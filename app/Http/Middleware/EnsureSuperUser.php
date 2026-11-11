<?php

namespace App\Http\Middleware;

use App\Support\PortalAuth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperUser
{
    /**
     * Allow access only for authenticated superuser accounts.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            return PortalAuth::unauthenticatedResponse($request);
        }

        if (! (PortalAuth::isSuperUser($user) || isSuperUser($user))) {
            return PortalAuth::forbiddenResponse($request, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
