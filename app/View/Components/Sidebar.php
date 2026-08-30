<?php
// app/View/Components/Sidebar.php
namespace App\View\Components;

use App\Core\Menu\MenuService;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public $menu;
    public $topnavMenu;
    protected MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
        
        $split = $this->menuService->getSplitMenu(request()->url(), auth()->user());
        $this->menu = $split['menu'];
        $this->topnavMenu = $split['topnavMenu'];
    }

    public function render()
    {
        return view('components.sidebar');
    }
}