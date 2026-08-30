<?php
// app/Core/Menu/MenuService.php
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
    }

    public function getSplitMenu(string $currentUrl, $user = null): array
    {
        // Jika user belum login, kembalikan menu kosong atau menu tamu
        if (!$user) {
            return [
                'menu' => collect(),
                'topnavMenu' => collect()
            ];
        }

        // Ambil izin yang dimiliki user
        $this->loadPermissions($user);

        // Ambil menu yang diizinkan
        $menus = Menu::where('is_active', 1)
            ->whereIn('id', $this->allowedMenus)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Load allowed actions untuk setiap menu
        $menus = $this->loadAllowedActions($menus);

        // Build tree menggunakan MenuTreeBuilder
        $tree = $this->treeBuilder
            ->setCustomParentMappings($this->getCustomParentMappings($user))
            ->setRootParentId($this->getRootParentId($user))
            ->build($menus, $currentUrl);

        // Filter tree (hapus grup kosong)
        $filteredTree = $this->treeBuilder->filterByPermission($tree);

        // Pisahkan menu berdasarkan jenisnya
        return [
            'menu' => $filteredTree->where('type', 'sidebar')->values(),
            'topnavMenu' => $filteredTree->where('type', 'topbar')->values(),
            'currentUrl' => $currentUrl
        ];
    }

    /**
     * Load allowed actions untuk setiap menu
     */
    protected function loadAllowedActions(Collection $menus): Collection
    {
        return $menus->map(function (Menu $menu) {
            $menu->allowed_actions = Action::whereIn('id', $this->allowedActions)
                ->where('menu_id', $menu->id)
                ->get(['id', 'name', 'display_name']);
            return $menu;
        });
    }

    /**
     * Tentukan root parent ID berdasarkan role user
     */
    protected function getRootParentId($user): ?int
    {
        // Contoh logika
        if ($user->hasRole('manager')) {
            return 51; // Academic Management
        }
        
        return null; // Default: semua menu
    }

    /**
     * Tentukan custom parent mappings berdasarkan role user
     */
    protected function getCustomParentMappings($user): array
    {
        $mappings = [];
        
        // Contoh: role khusus, Schedule Management jadi root
        if ($user->hasRole('special_role')) {
            $mappings[53] = null; // Schedule Management (id: 53) menjadi root
        }
        
        return $mappings;
    }

    /**
     * Memuat daftar menu & aksi yang diizinkan untuk user
     */
    protected function loadPermissions($user): void
    {
        $roleIds = DB::table('role_user')
            ->where('user_id', $user->id)
            ->pluck('role_id');

        // Ambil menu yang diizinkan
        $this->allowedMenus = DB::table('menu_role')
            ->whereIn('role_id', $roleIds)
            ->pluck('menu_id');

        // Ambil aksi yang diizinkan
        $this->allowedActions = DB::table('action_role')
            ->whereIn('role_id', $roleIds)
            ->pluck('action_id');
    }

    /**
     * Cek apakah user punya izin tertentu
     */
    public function hasPermission($user, string $actionName): bool
    {
        if (!$user) return false;
        
        $roleIds = $user->roles->pluck('id');
        
        return DB::table('actions')
            ->where('name', $actionName)
            ->whereIn('id', function ($q) use ($roleIds) {
                $q->select('action_id')->from('action_role')->whereIn('role_id', $roleIds);
            })
            ->exists();
    }
}