<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Pakai: ->middleware('tenant.role:owner')  atau  'tenant.role:owner,cashier'
 * Memastikan user login terdaftar di kantin pada URL dan punya peran yang diizinkan.
 */
class EnsureTenantRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $tenantId = (int) $request->route('tenant');

        $role = DB::table('canteen_tenant_users')
            ->where('canteen_tenant_id', $tenantId)
            ->where('user_id', $request->user()->id)
            ->value('role');

        if (! $role || ($roles && ! in_array($role, $roles, true))) {
            abort(403, 'Tidak punya akses ke kantin ini.');
        }

        $request->attributes->set('tenant_role', $role);

        return $next($request);
    }
}