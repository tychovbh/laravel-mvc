<?php

namespace Tychovbh\Mvc;

use Illuminate\Support\ServiceProvider;
use Tychovbh\Mvc\Console\Commands\MvcRepository;
use Tychovbh\Mvc\Console\Commands\MvcController;
use Tychovbh\Mvc\Console\Commands\MvcRequest;
use Tychovbh\Mvc\Http\Middleware\ValidateMiddleware;

class MvcServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MvcRepository::class,
                MvcController::class,
                MvcRequest::class
            ]);
        }

        $source = __DIR__ . '/../config/messages.php';
        if (is_application() === 'laravel') {
            $this->publishes([$source => config_path('messages.php')], 'laravel-mvc');
        }

        if (is_application() === 'lumen') {
            $this->app->configure('messages');
            $this->app->register(\Urameshibr\Providers\FormRequestServiceProvider::class);
            $this->app->routeMiddleware([
                'validate' => ValidateMiddleware::class
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
