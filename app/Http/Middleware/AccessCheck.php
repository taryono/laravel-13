<?php

namespace App\Http\Middleware;

use App\Support\PortalAuth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Models\AccessLog;
use Symfony\Component\HttpFoundation\Response;

class AccessCheck
{
    /**
     * Verify role or route/menu gate permissions and record an access audit log.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $route        = $request->route();
        $rawRouteName = $route ? $route->getName() : null;
        $access       = $route ? ($route->getAction()['as'] ?? $rawRouteName) : '';
        $accessSlug   = str_replace('.', '-', (string) $access);

        $user = $request->user();
        if (is_null($user)) {
            return PortalAuth::unauthenticatedResponse($request);
        }

        // SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if (PortalAuth::isSuperUser($user)) {
            $this->logAccess($user, $accessSlug ?: 'superuser', 'success', $request);
            return $next($request);
        }

        $parsedRoles = PortalAuth::parseList($roles);
        $hasAccess   = false;

        if (! empty($parsedRoles)) {
            $hasAccess = method_exists($user, 'hasAnyRole') && $user->hasAnyRole($parsedRoles);
        } else {
            $gate      = Gate::forUser($user);
            $hasAccess = ($accessSlug !== '' && $gate->allows($accessSlug))
                || ($rawRouteName && $gate->allows($rawRouteName))
                || ($access && $gate->allows($access));

            // Fallback ke Gate menu CRUD jika route mengikuti konvensi {resource}.{action}
            if (! $hasAccess && $rawRouteName && str_contains($rawRouteName, '.')) {
                $menuSlug   = PortalAuth::resolveMenuSlugFromRoute($rawRouteName);
                $crudAction = PortalAuth::inferCrudActionFromRoute($rawRouteName);
                $menuGate   = "{$menuSlug}.{$crudAction}";

                $hasAccess = ($gate->has($menuGate) && $gate->allows($menuGate))
                    || (method_exists($user, 'hasMenuPermission') && $user->hasMenuPermission($menuSlug, 'is_' . $crudAction));
            }
        }

        if (! $hasAccess) {
            $this->logAccess($user, $accessSlug ?: 'denied', 'denied', $request);
            return PortalAuth::forbiddenResponse($request, 'Anda tidak memiliki hak mengakses laman tersebut!', true);
        }

        $this->logAccess($user, $accessSlug ?: 'success', 'success', $request);
        return $next($request);
    }

    /**
     * Persist an access audit entry without failing the request if logging errors.
     */
    protected function logAccess(mixed $user, string $access, string $status, Request $request): void
    {
        try {
            if (class_exists(AccessLog::class)) {
                AccessLog::create([
                    'user_id'    => $user->id,
                    'action'     => $access,
                    'status'     => $status,
                    'ip_address' => $request->ip(),
                    'url'        => $request->fullUrl(),
                ]);
            }
        } catch (\Throwable $e) {
            // Abaikan kesalahan log agar tidak menghambat aliran aplikasi
        }
    }
}
