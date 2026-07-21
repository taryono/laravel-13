<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;          // ✅ WAJIB DITAMBAH
use App\Listeners\SetRoleSessionOnLogin;  // ✅ WAJIB DITAMBAH
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            SetRoleSessionOnLogin::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}