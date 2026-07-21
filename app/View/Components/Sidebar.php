<?php
// app/View/Components/Sidebar.php
namespace App\View\Components;

use App\Core\Menu\MenuRepository;
use App\Core\Menu\MenuTreeBuilder;
use App\Core\Menu\MenuPermissionResolver;
use Illuminate\View\Component;

// app/View/Components/Sidebar.php
class Sidebar extends Component
{
    public $menu;
    public $topnavMenu;

    public function __construct()
    {
        $items = (new MenuRepository())->all();
        $tree = (new MenuTreeBuilder())->build($items, request()->url());

        $isSuper = auth()->check() && isSuperUser();
        $roleIds = session()->get('role_ids', []);
        $allowedModuleIds = $isSuper ? [] : (new MenuPermissionResolver())->allowedModuleIds($roleIds);

        $filteredTree = (new MenuTreeBuilder())->filterByPermission($tree, $allowedModuleIds, $isSuper);

        // Gunakan nama variabel berbeda agar tidak tertukar
        $this->menu = $filteredTree->reject(fn ($i) => $i->isTopnav)->values();
        $this->topnavMenu = $filteredTree->filter(fn ($i) => $i->isTopnav)->values();
    }

    // Tambahkan method ini untuk jaminan variabel tersedia
    public function with(): array
    {
        return [
            'menu' => $this->menu,
            'topnavMenu' => $this->topnavMenu,
        ];
    }

    public function render()
    {
        return view('components.sidebar');
    }
}