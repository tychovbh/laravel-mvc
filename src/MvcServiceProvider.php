<?php

namespace Tychovbh\Mvc;

use Illuminate\Support\ServiceProvider;
use Tychovbh\Mvc\Console\MvcRepository;

class MvcServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            MvcRepository::class,
        ]);
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
