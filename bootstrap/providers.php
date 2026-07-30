<?php
// bootstrap/providers.php
use App\Providers\AppServiceProvider; 
use App\Providers\ViewServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\AuthServiceProvider;
return [
    AppServiceProvider::class,
    ViewServiceProvider::class,
    EventServiceProvider::class,
    AuthServiceProvider::class
];
