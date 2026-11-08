<?php

use App\Http\Middleware\EnsureSessionIsActive;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\ResolveActiveOrganization;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

        // ==========================================
        // GUEST REDIRECT (Pengganti RedirectIfAuthenticated)
        // ==========================================
        // Redirect user yang sudah login ke dashboard masing-masing
        $middleware->redirectUsersTo(function (Request $request) {
            if (auth('applicant')->check()) {
                return Route::has('ppdb.dashboard') ? route('ppdb.dashboard') : '/';
            }

            $user = $request->user();
            if (!$user) return '/';

            if ($user->hasAnyRole(['super-admin', 'admin-sekolah'])) {
                return Route::has('admin.dashboard')
                    ? route('admin.dashboard')
                    : (Route::has('dashboard') ? route('dashboard') : '/');
            }

            if ($user->hasRole('admin-ppdb')) {
                return Route::has('ppdb-admin.dashboard')
                    ? route('ppdb-admin.dashboard')
                    : (Route::has('dashboard') ? route('dashboard') : '/');
            }

            if ($user->hasAnyRole(['student', 'parent'])) {
                return Route::has('student.dashboard')
                    ? route('student.dashboard')
                    : (Route::has('dashboard') ? route('dashboard') : '/');
            }

            return Route::has('dashboard') ? route('dashboard') : '/';
        });

        // ==========================================
        // GUEST REDIRECT KE LOGIN
        // ==========================================
        // Redirect user yang belum login saat akses halaman protected
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin-ppdb/*')) {
                return Route::has('ppdb-admin.login') ? route('ppdb-admin.login') : route('login');
            }
            if ($request->is('admin/*')) {
                return Route::has('admin.login') ? route('admin.login') : route('login');
            }
            if ($request->is('student/*')) {
                return Route::has('student.login') ? route('student.login') : route('login');
            }
            if ($request->is('ppdb/*')) {
                return Route::has('ppdb.login') ? route('ppdb.login') : route('login');
            }

            return Route::has('login') ? route('login') : '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //  Integration::handles($exceptions);
        // Redirect 403 ke halaman yang sesuai
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Akses ditolak.'], 403);
            }

            // Redirect ke dashboard sesuai portal
            if ($request->is('admin-ppdb/*')) {
                $target = Route::has('ppdb-admin.dashboard') ? 'ppdb-admin.dashboard' : 'dashboard';
                return redirect()->route($target)
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            if ($request->is('admin/*')) {
                $target = Route::has('admin.dashboard') ? 'admin.dashboard' : 'dashboard';
                return redirect()->route($target)
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            if ($request->is('student/*')) {
                $target = Route::has('student.dashboard') ? 'student.dashboard' : 'dashboard';
                return redirect()->route($target)
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            $fallback = Route::has('dashboard') ? 'dashboard' : 'welcome.index';
            return redirect()->route($fallback)->with('error', 'Akses ditolak.');
        });

        // Redirect 401 (Unauthenticated)
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
            }

            // Redirect berdasarkan URL
            if ($request->is('admin-ppdb/*')) {
                $target = Route::has('ppdb-admin.login') ? 'ppdb-admin.login' : 'login';
                return redirect()->route($target);
            }
            if ($request->is('admin/*')) {
                $target = Route::has('admin.login') ? 'admin.login' : 'login';
                return redirect()->route($target);
            }
            if ($request->is('student/*')) {
                $target = Route::has('student.login') ? 'student.login' : 'login';
                return redirect()->route($target);
            }
            if ($request->is('ppdb/*')) {
                $target = Route::has('ppdb.login') ? 'ppdb.login' : 'login';
                return redirect()->route($target);
            }

            return redirect()->route('login');
        });
    })->create();
