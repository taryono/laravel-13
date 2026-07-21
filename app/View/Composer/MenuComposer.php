<?php
// app/View/Composers/MenuComposer.php
namespace App\View\Composers;

use App\Core\Menu\MenuRepository;
use App\Core\Menu\MenuTreeBuilder;
use App\Core\Menu\MenuPermissionResolver;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view): void
    {
        $items = (new MenuRepository())->all();

        $tree = (new MenuTreeBuilder())->build(
            $items,
            currentUrl: request()->url()
        );

        $isSuper = auth()->check() && isSuperUser();
        $roleIds = session()->get('role_ids', []);

        $allowedModuleIds = $isSuper
            ? []
            : (new MenuPermissionResolver())->allowedModuleIds($roleIds);

        $menu = (new MenuTreeBuilder())->filterByPermission($tree, $allowedModuleIds, $isSuper);

        // Kalau sidebar dan topnav butuh dipisah (ingat flag isTopnav dari Tree.php lama)
        $view->with('menu', $menu->reject(fn ($item) => $item->isTopnav)->values());
        $view->with('topnavMenu', $menu->filter(fn ($item) => $item->isTopnav)->values());
    }
}