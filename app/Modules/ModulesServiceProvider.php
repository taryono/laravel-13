<?php

namespace App\Modules;

use Lib\Directories;

/**
 * ServiceProvider
 *
 * The service provider for the modules. After being registered
 * it will make sure that each of the modules are properly loaded
 * i.e. with their routes, views etc.
 *
 * @package App\Modules
 */
class ModulesServiceProvider extends \Illuminate\Support\ServiceProvider

{
    /**
     * Will make sure that the required modules have been fully loaded
     * @return void
     */
    public function boot()
    {
        // For each of the registered modules, include their routes and Views
        $modules = (new Directories(app_path('Modules')))->getModules();

        foreach ($modules as $module) {

            // Load the routes for each of the modules
            if (file_exists($module . '/routes.php')) {
                include $module . '/routes.php';
            }

            // Load the views
            if (is_dir($module . '/Views')) {
                $folder = basename($module);
                $this->loadViewsFrom($module . '/Views', $folder);
            }
        }
    }

    public function register()
    {
    }
}
