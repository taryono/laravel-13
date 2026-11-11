<?php

namespace App\Http\Middleware;

use App\Support\PortalAuth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $param1
     * @param  string|null  $param2
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ?string $param1 = null, ?string $param2 = null): Response
    {
        $user = $request->user();
        if (! $user) {
            return PortalAuth::unauthenticatedResponse($request);
        }

        // SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if (PortalAuth::isSuperUser($user)) {
            return $next($request);
        }

        $knownActions = [
            'view', 'is_view', 'index', 'show',
            'create', 'is_create', 'store',
            'edit', 'is_edit', 'update',
            'delete', 'is_delete', 'destroy',
        ];

        $routeName = $request->route()?->getName();

        if ($param2 !== null) {
            $menuSlug  = $param1;
            $rawAction = $param2;
        } elseif ($param1 !== null && in_array(strtolower($param1), $knownActions, true)) {
            $menuSlug  = null;
            $rawAction = $param1;
        } elseif ($param1 !== null) {
            $menuSlug  = $param1;
            $rawAction = 'is_view';
        } else {
            $menuSlug  = null;
            $rawAction = PortalAuth::inferCrudActionFromRoute($routeName);
        }

        $gateAction   = PortalAuth::normalizeCrudAction($rawAction);
        $columnAction = 'is_' . $gateAction;

        if (empty($menuSlug)) {
            $menuSlug = PortalAuth::resolveMenuSlugFromRoute($routeName);
        }

        // 1. Cek lewat Gate yang didefinisikan di AuthServiceProvider (misal: "user.view", "user.create")
        $gateName = "{$menuSlug}.{$gateAction}";
        if (Gate::has($gateName) && Gate::forUser($user)->allows($gateName)) {
            return $next($request);
        }

        // 2. Fallback cek langsung lewat method hasMenuPermission pada model User
        if (method_exists($user, 'hasMenuPermission') && $user->hasMenuPermission($menuSlug, $columnAction)) {
            return $next($request);
        }

        return PortalAuth::forbiddenResponse($request, 'Anda tidak memiliki akses ke halaman ini.', true);
    }
}
