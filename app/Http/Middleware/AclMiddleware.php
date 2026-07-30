<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Models\AccessLog;

class AclMiddleware
{
    public function handle($request, Closure $next, $ability = null, $action = null, $modelClass = null)
{
    $user = $request->user();
    if (is_null($user)) {
        return redirect()->route('login');
    }

    // 1️⃣ SuperUser bypass
    if ($user->isSuperUser()) {
        $this->logAccess($user, $action ?? $ability, 'success', $request);
        return $next($request);
    }

    $allowed = false;

    // 2️⃣ User-specific permission override
    if ($action && UserPermission::where('user_id', $user->id)->where('action', $action)->exists()) {
        $allowed = true;
    }

    // 3️⃣ Role-based permission
    if (! $allowed && $action) {
        $roleIds = $user->roles()->pluck('id')->toArray();
        if (ActionRole::where('action_id', $action)->whereIn('role_id', $roleIds)->where('is_allowed', 1)->exists()) {
            $allowed = true;
        }
    }

    // 4️⃣ General permissions
    if (! $allowed && $action) {
        $permissionIds = $user->permissions()->pluck('id')->toArray();
        if (ActionPermission::where('action', $action)->whereIn('permission_id', $permissionIds)->exists()) {
            $allowed = true;
        }
    }

    // 5️⃣ Gate check
    if (! $allowed && $ability && ! Gate::allows($ability, $user)) {
        $this->logAccess($user, $ability, 'denied', $request);
        abort(403, 'Unauthorized by Gate');
    }

    // 6️⃣ Policy check
    if (! $allowed && $modelClass) {
        $modelId = $request->route('id');
        $model   = $modelClass::find($modelId);
        if ($model && ! $user->can($ability ?? $action, $model)) {
            $this->logAccess($user, $ability ?? $action, 'denied', $request);
            abort(403, 'Unauthorized by Policy');
        }
        $allowed = true;
    }

    // 7️⃣ Logging + response
    if (! $allowed) {
        $this->logAccess($user, $action ?? $ability, 'denied', $request);
        return $this->denyResponse($request);
    }

    $this->logAccess($user, $action ?? $ability, 'success', $request);
    return $next($request);
}


    protected function logAccess($user, $ability, $status, $request)
    {
        try {
            AccessLog::create([
                'user_id'    => $user->id,
                'action'     => $ability,
                'status'     => $status,
                'ip_address' => $request->ip(),
                'url'        => $request->fullUrl(),
            ]);
        } catch (\Exception $e) {
            Log::error("AuditTrail gagal: " . $e->getMessage());
        }
    }
}
