<?php

use App\Http\Middleware\EnsureSessionIsActive;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\ResolveActiveOrganization;
use App\Support\PortalAuth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

// use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
        $middleware->alias([
            'superuser'       => \App\Http\Middleware\EnsureSuperUser::class,
            'access'          => \App\Http\Middleware\AccessCheck::class,
            'acl'             => \App\Http\Middleware\AclMiddleware::class,
            'proctor'         => \App\Http\Middleware\Proctor::class,
            'menu.permission' => \App\Http\Middleware\CheckMenuPermission::class,
            'guest'           => RedirectIfAuthenticated::class,
            // Custom middleware per portal
            'applicant.auth'  => \App\Http\Middleware\ApplicantAuth::class,
            'student.auth'    => \App\Http\Middleware\StudentAuth::class,
            'parent.auth'     => \App\Http\Middleware\ParentAuth::class,
            'ppdb-admin.auth' => \App\Http\Middleware\AdminPpdbAuth::class,
            'tenant.role'     => \App\Http\Middleware\EnsureTenantRole::class,
            // Generic role middleware
            'role'            => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Middleware web yang dijalankan setelah sesi dan auth aktif
        $middleware->web(append: [
            EnsureSessionIsActive::class,
            ResolveActiveOrganization::class,
        ]);

        // Otomatis menerapkan RateLimiter::for('api', ...) ke seluruh group middleware 'api'
        $middleware->throttleApi('api');
        
        // Jika menggunakan Redis untuk menyimpan counter rate limit (sangat disarankan di production)
        $middleware->throttleWithRedis();

        // ==========================================
        // GUEST REDIRECT (RedirectIfAuthenticated)
        // ==========================================
        // Redirect user yang sudah login ke dashboard masing-masing
        $middleware->redirectUsersTo(fn (Request $request) => PortalAuth::dashboardUrlForUser($request));

        // ==========================================
        // GUEST REDIRECT KE LOGIN
        // ==========================================
        // Redirect user yang belum login saat akses halaman protected
        $middleware->redirectGuestsTo(
            fn (Request $request) => PortalAuth::wantsJson($request) ? null : PortalAuth::loginUrlFor($request)
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //  Integration::handles($exceptions);
        // Redirect 403 (dari abort(403), Gate::authorize(), atau middleware can:)
        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if ($e->getStatusCode() !== 403) {
                return null;
            }

            if (PortalAuth::wantsJson($request)) {
                return response()->json(['error' => $e->getMessage() ?: 'Akses ditolak.'], 403);
            }

            $targetUrl = PortalAuth::forbiddenRedirectUrl($request);
            if ($targetUrl === null) {
                return null;
            }

            return redirect()->to($targetUrl)
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        });

        // Redirect 401 (Unauthenticated)
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if (PortalAuth::wantsJson($request)) {
                return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
            }

            return redirect()->guest(PortalAuth::loginUrlFor($request));
        });
    })->create();
