<?php

namespace App\Providers;

use App\Laravel\Services\CustomValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        if(env('SECURE_ASSET',FALSE) == TRUE){
            $this->app['request']->server->set('HTTPS','on');
        }

        Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomValidator($translator, $data, $rules, $messages);
        });
        $this->loadViewsFrom(app_path('Laravel/Views'), 'custom');

        app('view')->getFinder()->setPaths([app_path('Laravel/Views')]);
    }
}
