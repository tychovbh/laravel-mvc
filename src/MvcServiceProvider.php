<?php

namespace Tychovbh\Mvc;

use Illuminate\Support\ServiceProvider;
use Laravelista\LumenVendorPublish\VendorPublishCommand;
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
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            MvcRepository::class,
            MvcController::class,
            MvcRequest::class,
        ]);

        $source = __DIR__ . '/../config/messages.php';
        $this->publishes([$source => config_path('messages.php')], 'laravel-mvc-config');
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'laravel-mvc-migrations');

        $this->loadRoutesFrom(sprintf('%s/../routes/%s/web.php', __DIR__, is_application()));

        if (is_application() === 'lumen') {
            $this->app->configure('messages');
            $this->app->register(\Urameshibr\Providers\FormRequestServiceProvider::class);
            $this->app->routeMiddleware([
                'validate' => ValidateMiddleware::class
            ]);
            $this->commands([
                VendorPublishCommand::class
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
