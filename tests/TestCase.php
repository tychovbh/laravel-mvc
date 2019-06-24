<?php

declare(strict_types=1);

namespace Tychovbh\Tests\Mvc;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Tychovbh\Mvc\MvcServiceProvider;
use Tychovbh\Tests\Mvc\App\TestUserController;

class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__ . '/database/factories');
        $this->artisan('migrate', ['--database' => 'testing']);
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('env', 'testing');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['router']->get('users', TestUserController::class . '@index')->name('users.index');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [MvcServiceProvider::class];
    }
}
