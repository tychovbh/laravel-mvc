<?php

declare(strict_types=1);

namespace Tychovbh\Tests\Mvc;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Tychovbh\Mvc\MvcServiceProvider;

class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        $this->withFactories(__DIR__.'/../database/factories');
        $this->artisan('migrate', ['--database' => 'testing']);
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
        $app['config']->set('database.default', 'testing');
        $app['config']->set('env', 'testing');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [MvcServiceProvider::class];
    }

    /** @test */
    public function itRunsTheMigrations()
    {
        $users = \DB::table('test_users')->where('id', '=', 1)->first();
        $this->assertEquals('hello@orchestraplatform.com', $users->email);
        $this->assertTrue(\Hash::check('123', $users->password));
        $columns = \Schema::getColumnListing('test_users');
        $this->assertEquals([
            'id',
            'email',
            'password',
            'firstname',
            'surname',
            'hidden',
            'created_at',
            'updated_at',
        ], $columns);
    }
}
