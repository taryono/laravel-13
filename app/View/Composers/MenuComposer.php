<?php
// app/View/Composers/MenuComposer.php
namespace App\View\Composers;

use App\Core\Menu\MenuService;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view): void
    {
        $split = (new MenuService())->getSplitMenu(request()->url());

        $view->with('menu', $split['menu']);
        $view->with('topnavMenu', $split['topnavMenu']);
    }
}