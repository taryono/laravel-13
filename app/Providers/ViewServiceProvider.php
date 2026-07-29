<?php
// app/Providers/ViewServiceProvider.php
namespace App\Providers;

use App\View\Composers\MenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('components.sidebar', MenuComposer::class); 
    }
}