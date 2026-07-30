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
        $access = $route->getAction()['as'] ?? $route->getName();
        $access = str_replace('.', '-', $access);

        $user = $request->user();
        if (is_null($user)) {
            return redirect()->route('login');
        }

        if ($user->isSuperUser()) {
            $this->logAccess($user, $access, 'success', $request);
            return $next($request);
        }

        $user_roles = $user->roles()->pluck('name')->toArray();
        $has_access = false;

        if (count($roles) > 0) {
            foreach ($roles as $role) {
                if (in_array(trim($role), $user_roles)) {
                    $has_access = true;
                    break;
                }
            }
        } else {
            $has_access = $user->can($access);
        }

        if (! $has_access) {
            $this->logAccess($user, $access, 'denied', $request);
            return $this->denyResponse($request);
        }

        $this->logAccess($user, $access, 'success', $request);
        return $next($request);
    }

    protected function logAccess($user, $access, $status, $request)
    {
        AccessLog::create([
            'user_id'    => $user->id,
            'action'     => $access,
            'status'     => $status,
            'ip_address' => $request->ip(),
            'url'        => $request->fullUrl(),
        ]);
    }

    protected function denyResponse($request)
    {
        if ($request->isMethod('GET')) {
            if ($request->ajax()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anda tidak memiliki hak mengakses laman tersebut!'
                ], 403);
            }
            abort(403, 'Anda tidak memiliki hak mengakses laman tersebut!');
        }
    }
}
