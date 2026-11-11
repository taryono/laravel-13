<?php

namespace App\Http\Middleware;

use App\Support\PortalAuth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Models\AccessLog;
use Models\Action;
use Models\ActionPermission;
use Models\ActionRole;
use Models\ActionUser;
use Models\UserPermission;
use Symfony\Component\HttpFoundation\Response;

class AclMiddleware
{
    /**
     * Enforce fine-grained ACL permissions across overrides, actions, gates, and policies.
     */
    public function handle(
        Request $request,
        Closure $next,
        ?string $ability = null,
        ?string $action = null,
        ?string $modelClass = null
    ): Response {
        $user = $request->user();
        if (is_null($user)) {
            return PortalAuth::unauthenticatedResponse($request);
        }

        $ability    = $this->normalizeParam($ability);
        $action     = $this->normalizeParam($action);
        $modelClass = $this->normalizeParam($modelClass);

        if ($ability === null && $action === null && $modelClass === null) {
            $ability = $request->route()?->getName();
        }

        // 1. SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if (PortalAuth::isSuperUser($user)) {
            $this->logAccess($user, $action ?? $ability ?? 'superuser-access', 'success', $request);
            return $next($request);
        }

        $allowed = false;

        // 2. User-specific permission override (hanya jika tabel & kolom tersedia)
        if ($action && class_exists(UserPermission::class)) {
            $table = (new UserPermission())->getTable();
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'action')) {
                $allowed = UserPermission::where('user_id', $user->id)
                    ->where('action', $action)
                    ->exists();
            }
        }

        // 3. Action permission check (action_user & action_role)
        if (! $allowed && $action) {
            $allowed = $this->checkActionAccess($user, $action);
        }

        // 4. General action-permission mapping (hanya jika tabel & relasi tersedia)
        if (! $allowed && $action && class_exists(ActionPermission::class) && method_exists($user, 'permissions')) {
            $table = (new ActionPermission())->getTable();
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'action')) {
                $permissionIds = $user->permissions()->pluck('permissions.id')->toArray();
                if (! empty($permissionIds)) {
                    $allowed = ActionPermission::where('action', $action)
                        ->whereIn('permission_id', $permissionIds)
                        ->exists();
                }
            }
        }

        // 5. Gate check (tanpa model atau ability langsung)
        $gateAbility = $ability ?? $action;
        if (! $allowed && $ability && Gate::forUser($user)->allows($ability)) {
            $allowed = true;
        }

        // 6. Policy check (instance model atau class-level)
        if (! $allowed && $modelClass && class_exists($modelClass) && $gateAbility) {
            $allowed = $this->checkPolicyAccess($request, $user, $gateAbility, $modelClass);
        }

        // 7. Logging + response
        if (! $allowed) {
            $this->logAccess($user, $action ?? $ability ?? 'unauthorized', 'denied', $request);
            return PortalAuth::forbiddenResponse($request, 'Anda tidak memiliki akses ke halaman atau aksi ini.', true);
        }

        $this->logAccess($user, $action ?? $ability ?? 'authorized', 'success', $request);
        return $next($request);
    }

    /**
     * Check direct user action and role-based action permissions.
     */
    protected function checkActionAccess(mixed $user, string $action): bool
    {
        if (method_exists($user, 'hasActionPermission') && $user->hasActionPermission($action)) {
            return true;
        }

        if (! Schema::hasTable('actions')) {
            return false;
        }

        $actionId = is_numeric($action)
            ? (int) $action
            : Action::withoutGlobalScope('organization_id')->where('name', $action)->value('id');

        if (! $actionId) {
            return false;
        }

        if (class_exists(ActionUser::class) && Schema::hasTable('action_user')) {
            $direct = ActionUser::where('action_id', $actionId)
                ->where('user_id', $user->id)
                ->exists();
            if ($direct) {
                return true;
            }
        }

        if (class_exists(ActionRole::class) && Schema::hasTable('action_role') && Schema::hasTable('role_user')) {
            $orgId   = $user->active_organization_id ?? null;
            $roleIds = DB::table('role_user')
                ->where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->when($orgId, function ($query) use ($orgId) {
                    $query->where(function ($sub) use ($orgId) {
                        $sub->where('organization_id', $orgId)
                            ->orWhereNull('organization_id');
                    });
                })
                ->pluck('role_id')
                ->all();

            if (! empty($roleIds)) {
                return ActionRole::where('action_id', $actionId)
                    ->whereIn('role_id', $roleIds)
                    ->where('is_allowed', 1)
                    ->exists();
            }
        }

        return false;
    }

    /**
     * Resolve model instance (or class name) from route parameters and check Policy gate.
     */
    protected function checkPolicyAccess(Request $request, mixed $user, string $ability, string $modelClass): bool
    {
        $base       = class_basename($modelClass);
        $routeParam = $request->route('id')
            ?? $request->route(Str::snake($base))
            ?? $request->route(Str::camel($base))
            ?? $request->route(strtolower($base));

        if ($routeParam === null) {
            return Gate::forUser($user)->allows($ability, $modelClass);
        }

        $model = $routeParam instanceof $modelClass
            ? $routeParam
            : $modelClass::find($routeParam);

        return $model ? Gate::forUser($user)->allows($ability, $model) : false;
    }

    protected function normalizeParam(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);
        return $trimmed === '' ? null : $trimmed;
    }

    protected function logAccess(mixed $user, string $ability, string $status, Request $request): void
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
            Log::error('AuditTrail gagal: ' . $e->getMessage());
        }
    }
}
