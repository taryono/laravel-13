<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Models\AccessLog;

class AccessCheck
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $route  = $request->route();
        $rawRouteName = $route ? $route->getName() : null;
        $access = $route ? ($route->getAction()['as'] ?? $rawRouteName) : '';
        $accessSlug = str_replace('.', '-', (string)$access);

        $user = $request->user();
        if (is_null($user)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
            }
            return redirect()->route('login');
        }

        // SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if ($user->isSuperUser()) {
            $this->logAccess($user, $accessSlug ?: 'superuser', 'success', $request);
            return $next($request);
        }

        $has_access = false;

        if (count($roles) > 0) {
            $has_access = $user->hasAnyRole($roles);
        } else {
            // Cek Gate (terdaftar di AuthServiceProvider)
            $has_access = $user->can($accessSlug)
                || ($rawRouteName && $user->can($rawRouteName))
                || ($access && $user->can($access));
        }

        if (! $has_access) {
            $this->logAccess($user, $accessSlug ?: 'denied', 'denied', $request);
            return $this->denyResponse($request);
        }

        $this->logAccess($user, $accessSlug ?: 'success', 'success', $request);
        return $next($request);
    }

    protected function logAccess($user, $access, $status, $request): void
    {
        try {
            if (class_exists(AccessLog::class)) {
                AccessLog::create([
                    'user_id'    => $user->id,
                    'action'     => $access,
                    'status'     => $status,
                    'ip_address' => $request->ip(),
                    'url'        => $request->fullUrl(),
                ]);
            }
        } catch (\Throwable $e) {
            // Abaikan kesalahan log agar tidak menghambat aliran aplikasi
        }
    }

    protected function denyResponse($request): Response
    {
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki hak mengakses laman tersebut!'
            ], 403);
        }

        abort(403, 'Anda tidak memiliki hak mengakses laman tersebut!');
    }
}
