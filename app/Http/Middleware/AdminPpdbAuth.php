<?php

namespace App\Http\Middleware;

use App\Support\PortalAuth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminPpdbAuth
{
    /**
     * Authenticate PPDB admin panel users and superusers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('web')->check()) {
            return PortalAuth::unauthenticatedResponse($request, 'Silakan login sebagai admin.', 'ppdb-admin.login');
        }

        $user = Auth::guard('web')->user();

        // SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if (PortalAuth::isSuperUser($user)) {
            return $next($request);
        }

        if (! $user || ! method_exists($user, 'hasAnyRole') || ! $user->hasAnyRole(PortalAuth::PPDB_ADMIN_ROLES)) {
            return PortalAuth::forbiddenResponse($request, 'Anda tidak memiliki akses ke panel admin PPDB.');
        }

        return $next($request);
    }
}
