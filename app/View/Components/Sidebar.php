<?php
// app/View/Components/Sidebar.php
namespace App\View\Components;

use App\Core\Menu\MenuService;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public $menu;
    public $topnavMenu;

    public function __construct()
    {
        $split = (new MenuService())->getSplitMenu(request()->url());
        $this->menu = $split['menu'];
        $this->topnavMenu = $split['topnavMenu'];
    }

    public function render()
    {
        return view('components.sidebar');
    }
}