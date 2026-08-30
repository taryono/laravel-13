<?php
// app/View/Composers/MenuComposer.php
namespace App\View\Composers;

use App\Core\Menu\MenuService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MenuComposer
{
    protected MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function compose(View $view): void
    {
        $split = $this->menuService->getSplitMenu(
            request()->url(),
            Auth::user()
        );
        
        $view->with('menu', $split['menu'] ?? collect());
        $view->with('topnavMenu', $split['topnavMenu'] ?? collect());
    }
}