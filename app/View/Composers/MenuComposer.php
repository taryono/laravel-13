<?php

namespace App\View\Composers;

use App\Core\Menu\MenuService;
use App\Core\Menu\TrialMenuResolver;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MenuComposer
{
    protected MenuService $menuService;
    protected TrialMenuResolver $trialResolver;

    public function __construct(MenuService $menuService, ?TrialMenuResolver $trialResolver = null)
    {
        $this->menuService = $menuService;
        $this->trialResolver = $trialResolver ?? new TrialMenuResolver();
    }

    public function compose(View $view): void
    {
        $user = Auth::user();

        $split = $this->menuService->getSplitMenu(
            request()->url(),
            $user
        );

        $activeCategoryId = session('active_menu_category_id');
        $activeCategory = null;
        if ($activeCategoryId && class_exists(\Models\MenuCategory::class)) {
            $activeCategory = \Models\MenuCategory::find($activeCategoryId);
        }

        $isTrialUser = $this->trialResolver->isTrialUser($user);

        $view->with('menu', $split['menu'] ?? collect());
        $view->with('topnavMenu', $split['topnavMenu'] ?? collect());
        $view->with('activeMenuCategory', $activeCategory);
        $view->with('isTrialUser', $isTrialUser);
    }
}
