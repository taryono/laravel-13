<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
        $middleware->alias([
            'superuser' => \App\Http\Middleware\EnsureSuperUser::class,
            'access'    => \App\Http\Middleware\AccessCheck::class,
            'acl'       => \App\Http\Middleware\AclMiddleware::class,
            'proctor' => \App\Http\Middleware\Proctor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //  Integration::handles($exceptions);
    })->create();
