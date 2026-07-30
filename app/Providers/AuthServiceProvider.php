<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class, 
    ];
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
   public function boot()
    { 
        Gate::before(function ($user, $ability) {
            if ($user->isSuperUser()) {
                return true;
            }
        });

        Gate::define('manage-menu', function ($user) {
            return isSuperUser(); // atau cek permission module tertentu
        });

        try {
            if (Schema::hasTable('action_roles')) {
                // Mendapatkan semua menu yang aktif
                // dan mendefinisikan izin untuk setiap menu
                // Menggunakan cache untuk menghindari query berulang 
                $roleIdsCache = []; // Cache untuk role_ids berdasarkan user ID
                $modules = \Models\Module::select(['can', 'name'])->where('is_active', 1)->whereNull('is_private')->get();
               
                foreach ($modules as $id => $module) {
                    $can = $module->can;
                    Gate::define($can, function ($user) use ($module, &$roleIdsCache) {
                        // Ambil dari cache jika sudah ada
                        if (! isset($roleIdsCache[$user->id])) {
                            $roleIdsCache[$user->id] = $user->roles()->pluck('role_id')->toArray();
                        }

                        $role_ids = $roleIdsCache[$user->id];
                        $roleNames = \Models\Role::whereIn('id', $role_ids)->pluck('name', 'id')->all();
                        return null !== \Models\Menu::whereIn('role', $roleNames)
                            ->where('name', $module->name)
                            ->where('is_active', 1)
                            ->where('is_publish', 1)
                            ->where('is_private', 0)
                            ->count();
                    });
                }
                // Mendapatkan semua actions yang aktif
                // dan mendefinisikan izin untuk setiap action
                // Menggunakan cache untuk menghindari query berulang 
                $actions      = \Models\Action::where('is_active', 1)->pluck("action", "id")->all();
                $roleIdsCache = []; // Cache untuk role_ids berdasarkan user ID
                foreach ($actions as $id => $action) {
                    Gate::define($action, function ($user) use ($id, &$roleIdsCache) {
                        // Ambil dari cache jika sudah ada
                        if (! isset($roleIdsCache[$user->id])) {
                            $roleIdsCache[$user->id] = $user->roles()->pluck('role_id')->toArray();
                        }

                        $role_ids = $roleIdsCache[$user->id];
                        return \Models\ActionRole::where('action_id', $id)
                            ->whereIn('role_id', $role_ids)
                            ->where('is_allowed', 1)
                            ->exists();
                    });
                }

                $menus = \Models\Menu::select(['can','role'])->where('url', "#")
                ->where('is_active', 1)
                ->where('is_private',"<>", 1)
                ->where('is_publish', 1)
                ->groupBy('can', 'role')
                ->get();
                 
                foreach ($menus as $menu) {
                    Gate::define($menu->can, function ($user) use ($menu) {
                        if($user->isSuperUser()) return true;
                        return $user->hasRole($menu->role); 
                    });
                }
            }
        } catch (\Exception $e) {
            dump($e);
        }
    } 
}
