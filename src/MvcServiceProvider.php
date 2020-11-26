<?php

namespace Tychovbh\Mvc;

use Chelout\OffsetPagination\OffsetPaginationServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Mollie\Laravel\Facades\Mollie;
use Mollie\Laravel\MollieServiceProvider;
use Tychovbh\Mvc\Console\Commands\MvcCollections;
use Tychovbh\Mvc\Console\Commands\MvcPaymentsCheck;
use Tychovbh\Mvc\Console\Commands\MvcRepository;
use Tychovbh\Mvc\Console\Commands\MvcController;
use Tychovbh\Mvc\Console\Commands\MvcRequest;
use Tychovbh\Mvc\Console\Commands\MvcUpdate;
use Tychovbh\Mvc\Console\Commands\MvcContractsUpdate;
use Tychovbh\Mvc\Console\Commands\MvcUserCreate;
use Tychovbh\Mvc\Console\Commands\MvcUserToken;
use Tychovbh\Mvc\Console\Commands\VendorPublish;
use Tychovbh\Mvc\Http\Middleware\AuthenticateMiddleware;
use Tychovbh\Mvc\Http\Middleware\AuthorizeMiddleware;
use Tychovbh\Mvc\Http\Middleware\ValidateMiddleware;
use Tychovbh\Mvc\Http\Middleware\CacheMiddleware;
use Tychovbh\Mvc\Observers\AddressObserver;
use Tychovbh\Mvc\Observers\ContractObserver;
use Tychovbh\Mvc\Observers\PaymentObserver;
use Tychovbh\Mvc\Services\AddressLookup\AddressLookupInterface;
use Tychovbh\Mvc\Services\DocumentSign\DocumentSignInterface;
use Tychovbh\Mvc\Services\HtmlConverter\HtmlConverterInterface;
use Urameshibr\Providers\FormRequestServiceProvider;

class MvcServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->observers();

        $this->app->register(MollieServiceProvider::class);
        if (is_application() === 'lumen') {
            $this->app->routeMiddleware([
                'validate' => ValidateMiddleware::class,
                'auth' => AuthenticateMiddleware::class,
                'authorize' => AuthorizeMiddleware::class,
                'cache' => CacheMiddleware::class
            ]);
            $this->app->register(FormRequestServiceProvider::class);

            $this->app->withFacades(true, [Mollie::class => 'Mollie']);
            $this->app->configure('mvc-messages');
            $this->app->configure('mvc-forms');
            $this->app->configure('mvc-collections');
            $this->app->configure('mvc-auth');
            $this->app->configure('mvc-mail');
            $this->app->configure('mvc-cache');
            $this->app->configure('mvc-security');
            $this->app->configure('mvc-payments');
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
            MvcCollections::class,
            MvcUserCreate::class,
            MvcUserToken::class,
            MvcPaymentsCheck::class,
            MvcContractsUpdate::class
        ]);

        $this->config('mvc-messages');
        $this->config('mvc-forms');
        $this->config('mvc-collections');
        $this->config('mvc-auth');
        $this->config('mvc-mail');
        $this->config('mvc-cache');
        $this->config('mvc-security');
        $this->config('mvc-payments');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'laravel-mvc-migrations');

        $this->loadRoutesFrom(sprintf('%s/../routes/%s/web.php', __DIR__, is_application()));

        if (is_application() === 'lumen') {
            $this->commands([
                VendorPublish::class
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

        $this->app->bind(DocumentSignInterface::class, function ($app) {
            $client = $app->make(Client::class);
            $service = config('mvc-document-sign.default');
            $service = 'Tychovbh\\Mvc\\Services\\DocumentSign\\' . $service;

            return new $service($client);
        });

        $this->app->bind(HtmlConverterInterface::class, function () {
            $service = config('mvc-html-converter.default');
            $service = 'Tychovbh\\Mvc\\Services\\HtmlConverter\\' . $service;

            return new $service();
        });

        $this->app->bind(AddressLookupInterface::class, function ($app) {
            $client = $app->make(Client::class);
            $service = config('mvc-address-lookup.default');
            $service = 'Tychovbh\\Mvc\\Services\\AddressLookup\\' . $service;

            return new $service($client);
        });
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

    /**
     * @throws \Exception
     */
    private function observers()
    {
        $this->observe(Payment::class, PaymentObserver::class);
        $this->observe(Address::class, AddressObserver::class);
        $this->observe(Contract::class, ContractObserver::class);
    }

    /**
     * @param string $class
     * @param string $observer
     * @throws \Exception
     */
    private function observe(string $class, string $observer)
    {
        $class = project_or_package_class('Model', $class);
        $class::observe($observer);
    }
}
