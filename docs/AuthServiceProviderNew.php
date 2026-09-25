<?php

namespace App\Providers;

use Models\Action;
use Models\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AuthServiceProviderNew extends ServiceProvider
{
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    public function register(): void {}

    public function boot(): void
    {
        // Super Admin = semua izin
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'isSuperUser') && $user->isSuperUser()) {
                return true;
            }
        });

        Gate::define('manage-menu', function ($user) {
            return $user->isSuperUser() || $user->can('menu-manage');
        });

        try {
            if (!Schema::hasTable('actions') || !Schema::hasTable('menus')) {
                return;
            }

            // =============================================
            // Gate untuk AKSI KHUSUS
            // =============================================
            $allActions = Cache::remember('acl-all-actions', 3600, function () {
                return Action::where('is_active', 1)->pluck('name', 'id');
            });

            foreach ($allActions as $actionId => $actionName) {
                Gate::define($actionName, function ($user) use ($actionId) {
                    $orgId = session('active_organization_id')
                        ?? $user->active_organization_id;

                    // Izin langsung ke user
                    if (DB::table('action_user')
                        ->where('action_id', $actionId)
                        ->where('user_id', $user->id)
                        ->whereNull('deleted_at')
                        ->exists()
                    ) {
                        return true;
                    }

                    // Lewat peran
                    $roleIds = DB::table('role_user')
                        ->where('user_id', $user->id)
                        ->where('organization_id', $orgId)
                        ->whereNull('deleted_at')
                        ->pluck('role_id');

                    return DB::table('action_role')
                        ->where('action_id', $actionId)
                        ->whereIn('role_id', $roleIds)
                        ->whereNull('deleted_at')
                        ->exists();
                });
            }

            // =============================================
            // Gate untuk MENU
            // =============================================
            $allMenus = Cache::remember('acl-all-menus', 3600, function () {
                return Menu::where('is_active', 1)
                    ->whereNull('deleted_at')
                    ->pluck('slug', 'id');
            });

            foreach ($allMenus as $menuId => $menuSlug) {
                // Menu induk (tidak punya route) — cek apakah ada anak yang bisa diakses
                Gate::define("menu-{$menuSlug}.view", function ($user) use ($menuId) {
                    return $this->checkMenuViewPermission($user, $menuId);
                });

                // CRUD hanya untuk menu yang punya rute
                Gate::define("menu-{$menuSlug}.create", function ($user) use ($menuId) {
                    return $this->checkMenuActionPermission($user, $menuId, 'is_create');
                });
                Gate::define("menu-{$menuSlug}.edit", function ($user) use ($menuId) {
                    return $this->checkMenuActionPermission($user, $menuId, 'is_edit');
                });
                Gate::define("menu-{$menuSlug}.delete", function ($user) use ($menuId) {
                    return $this->checkMenuActionPermission($user, $menuId, 'is_delete');
                });
            }
        } catch (\Exception $e) {
            logger()->error('Error memuat ACL: ' . $e->getMessage());
        }
    }

    // =====================================================
    // Cek Izin Tampilkan Menu
    // =====================================================
    private function checkMenuViewPermission($user, $menuId): bool
    {
        $orgId = session('active_organization_id') ?? $user->active_organization_id;

        // Ambil data menu
        $menu = \Models\Menu::find($menuId);
        if (!$menu) return false;

        // ✅ Menu PUNYA ROUTE → cek izin langsung
        if (!empty($menu->route)) {
            return $this->checkMenuActionPermission($user, $menuId, 'is_view');
        }

        // ✅ Menu TANPA ROUTE (induk/kategori) → cek apakah ADA ANAK yang bisa diakses
        $childMenuIds = \Models\Menu::where('parent_id', $menuId)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->pluck('id');

        if ($childMenuIds->isEmpty()) {
            return true; // Tidak punya anak → tampilkan saja
        }

        // Cek apakah salah satu anak punya izin tampil
        foreach ($childMenuIds as $childId) {
            if ($this->checkMenuViewPermission($user, $childId)) {
                return true;
            }
        }

        return false;
    }

    // =====================================================
    // Cek Izin Aksi Spesifik (CRUD)
    // =====================================================
    private function checkMenuActionPermission($user, $menuId, $actionColumn): bool
    {
        $orgId = session('active_organization_id') ?? $user->active_organization_id;

        $roleIds = DB::table('role_user')
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->whereNull('deleted_at')
            ->pluck('role_id');

        if ($roleIds->isEmpty()) return false;

        return DB::table('menu_role')
            ->where('menu_id', $menuId)
            ->whereIn('role_id', $roleIds)
            ->where($actionColumn, 1)
            ->whereNull('deleted_at')
            ->exists();
    }
}
