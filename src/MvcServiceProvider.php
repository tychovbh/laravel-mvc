<?php

namespace Tychovbh\Mvc;

use Illuminate\Support\ServiceProvider;
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
use Tychovbh\Mvc\Http\Middleware\ValidateMiddleware;
use Tychovbh\Mvc\Http\Middleware\ValidateRequest;
use Tychovbh\Mvc\Models\Address;
use Tychovbh\Mvc\Models\Contract;
use Tychovbh\Mvc\Observers\AddressObserver;
use Tychovbh\Mvc\Observers\ContractObserver;
use Tychovbh\Mvc\Services\AddressLookup\AddressLookupInterface;
use Tychovbh\Mvc\Services\DocumentSign\DocumentSignInterface;
use Tychovbh\Mvc\Services\HtmlConverter\HtmlConverterInterface;
use Tychovbh\Mvc\Services\Shop\ShopInterface;
use Tychovbh\Mvc\Services\Voucher\VoucherInterface;

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

        if (config('mvc-payments.enabled')) {
            $this->app->register(\Mollie\Laravel\MollieServiceProvider::class);
        }

        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('validate', ValidateMiddleware::class);
        $router->pushMiddlewareToGroup('validate.request', ValidateRequest::class);


        if (!$this->app->runningInConsole()) {
            return;
        }

        $commands = [
            MvcRepository::class,
            MvcController::class,
            MvcRequest::class,
            MvcUpdate::class,
            MvcCollection::class,
            MvcCollections::class,
            MvcUserCreate::class,
            MvcUserToken::class,
            MvcContractsUpdate::class
        ];

        if (config('mvc-payments.enabled')) {
            $commands[] = MvcPaymentsCheck::class;
        }

        $this->commands($commands);

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'laravel-mvc-migrations');


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
                $client = $app->make(\GuzzleHttp\Client::class);
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
                $client = $app->make(\GuzzleHttp\Client::class);
                $service = config('mvc-address-lookup.default');
                $service = 'Tychovbh\\Mvc\\Services\\AddressLookup\\' . $service;

                return new $service($client);
            });
        }

        if (config('mvc-shop.default')) {
            $this->app->bind(ShopInterface::class, function ($app) {
                $client = $app->make(\GuzzleHttp\Client::class);
                $service = config('mvc-shop.default');
                $service = 'Tychovbh\\Mvc\\Services\\Shop\\' . $service;

                return new $service($client);
            });
        }

        if (config('mvc-voucher.default')) {
            $this->app->bind(VoucherInterface::class, function ($app) {
                $client = $app->make(\GuzzleHttp\Client::class);
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
        if (config('mvc-payments.enabled')) {
            $this->observe(\Tychovbh\Mvc\Models\Payment::class, \Tychovbh\Mvc\Observers\PaymentObserver::class);
        }
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
