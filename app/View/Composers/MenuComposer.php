<?php
// app/View/Composers/MenuComposer.php
namespace App\View\Composers;

use App\Core\Menu\MenuService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view): void
    {
        $menuService = new MenuService();
        
        // Kirim user yang sedang login ke service untuk filter izin
        $split = $menuService->getSplitMenu(
            request()->url(),
            Auth::user()
        ); 
        $view->with('menu', $split['menu'] ?? collect());
        $view->with('topnavMenu', $split['topnavMenu'] ?? collect());
    }
}