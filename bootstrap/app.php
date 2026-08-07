<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        using: function () {
            // Route::middleware('api')
            //     ->prefix('api')
            //     ->namespace('App\Http\Controllers\Api')
            //     ->group(base_path('routes/api.php'));

            Route::middleware('api')
                ->prefix('api')
                ->namespace('App\Http\Controllers\API')
                ->group(base_path('routes/api.php'));    //for local remove it when done 

            Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/web.php'));
        },
        // health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
