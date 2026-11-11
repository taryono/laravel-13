<?php

namespace App\Http\Middleware;

use App\Support\PortalAuth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Proctor
{
    /**
     * Daftar peran yang diizinkan mengakses dasbor pengawas.
     */
    protected array $allowedRoles = PortalAuth::PROCTOR_ROLES;

    /**
     * Restrict proctoring routes to superusers or allowed proctor roles.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return PortalAuth::unauthenticatedResponse($request);
        }

        // SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if (PortalAuth::isSuperUser($user)) {
            return $next($request);
        }

        if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($this->allowedRoles)) {
            return $next($request);
        }

        return PortalAuth::forbiddenResponse($request, 'Akses hanya untuk Pengawas.');
    }
}
