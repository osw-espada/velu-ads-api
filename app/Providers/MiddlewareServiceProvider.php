<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('api.auth', \App\Laravel\Middlewares\Api\Authenticate::class);
        $router->aliasMiddleware('api.exist', \App\Laravel\Middlewares\Api\ExistRecord::class);

        $router->aliasMiddleware('web.auth', \App\Laravel\Middlewares\Web\Authenticate::class);
        $router->aliasMiddleware('web.guest', \App\Laravel\Middlewares\Web\RedirectIfAuthenticated::class);
        $router->aliasMiddleware('throttle', \Illuminate\Routing\Middleware\ThrottleRequests::class);
    }
}
