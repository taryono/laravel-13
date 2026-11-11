<?php

namespace App\Http\Middleware;

use App\Support\PortalAuth;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pakai: ->middleware('tenant.role:owner')  atau  'tenant.role:owner,cashier'
 * Memastikan user login terdaftar di kantin pada URL dan punya peran yang diizinkan.
 */
class EnsureTenantRole
{
    /**
     * Ensure the authenticated user has one of the required canteen tenant roles.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (! $user) {
            return PortalAuth::unauthenticatedResponse($request);
        }

        $tenantParam = $request->route('tenant');
        $tenantId    = $tenantParam instanceof Model ? (int) $tenantParam->getKey() : (int) $tenantParam;

        if ($tenantId <= 0 || ! Schema::hasTable('canteen_tenant_users')) {
            return PortalAuth::forbiddenResponse($request, 'Tidak punya akses ke kantin ini.');
        }

        $role = DB::table('canteen_tenant_users')
            ->where('canteen_tenant_id', $tenantId)
            ->where('user_id', $user->id)
            ->value('role');

        $allowedRoles = PortalAuth::parseList($roles);

        if (! $role || (! empty($allowedRoles) && ! in_array($role, $allowedRoles, true))) {
            return PortalAuth::forbiddenResponse($request, 'Tidak punya akses ke kantin ini.');
        }

        $request->attributes->set('tenant_role', $role);

        return $next($request);
    }
}
