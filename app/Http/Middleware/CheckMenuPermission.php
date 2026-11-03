<?php

namespace App\Http\Middleware;

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
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
            }
            return redirect()->route('login');
        }

        // SuperUser bypass (selaras dengan Gate::before di AuthServiceProvider)
        if ($user->isSuperUser()) {
            return $next($request);
        }

        // Tentukan $menuSlug dan $permission
        $knownActions = ['view', 'is_view', 'index', 'show', 'create', 'is_create', 'store', 'edit', 'is_edit', 'update', 'delete', 'is_delete', 'destroy'];

        if ($param2 !== null) {
            $menuSlug = $param1;
            $rawAction = $param2;
        } elseif ($param1 !== null && in_array(strtolower($param1), $knownActions, true)) {
            $menuSlug = null;
            $rawAction = $param1;
        } else {
            $menuSlug = $param1;
            $rawAction = 'is_view';
        }

        // Normalisasi nama aksi CRUD
        $gateAction = match (strtolower((string)$rawAction)) {
            'create', 'store', 'is_create' => 'create',
            'edit', 'update', 'is_edit'    => 'edit',
            'delete', 'destroy', 'is_delete' => 'delete',
            default                        => 'view',
        };
        $columnAction = 'is_' . $gateAction;

        // Jika $menuSlug belum ditentukan, cari otomatis berdasarkan rute
        if (empty($menuSlug)) {
            $routeName = $request->route() ? $request->route()->getName() : null;
            if ($routeName && class_exists(\Models\Menu::class)) {
                $menu = \Models\Menu::where('route', $routeName)->first();
                if (!$menu) {
                    $prefix = explode('.', $routeName)[0] ?? '';
                    $menu = \Models\Menu::where('slug', $prefix)
                        ->orWhere('slug', str_replace('_', '-', $prefix))
                        ->first();
                }
                $menuSlug = $menu?->slug ?? str_replace('.', '-', $routeName);
            } else {
                $menuSlug = $routeName ? str_replace('.', '-', $routeName) : 'menu';
            }
        }

        // 1. Cek lewat Gate yang didefinisikan di AuthServiceProvider (misal: "user.view", "user.create")
        $gateName = "{$menuSlug}.{$gateAction}";
        if (Gate::has($gateName)) {
            if (Gate::forUser($user)->allows($gateName)) {
                return $next($request);
            }
        }

        // 2. Fallback cek langsung lewat method hasMenuPermission pada model User
        if (method_exists($user, 'hasMenuPermission') && $user->hasMenuPermission($menuSlug, $columnAction)) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke halaman ini.',
            ], 403);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
