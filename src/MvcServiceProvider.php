<?php

namespace Tychovbh\Mvc;

use Chelout\OffsetPagination\OffsetPaginationServiceProvider;
use Illuminate\Support\ServiceProvider;
use Tychovbh\Mvc\Console\Commands\MvcCollections;
use Tychovbh\Mvc\Console\Commands\MvcRepository;
use Tychovbh\Mvc\Console\Commands\MvcController;
use Tychovbh\Mvc\Console\Commands\MvcRequest;
use Tychovbh\Mvc\Console\Commands\MvcUpdate;
use Tychovbh\Mvc\Console\Commands\VendorPublishCommand;
use Tychovbh\Mvc\Http\Middleware\AuthenticateMiddleware;
use Tychovbh\Mvc\Http\Middleware\AuthorizeMiddleware;
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
        if (is_application() === 'lumen') {
            $this->app->routeMiddleware([
                'validate' => ValidateMiddleware::class,
                'auth' => AuthenticateMiddleware::class,
                'authorize' => AuthorizeMiddleware::class
            ]);
            $this->app->register(\Urameshibr\Providers\FormRequestServiceProvider::class);
            $this->app->configure('mvc-messages');
            $this->app->configure('mvc-forms');
            $this->app->configure('mvc-collections');
            $this->app->configure('mvc-auth');
            $this->app->configure('mvc-mail');
        } else {
            $router = $this->app['router'];
            $router->pushMiddlewareToGroup('validate', ValidateMiddleware::class);
        }

        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            MvcRepository::class,
            MvcController::class,
            MvcRequest::class,
            MvcUpdate::class,
            MvcCollections::class
        ]);

        $this->config('mvc-messages');
        $this->config('mvc-forms');
        $this->config('mvc-collections');
        $this->config('mvc-auth');
        $this->config('mvc-mail');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'laravel-mvc-migrations');

        $this->loadRoutesFrom(sprintf('%s/../routes/%s/web.php', __DIR__, is_application()));

        if (is_application() === 'lumen') {
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
        $this->app->register(
            OffsetPaginationServiceProvider::class
        );
    }

    /**
     * Publish config file
     * @param string $name
     */
    private function config(string $name)
    {
        $source = __DIR__ . '/../config/'. $name .'.php';
        $this->publishes([$source => config_path($name . '.php')], 'laravel-mvc-config-' . $name);
    }
}
