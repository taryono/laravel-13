<?php

namespace App\Http\Middleware;

use App\Support\PortalAuth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return PortalAuth::unauthenticatedResponse($request);
        }

        // SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if (PortalAuth::isSuperUser($user)) {
            return $next($request);
        }

        $parsedRoles = PortalAuth::parseList($roles);
        if (empty($parsedRoles)) {
            return $next($request);
        }

        if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($parsedRoles)) {
            return $next($request);
        }

        return PortalAuth::forbiddenResponse($request, 'Anda tidak memiliki hak akses (peran) untuk halaman ini.');
    }
}
