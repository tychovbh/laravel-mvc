<?php

namespace Tychovbh\Mvc;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Mollie\Laravel\Facades\Mollie;
use Mollie\Laravel\MollieServiceProvider;
use Tychovbh\Mvc\Console\Commands\MvcCollection;
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
use Tychovbh\Mvc\Services\Shop\ShopInterface;
use Tychovbh\Mvc\Services\Voucher\VoucherInterface;
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
                'auth' => AuthenticateMiddleware::class,
                'authorize' => AuthorizeMiddleware::class,
                'validate' => ValidateMiddleware::class,
                'cache' => CacheMiddleware::class
            ]);
            $this->app->register(FormRequestServiceProvider::class);

            $this->app->withFacades(true, [Mollie::class => 'Mollie']);
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
            MvcCollection::class,
            MvcCollections::class,
            MvcUserCreate::class,
            MvcUserToken::class,
            MvcPaymentsCheck::class,
            MvcContractsUpdate::class
        ]);

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
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
        if (config('mvc-document-sign.default')) {
            $this->app->bind(DocumentSignInterface::class, function ($app) {
                $client = $app->make(Client::class);
                $service = config('mvc-document-sign.default');
                $service = 'Tychovbh\\Mvc\\Services\\DocumentSign\\' . $service;

                return new $service($client);
            });
        }

        if (config('mvc-html-converter.default')) {
            $this->app->bind(HtmlConverterInterface::class, function () {
                $service = config('mvc-html-converter.default');
                $service = 'Tychovbh\\Mvc\\Services\\HtmlConverter\\' . $service;

                return new $service();
            });

        }

        if (config('mvc-html-converter.default') === 'LaravelDompdfConverter') {
            $this->mergeConfigFrom(__DIR__ . '/../config/dompdf.php', 'dompdf');
            $this->app->register(\Barryvdh\DomPDF\ServiceProvider::class);
        }

        if (config('mvc-address-lookup.default')) {
            $this->app->bind(AddressLookupInterface::class, function ($app) {
                $client = $app->make(Client::class);
                $service = config('mvc-address-lookup.default');
                $service = 'Tychovbh\\Mvc\\Services\\AddressLookup\\' . $service;

                return new $service($client);
            });
        }

        if (config('mvc-shop.default')) {
            $this->app->bind(ShopInterface::class, function ($app) {
                $client = $app->make(Client::class);
                $service = config('mvc-shop.default');
                $service = 'Tychovbh\\Mvc\\Services\\Shop\\' . $service;

                return new $service($client);
            });
        }

        if (config('mvc-voucher.default')) {
            $this->app->bind(VoucherInterface::class, function ($app) {
                $client = $app->make(Client::class);
                $service = config('mvc-voucher.default');
                $service = 'Tychovbh\\Mvc\\Services\\Voucher\\' . $service;

                return new $service($client);
            });
        }
    }

    /**
     * Publish config file
     * @param string $name
     */
    private function config(string $name)
    {
        $source = __DIR__ . '/../config/' . $name . '.php';
        $this->publishes([$source => config_path($name . '.php')], 'laravel-mvc-config-' . $name);
    }

    /**
     * @throws \Exception
     */
    private function observers()
    {
        $this->observe(Payment::class, PaymentObserver::class);
        if (config('mvc-address-lookup.default')) {
            $this->observe(Address::class, AddressObserver::class);
        }

        if (config('mvc-document-sign.default') && config('mvc-html-converter.default')) {
            $this->observe(Contract::class, ContractObserver::class);
        }
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
