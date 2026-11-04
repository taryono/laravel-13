<?php

namespace App\Core\Menu;

use Models\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MenuRepository
{
    /**
     * Ambil menu yang aktif dan diizinkan untuk user saat ini.
     * Mendukung filter opsional berdasarkan menu_category_id atau session aktif.
     */
    public function all(?int $menuCategoryId = null): Collection
    {
        $menuCategoryId = $menuCategoryId
            ?? session('active_menu_category_id')
            ?? session('selected_menu_category_id')
            ?? request('menu_category_id');

        $query = Menu::query()
            ->where('is_active', 1)
            ->whereNull('deleted_at');

        // Filter berdasarkan menu_category_id terpilih (jika ada)
        if ($menuCategoryId) {
            $categoryMenuIds = $this->getMenuIdsForCategory((int) $menuCategoryId);
            $query->whereIn('id', $categoryMenuIds);
        }

        if (Auth::check()) {
            $user = Auth::user();
            $orgId = session('active_organization_id') ?? $user->active_organization_id;

            if (!$user->isSuperUser()) {
                $roleIds = DB::table('role_user')
                    ->where('user_id', $user->id)
                    ->where('organization_id', $orgId)
                    ->whereNull('deleted_at')
                    ->pluck('role_id');

                $allowedMenuIds = DB::table('menu_role')
                    ->whereIn('role_id', $roleIds)
                    ->where('is_view', 1)
                    ->whereNull('deleted_at')
                    ->pluck('menu_id');

                $query->whereIn('id', $allowedMenuIds);
            }
        }

        return $query
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn(Menu $m) => MenuItem::fromModel($m));
    }

    /**
     * Ambil menu khusus berdasarkan ID kategori menu terpilih.
     */
    public function getByCategory(?int $menuCategoryId): Collection
    {
        return $this->all($menuCategoryId);
    }

    /**
     * Dapatkan semua ID menu (termasuk sub-menu dan seluruh turunannya)
     * yang berada di bawah menu_category_id tertentu.
     */
    public function getMenuIdsForCategory(int $menuCategoryId): array
    {
        // 1. Ambil menu root atau menu langsung yang memiliki menu_category_id ini
        $directMenuIds = Menu::where('menu_category_id', $menuCategoryId)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->pluck('id')
            ->toArray();

        if (empty($directMenuIds)) {
            return [];
        }

        // 2. Ambil seluruh turunan (child, grandchild, dst.) dari menu-menu tersebut
        $descendants = $this->getDescendantMenuIds($directMenuIds);

        return array_values(array_unique(array_merge($directMenuIds, $descendants)));
    }

    /**
     * Rekursif mengambil semua child/descendant IDs dari sekumpulan parent IDs
     */
    protected function getDescendantMenuIds(array $parentIds): array
    {
        if (empty($parentIds)) {
            return [];
        }

        $allDescendants = [];
        $currentParents = $parentIds;

        while (!empty($currentParents)) {
            $children = Menu::whereIn('parent_id', $currentParents)
                ->where('is_active', 1)
                ->whereNull('deleted_at')
                ->pluck('id')
                ->toArray();

            if (empty($children)) {
                break;
            }

            $allDescendants = array_merge($allDescendants, $children);
            $currentParents = $children;
        }

        return $allDescendants;
    }

    /**
     * Ambil SEMUA menu tanpa filter apapun — khusus keperluan admin management.
     * Admin harus bisa lihat & edit menu yang tidak aktif/tidak publish sekalipun.
     */
    public function allForAdmin(): Collection
    {
        return Menu::with('roles')
            ->orderBy('sort_order')
            ->orderBy('sequence')
            ->orderBy('name')
            ->get()
            ->map(fn(Menu $m) => MenuItem::fromModel($m));
    }

    public function findRaw(int $id): Menu
    {
        return Menu::findOrFail($id);
    }
}
