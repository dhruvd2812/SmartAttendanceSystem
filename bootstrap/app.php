<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(
    basePath: dirname(__DIR__)
)

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    */

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    */

    ->withMiddleware(function (Middleware $middleware) {

        /*
        |--------------------------------------------------------------------------
        | Trust Render Proxy
        |--------------------------------------------------------------------------
        */

        $middleware->trustProxies(at: '*');

        /*
        |--------------------------------------------------------------------------
        | Role Middleware Alias
        |--------------------------------------------------------------------------
        */

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })

    /*
    |--------------------------------------------------------------------------
    | Exceptions
    |--------------------------------------------------------------------------
    */

    ->withExceptions(function (Exceptions $exceptions) {

        //
    })

    /*
    |--------------------------------------------------------------------------
    | Create Application
    |--------------------------------------------------------------------------
    */

    ->create();