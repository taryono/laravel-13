<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
    {
        Schema::defaultStringLength(191);
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
    }

}
