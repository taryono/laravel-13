<?php

namespace App\Http\Middleware;

use Closure;
use Models\UserPermission;
use Models\ActionRole;
use Models\ActionPermission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Models\AccessLog;
use Symfony\Component\HttpFoundation\Response;

class AclMiddleware
{
    public function handle($request, Closure $next, $ability = null, $action = null, $modelClass = null): Response
    {
        $user = $request->user();
        if (is_null($user)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
            }
            return redirect()->route('login');
        }

        // 1️⃣ SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if ($user->isSuperUser()) {
            $this->logAccess($user, $action ?? $ability ?? 'superuser-access', 'success', $request);
            return $next($request);
        }

        $allowed = false;

        // 2️⃣ User-specific permission override
        if ($action && class_exists(UserPermission::class) && UserPermission::where('user_id', $user->id)->where('action', $action)->exists()) {
            $allowed = true;
        }

        // 3️⃣ Action permission check (selaras dengan Action Gate di AuthServiceProvider)
        if (! $allowed && $action) {
            if (method_exists($user, 'hasActionPermission') && $user->hasActionPermission($action)) {
                $allowed = true;
            } elseif (class_exists(ActionRole::class)) {
                $roleIds = $user->roles()->pluck('id')->toArray();
                if (ActionRole::where('action_id', $action)->whereIn('role_id', $roleIds)->where('is_allowed', 1)->exists()) {
                    $allowed = true;
                }
            }
        }

        // 4️⃣ General permissions
        if (! $allowed && $action && class_exists(ActionPermission::class) && method_exists($user, 'permissions')) {
            $permissionIds = $user->permissions()->pluck('id')->toArray();
            if (ActionPermission::where('action', $action)->whereIn('permission_id', $permissionIds)->exists()) {
                $allowed = true;
            }
        }

        // 5️⃣ Gate check (selaras dengan AuthServiceProvider)
        if (! $allowed && $ability) {
            if (Gate::forUser($user)->allows($ability)) {
                $allowed = true;
            }
        }

        // 6️⃣ Policy check
        if (! $allowed && $modelClass) {
            $modelId = $request->route('id') ?? $request->route(strtolower(class_basename($modelClass)));
            $model   = class_exists($modelClass) ? $modelClass::find($modelId) : null;
            if ($model && $user->can($ability ?? $action, $model)) {
                $allowed = true;
            }
        }

        // 7️⃣ Logging + response
        if (! $allowed) {
            $this->logAccess($user, $action ?? $ability ?? 'unauthorized', 'denied', $request);
            return $this->denyResponse($request);
        }

        $this->logAccess($user, $action ?? $ability ?? 'authorized', 'success', $request);
        return $next($request);
    }

    protected function logAccess($user, $ability, $status, $request): void
    {
        try {
            if (class_exists(AccessLog::class)) {
                AccessLog::create([
                    'user_id'    => $user->id,
                    'action'     => $ability,
                    'status'     => $status,
                    'ip_address' => $request->ip(),
                    'url'        => $request->fullUrl(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("AuditTrail gagal: " . $e->getMessage());
        }
    }

    protected function denyResponse($request): Response
    {
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke halaman atau aksi ini.',
            ], 403);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman atau aksi ini.');
    }
}
