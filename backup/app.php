<?php

use App\Http\Middleware\EnsureSessionIsActive;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\ResolveActiveOrganization;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
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

            // TODO: hapus bertahap setelah semua route pakai can:...
            'role'            => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->web(append: [
            EnsureSessionIsActive::class,
            ResolveActiveOrganization::class,
        ]);

        // User yang sudah login diarahkan berdasarkan izin, bukan nama role.
        // Urutan penting: Gate::before meloloskan super user di cek pertama.
        $middleware->redirectUsersTo(function (Request $request) {
            if (auth('applicant')->check()) {
                return route('ppdb.dashboard');
            }

            $user = $request->user();
            if (!$user) {
                return '/';
            }

            $gate = Gate::forUser($user);

            return match (true) {
                $gate->allows('access-admin-panel')    => route('admin.dashboard'),
                $gate->allows('access-ppdb-admin')     => route('ppdb-admin.dashboard'),
                $gate->allows('access-student-portal') => route('student.dashboard'),
                default                                => '/',
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 401: belum login -> arahkan ke halaman login sesuai portal
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
            }

            return redirect()->route(match (true) {
                $request->is('admin-ppdb', 'admin-ppdb/*') => 'ppdb-admin.login',
                $request->is('admin', 'admin/*')           => 'admin.login',
                $request->is('student', 'student/*')       => 'student.login',
                $request->is('ppdb', 'ppdb/*')             => 'ppdb.login',
                default                                    => 'login',
            });
        });

        // 403: dari abort(403), Gate::authorize(), maupun middleware can:
        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if ($e->getStatusCode() !== 403) {
                return null; // status lain ditangani Laravel
            }

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Akses ditolak.'], 403);
            }

            // Cegah redirect loop: dashboard itu sendiri yang ditolak
            if ($request->routeIs('*.dashboard')) {
                return null;
            }

            $target = match (true) {
                $request->is('admin-ppdb', 'admin-ppdb/*') => 'ppdb-admin.dashboard',
                $request->is('admin', 'admin/*')           => 'admin.dashboard',
                $request->is('student', 'student/*')       => 'student.dashboard',
                default                                    => null,
            };

            return ($target ? redirect()->route($target) : redirect('/'))
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        });
    })->create();