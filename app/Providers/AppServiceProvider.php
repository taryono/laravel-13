<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use App\Core\Menu\MenuService;
use App\Core\Menu\MenuTreeBuilder;
use Models\Organization;
use Models\Employee;
use App\Observers\EmployeeObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    // app/Providers/AppServiceProvider.php
    public function register()
    {
        $this->app->singleton(MenuTreeBuilder::class);
        $this->app->singleton(MenuService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        $this->configureRateLimiting();
        $modulesPath = app_path('Modules');

        if (File::exists($modulesPath)) {
            $modules = File::directories($modulesPath);

            foreach ($modules as $module) {
                $moduleName = basename($module);
                $kebabName = Str::kebab($moduleName); // Contoh: Product -> product

                // FIX: Menggunakan Route::group() untuk meload file routes.php secara aman
                if (File::exists("{$module}/routes.php")) {
                    Route::group([], "{$module}/routes.php");
                }
                if (File::exists("{$module}/Views")) {
                    View::addNamespace($moduleName, "{$module}/Views");
                }
            }
        }

        View::composer('*', function ($view) {
            $view->with('assetVersion', config('app.asset_version', time()));
        });

        View::composer('layouts.*', function ($view) {
            if (!Schema::hasTable('organizations')) {
                return $view;
            }
            $view->with('organization', Organization::first());
        });
        Employee::observe(EmployeeObserver::class);
        \Carbon\Carbon::setLocale('id');
        // Agar recipient_type berisi 'student' dan bukan nama kelas lengkap, daftarkan morph map di AppServiceProvider::boot():
        Relation::enforceMorphMap([
            'user' => \App\Models\User::class,
            'student' => \Models\Student::class,
            'employee' => Employee::class,
        ]);
    }

    protected function configureRateLimiting(): void
    {
        // 1. Limiter Khusus Login (Kombinasi Email/Username + IP)
        // Membatasi maksimal 5 percobaan per menit agar tidak mudah di-brute-force
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input('email', '')).'|'.$request->ip()
            );

            return [
                // Batas ketat per kombinasi akun + IP
                Limit::perMinute(5)->by($throttleKey)->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . ($headers['Retry-After'] ?? 60) . ' detik.',
                    ], 429, $headers);
                }),
                // Batas global per IP (mencegah satu IP mencoba ribuan email berbeda)
                Limit::perMinute(20)->by($request->ip()),
            ];
        });

        // 2. Limiter Umum API (User ID jika sudah login, atau IP jika guest)
        RateLimiter::for('api', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(120)->by('user:'.$request->user()->id)
                : Limit::perMinute(60)->by('ip:'.$request->ip());
        });

        // 3. Limiter Ketat untuk Endpoint Berat / Sensitif (misal: Export PDF/Word, Sync, Payment Webhook)
        RateLimiter::for('heavy-operations', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
    }

}
