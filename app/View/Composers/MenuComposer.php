<?php

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

        $activeCategoryId = session('active_menu_category_id');
        $activeCategory = null;
        if ($activeCategoryId && class_exists(\Models\MenuCategory::class)) {
            $activeCategory = \Models\MenuCategory::find($activeCategoryId);
        }

        $view->with('menu', $split['menu'] ?? collect());
        $view->with('topnavMenu', $split['topnavMenu'] ?? collect());
        $view->with('activeMenuCategory', $activeCategory);
    }
}
