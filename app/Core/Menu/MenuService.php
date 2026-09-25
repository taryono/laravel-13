<?php

namespace App\Core\Menu;

use Models\Menu;
use Models\Action;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MenuService
{
    protected Collection $allowedMenus;
    protected Collection $allowedActions;
    protected MenuTreeBuilder $treeBuilder;

    public function __construct(MenuTreeBuilder $treeBuilder)
    {
        $this->treeBuilder = $treeBuilder;
        // ✅ Inisialisasi di konstruktor
        $this->allowedMenus = collect();
        $this->allowedActions = collect();
    }

    public function getSplitMenu(string $currentUrl, $user = null): array
    {
        if (!$user) {
            return ['menu' => collect(), 'topnavMenu' => collect()];
        }

        // ✅ Ambil organisasi aktif
        $orgId = session('active_organization_id')
            ?? $user->active_organization_id;

        // ✅ Super Admin = semua menu
        if ($user->isSuperUser()) {
            // ✅ Pastikan keduanya diatur
            $this->allowedMenus = collect();
            $this->allowedActions = collect();
        } else {
            $this->loadPermissions($user, $orgId);
        }

        // ✅ Ambil menu — is_publish boleh null/1, is_active=1
        $query = Menu::where('is_active', 1)
            ->whereNull('deleted_at');

        // Terapkan filter izin (kecuali Super Admin)
        if (!$user->isSuperUser() && $this->allowedMenus->isNotEmpty()) {
            $query->whereIn('id', $this->allowedMenus);
        }

        $menus = $query->orderBy('sort_order', 'asc')->get();
        $menus = $this->loadAllowedActions($menus);


        $tree = $this->treeBuilder
            ->setCustomParentMappings($this->getCustomParentMappings($user))
            ->setRootParentId($this->getRootParentId($user))
            ->build($menus, $currentUrl);

        // ✅ Filter yang sudah diperbaiki
        $filteredTree = $this->treeBuilder->filterByPermission($tree);

        return [
            'menu' => $filteredTree->where('type', 'sidebar')->values(),
            'topnavMenu' => $filteredTree->where('type', 'topbar')->values(),
            'currentUrl' => $currentUrl
        ];
    }

    protected function loadPermissions($user, $orgId): void
    {
        // ✅ Filter role_user dengan organization_id
        $roleIds = DB::table('role_user')
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId) // KUNCI INI
            ->whereNull('deleted_at')
            ->pluck('role_id');

        if ($roleIds->isEmpty()) {
            $this->allowedMenus = collect();
            $this->allowedActions = collect();
            return;
        }

        // ✅ Ambil menu yang ada izin is_view = 1
        $this->allowedMenus = DB::table('menu_role')
            ->whereIn('role_id', $roleIds)
            ->where('is_view', 1) // Hanya yang boleh dilihat
            ->pluck('menu_id');
        dd($roleIds, $this->allowedMenus);
        $this->allowedActions = DB::table('action_role')
            ->whereIn('role_id', $roleIds)
            ->pluck('action_id');
    }

    protected function loadAllowedActions(Collection $menus): Collection
    {
        return $menus->map(function (Menu $menu) {
            $menu->allowed_actions = $this->allowedActions->isEmpty()
                ? Action::where('menu_id', $menu->id)->get(['id', 'name', 'display_name'])
                : Action::whereIn('id', $this->allowedActions)
                ->where('menu_id', $menu->id)
                ->get(['id', 'name', 'display_name']);
            return $menu;
        });
    }

    protected function getRootParentId($user): ?int
    {
        return null;
    }

    protected function getCustomParentMappings($user): array
    {
        return [];
    }

    public function hasPermission($user, string $actionName): bool
    {
        if (!$user) return false;
        if ($user->isSuperUser()) return true;

        $orgId = session('active_organization_id')
            ?? $user->active_organization_id;

        $action = Action::where('name', $actionName)->first();
        if (!$action) return false;

        // Cek langsung ke user
        $direct = DB::table('action_user')
            ->where('action_id', $action->id)
            ->where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->exists();
        if ($direct) return true;

        // Cek lewat peran di organisasi aktif
        $roleIds = DB::table('role_user')
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->pluck('role_id');

        return DB::table('action_role')
            ->where('action_id', $action->id)
            ->whereIn('role_id', $roleIds)
            ->exists();
    }
}
