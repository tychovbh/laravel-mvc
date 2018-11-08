<?php

namespace Tychovbh\Mvc;

use Illuminate\Support\ServiceProvider;
use Tychovbh\Mvc\Console\RepositoryMake;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->commands([
            RepositoryMake::class,
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
