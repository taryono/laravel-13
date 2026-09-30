<?php

use App\Http\Middleware\EnsureSessionIsActive;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

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
            'superuser' => \App\Http\Middleware\EnsureSuperUser::class,
            'access'    => \App\Http\Middleware\AccessCheck::class,
            'acl'       => \App\Http\Middleware\AclMiddleware::class,
            'proctor' => \App\Http\Middleware\Proctor::class,
            'menu.permission' => \App\Http\Middleware\CheckMenuPermission::class,
            'guest' => RedirectIfAuthenticated::class,
            // Custom middleware per portal
            'applicant.auth'    => \App\Http\Middleware\ApplicantAuth::class,
            'student.auth'      => \App\Http\Middleware\StudentAuth::class,
            'parent.auth'       => \App\Http\Middleware\ParentAuth::class,
            'ppdb-admin.auth'   => \App\Http\Middleware\AdminPpdbAuth::class,

            // Generic role middleware
            'role'              => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        $middleware->web(append: [
            EnsureSessionIsActive::class,
        ]);

        // ==========================================
        // GUEST REDIRECT (Pengganti RedirectIfAuthenticated)
        // ==========================================
        // Redirect user yang sudah login ke dashboard masing-masing
        $middleware->redirectUsersTo(function (Request $request) {
            if (auth('applicant')->check()) return route('ppdb.dashboard');
            $user = $request->user();
            if (!$user) return '/';
            if ($user->hasAnyRole(['super-admin', 'admin-sekolah'])) return route('admin.dashboard');
            if ($user->hasRole('admin-ppdb')) return route('ppdb-admin.dashboard');
            if ($user->hasAnyRole(['student', 'parent'])) return route('student.dashboard');

            return '/';
        });

        // ==========================================
        // GUEST REDIRECT KE LOGIN
        // ==========================================
        // Redirect user yang belum login saat akses halaman protected
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin-ppdb/*')) return route('ppdb-admin.login');
            if ($request->is('admin/*'))      return route('admin.login');
            if ($request->is('student/*'))    return route('student.login');
            if ($request->is('ppdb/*'))       return route('ppdb.login');
            return route('login');
        });

        $middleware->append(\App\Http\Middleware\ResolveActiveOrganization::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //  Integration::handles($exceptions);
        // Redirect 403 ke halaman yang sesuai
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Akses ditolak.'], 403);
            }

            // Redirect ke dashboard sesuai portal
            if ($request->is('admin-ppdb/*')) {
                return redirect()->route('ppdb-admin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            if ($request->is('admin/*')) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            if ($request->is('student/*')) {
                return redirect()->route('student.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect('/')->with('error', 'Akses ditolak.');
        });

        // Redirect 401 (Unauthenticated)
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
            }

            // Redirect berdasarkan URL
            if ($request->is('admin-ppdb/*')) {
                return redirect()->route('ppdb-admin.login');
            }
            if ($request->is('admin/*')) {
                return redirect()->route('admin.login');
            }
            if ($request->is('student/*')) {
                return redirect()->route('student.login');
            }
            if ($request->is('ppdb/*')) {
                return redirect()->route('ppdb.login');
            }

            return redirect()->route('login');
        });
    })->create();
