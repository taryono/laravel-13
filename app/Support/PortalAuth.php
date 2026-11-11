<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class PortalAuth
{
    /**
     * Role names/codes granted access to the student portal.
     */
    public const STUDENT_PORTAL_ROLES = [
        'student', 'siswa', 'Siswa',
        'parent', 'orang-tua', 'orang_tua', 'Orang Tua',
    ];

    /**
     * Role names/codes granted access to the parent portal.
     */
    public const PARENT_PORTAL_ROLES = [
        'parent', 'orang-tua', 'orang_tua', 'Orang Tua',
    ];

    /**
     * Role names/codes granted access to the PPDB admin portal.
     */
    public const PPDB_ADMIN_ROLES = [
        'admin-ppdb', 'admin_ppdb', 'Panitia PPDB',
        'super-admin', 'super_admin', 'Super Administrator',
        'Administrator', 'admin', 'Admin',
    ];

    /**
     * Role names/codes granted access to the CBT proctor dashboard.
     */
    public const PROCTOR_ROLES = [
        'proctor', 'pengawas', 'admin', 'supervisor', 'operator-cbt',
    ];

    /**
     * Determine whether the request expects a JSON response.
     */
    public static function wantsJson(Request $request): bool
    {
        return $request->expectsJson()
            || $request->ajax()
            || $request->is('api/*', 'canteen/*', 'proctoring/*');
    }

    /**
     * Resolve the login route name for the request's portal.
     */
    public static function loginRouteFor(Request $request, string $default = 'login'): string
    {
        return match (true) {
            $request->is('admin-ppdb', 'admin-ppdb/*', 'dashboard/admin', 'dashboard/admin/*') => 'ppdb-admin.login',
            $request->is('admin', 'admin/*')                                                   => 'admin.login',
            $request->is('student', 'student/*')                                               => 'student.login',
            $request->is('ppdb', 'ppdb/*')                                                     => 'ppdb.login',
            default                                                                            => $default,
        };
    }

    /**
     * Resolve the login URL for the request's portal with safe fallbacks.
     */
    public static function loginUrlFor(Request $request, string $default = 'login'): string
    {
        $routeName = self::loginRouteFor($request, $default);

        if (Route::has($routeName)) {
            return route($routeName);
        }

        if ($routeName !== 'login' && Route::has('login')) {
            return route('login');
        }

        return '/';
    }

    /**
     * Resolve the home/dashboard URL for an authenticated user.
     */
    public static function dashboardUrlForUser(Request $request): string
    {
        if (Auth::guard('applicant')->check() && ($request->is('ppdb', 'ppdb/*') || ! $request->user())) {
            return Route::has('ppdb.dashboard') ? route('ppdb.dashboard') : '/';
        }

        $user = $request->user();
        if (! $user) {
            return '/';
        }

        $hasAnyRole = method_exists($user, 'hasAnyRole');

        if ($request->is('admin-ppdb', 'admin-ppdb/*') && $hasAnyRole && $user->hasAnyRole(self::PPDB_ADMIN_ROLES)) {
            return self::firstExistingRouteUrl(['ppdb-admin.dashboard', 'dashboard']);
        }

        if ($hasAnyRole && $user->hasAnyRole(['super-admin', 'admin-sekolah', 'admin-yayasan'])) {
            return self::firstExistingRouteUrl(['admin.dashboard', 'dashboard']);
        }

        if ($hasAnyRole && $user->hasAnyRole(['admin-ppdb', 'admin_ppdb', 'Panitia PPDB'])) {
            return self::firstExistingRouteUrl(['ppdb-admin.dashboard', 'dashboard']);
        }

        if ($hasAnyRole && $user->hasAnyRole(self::STUDENT_PORTAL_ROLES)) {
            return self::firstExistingRouteUrl(['student.dashboard', 'dashboard']);
        }

        return self::firstExistingRouteUrl(['dashboard']);
    }

    /**
     * Resolve a safe redirect URL after a 403 error, returning null to avoid loops.
     */
    public static function forbiddenRedirectUrl(Request $request): ?string
    {
        if ($request->routeIs('*.dashboard', 'dashboard', 'dashboard.admin.*', 'welcome.index')) {
            return null;
        }

        $user = $request->user();
        if (! $user && ! Auth::guard('applicant')->check()) {
            return null;
        }

        $hasAnyRole = $user && method_exists($user, 'hasAnyRole');

        $target = match (true) {
            $request->is('admin-ppdb', 'admin-ppdb/*') && $hasAnyRole && $user->hasAnyRole(self::PPDB_ADMIN_ROLES) => self::firstExistingRouteUrl(['ppdb-admin.dashboard', 'dashboard', 'welcome.index']),
            $request->is('admin', 'admin/*') && $hasAnyRole && $user->hasAnyRole(['super-admin', 'admin-sekolah', 'admin-yayasan', 'admin', 'Admin']) => self::firstExistingRouteUrl(['admin.dashboard', 'dashboard', 'welcome.index']),
            $request->is('student', 'student/*') && $hasAnyRole && $user->hasAnyRole(self::STUDENT_PORTAL_ROLES) => self::firstExistingRouteUrl(['student.dashboard', 'dashboard', 'welcome.index']),
            default => $user ? self::dashboardUrlForUser($request) : self::firstExistingRouteUrl(['ppdb.dashboard', 'welcome.index']),
        };

        if ($target === '/' && Route::has('welcome.index')) {
            $target = route('welcome.index');
        }

        $current = rtrim($request->url(), '/');
        if (rtrim($target, '/') === $current) {
            return null;
        }

        return $target;
    }

    /**
     * Safely check whether the given user model is a superuser.
     */
    public static function isSuperUser(mixed $user): bool
    {
        return is_object($user) && method_exists($user, 'isSuperUser') && $user->isSuperUser();
    }

    /**
     * Return a 401 JSON response or portal-aware guest redirect to login.
     */
    public static function unauthenticatedResponse(
        Request $request,
        string $message = 'Silakan login terlebih dahulu.',
        string $defaultLoginRoute = 'login'
    ): Response {
        if (self::wantsJson($request)) {
            return response()->json(['error' => $message], 401);
        }

        return redirect()->guest(self::loginUrlFor($request, $defaultLoginRoute))
            ->with('error', $message);
    }

    /**
     * Return a 403 JSON response or abort(403) for web requests.
     */
    public static function forbiddenResponse(
        Request $request,
        string $message = 'Anda tidak memiliki akses ke halaman ini.',
        bool $includeStatus = false
    ): Response {
        if (self::wantsJson($request)) {
            $payload = $includeStatus
                ? ['status' => 'error', 'message' => $message, 'error' => $message]
                : ['error' => $message];

            return response()->json($payload, 403);
        }

        abort(403, $message);
    }

    /**
     * Split comma- or pipe-separated middleware parameters into unique trimmed values.
     *
     * @param  array<int, mixed>  $values
     * @return array<int, string>
     */
    public static function parseList(array $values): array
    {
        $parsed = [];
        foreach ($values as $value) {
            foreach (preg_split('/[,|]/', (string) $value) ?: [] as $item) {
                $trimmed = trim($item);
                if ($trimmed !== '') {
                    $parsed[] = $trimmed;
                }
            }
        }

        return array_values(array_unique($parsed));
    }

    /**
     * Resolve menu slug from a route name by checking the menus table.
     */
    public static function resolveMenuSlugFromRoute(?string $routeName): string
    {
        if (! $routeName) {
            return 'menu';
        }

        if (class_exists(\Models\Menu::class) && Schema::hasTable('menus')) {
            $menu = \Models\Menu::withoutGlobalScope('organization_id')
                ->where('route', $routeName)
                ->first();

            if (! $menu) {
                $prefix = explode('.', $routeName)[0] ?? '';
                if ($prefix !== '') {
                    $menu = \Models\Menu::withoutGlobalScope('organization_id')
                        ->where(function ($q) use ($prefix) {
                            $q->where('slug', $prefix)
                                ->orWhere('slug', str_replace('_', '-', $prefix))
                                ->orWhere('route', $prefix . '.index');
                        })
                        ->first();
                }
            }

            if ($menu && ! empty($menu->slug)) {
                return $menu->slug;
            }
        }

        return str_replace('.', '-', $routeName);
    }

    /**
     * Map a route suffix or action alias to normalized CRUD action (view/create/edit/delete).
     */
    public static function normalizeCrudAction(?string $rawAction): string
    {
        return match (strtolower((string) $rawAction)) {
            'create', 'store', 'is_create'   => 'create',
            'edit', 'update', 'is_edit'      => 'edit',
            'delete', 'destroy', 'is_delete' => 'delete',
            default                          => 'view',
        };
    }

    /**
     * Infer CRUD action from the last segment of a route name.
     */
    public static function inferCrudActionFromRoute(?string $routeName): string
    {
        if (! $routeName || ! str_contains($routeName, '.')) {
            return 'view';
        }

        $segments = explode('.', $routeName);
        $suffix = end($segments) ?: '';

        return self::normalizeCrudAction($suffix);
    }

    /**
     * Return the URL of the first registered route name, or '/'.
     *
     * @param  array<int, string>  $candidates
     */
    public static function firstExistingRouteUrl(array $candidates): string
    {
        foreach ($candidates as $name) {
            if (Route::has($name)) {
                return route($name);
            }
        }

        return '/';
    }
}
