<?php
// app/Core/Menu/MenuService.php
namespace App\Core\Menu;

use Models\Menu;
use Models\Role;
use Models\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    protected Collection $allowedMenus;
    protected Collection $allowedActions;

    public function getSplitMenu(string $currentUrl, $user = null): array
    {
        // Jika user belum login, kembalikan menu kosong atau menu tamu
        if (!$user) {
            return [
                'menu' => collect(),
                'topnavMenu' => collect()
            ];
        }

        // Ambil izin yang dimiliki user dari tabel baru
        $this->loadPermissions($user);

        // Ambil semua menu yang diizinkan + susun menjadi pohon
        $allMenus = $this->buildMenuTree(
            Menu::where('is_active', 1)
                ->whereIn('id', $this->allowedMenus)
                ->orderBy('sort_order', 'asc')
                ->get()
        );

        // Pisahkan menu berdasarkan jenisnya
        return [
            'menu' => $allMenus->where('type', 'sidebar')->values(),
            'topnavMenu' => $allMenus->where('type', 'topbar')->values(),
            'currentUrl' => $currentUrl
        ];
    }

    /**
     * Memuat daftar menu & aksi yang diizinkan untuk user
     */
    // Di dalam loadPermissions()
    // app/Core/Menu/MenuService.php
    protected function loadPermissions($user): void
    {
        // AMBIL LANGSUNG DARI TABEL PIVOT, JANGAN LEWAT RELASI
        $roleIds = \DB::table('role_user')
            ->where('user_id', $user->id)
            ->pluck('role_id');

        // Ambil menu yang diizinkan
        $this->allowedMenus = \DB::table('menu_role')
            ->whereIn('role_id', $roleIds)
            ->pluck('menu_id');

        // Ambil aksi yang diizinkan
        $this->allowedActions = \DB::table('action_role')
            ->whereIn('role_id', $roleIds)
            ->pluck('action_id');
    }

    /**
     * Menyusun menu menjadi struktur bertingkat (parent-child)
     */
    protected function buildMenuTree(Collection $menus, int $parentId = null): Collection
    {
        return $menus
            ->where('parent_id', $parentId)
            ->map(function (Menu $menu) use ($menus) {
                // Tambahkan izin aksi ke dalam data menu jika dibutuhkan di tampilan
                $menu->allowed_actions = Action::whereIn('id', $this->allowedActions)
                    ->where('menu_id', $menu->id)
                    ->get(['name', 'display_name']);

                $menu->children = $this->buildMenuTree($menus, $menu->id);
                return $menu;
            });
    }

    /**
     * Cek apakah user punya izin tertentu (bisa dipanggil dari Controller/Blade)
     */
    public function hasPermission($user, string $actionName): bool
    {
        if (!$user) return false;
        
        $roleIds = $user->roles->pluck('id');
        
        return \DB::table('actions')
            ->where('name', $actionName)
            ->whereIn('id', function ($q) use ($roleIds) {
                $q->select('action_id')->from('action_role')->whereIn('role_id', $roleIds);
            })
            ->exists();
    }
}