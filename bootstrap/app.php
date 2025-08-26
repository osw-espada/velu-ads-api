<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/health',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->append([
                \App\Laravel\Middlewares\TrustProxies::class,
                \App\Laravel\Middlewares\PreventRequestsDuringMaintenance::class,
                \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
                \App\Laravel\Middlewares\TrimStrings::class,
                \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
                \App\Laravel\Middlewares\TransformInput::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            ]
        );

        $middleware->group('web', [
            \App\Laravel\Middlewares\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->group('api', [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        \App\Exceptions\Handler::registerInExceptions($exceptions);

    })->create();
