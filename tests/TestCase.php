<?php

declare(strict_types=1);

namespace Tychovbh\Tests\Mvc;

use Illuminate\Http\Resources\Json\JsonResource;
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
        $this->withFactories(__DIR__ . '/../database/factories');
        $this->artisan('migrate', ['--database' => 'testing']);
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('env', 'testing');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['router']->get('users', TestUserController::class . '@index')->name('users.index');
        $app['router']->get('users', TestUserController::class . '@create')->name('users.create');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [MvcServiceProvider::class];
    }

    public function index($uri, JsonResource $resource, array $headers = [])
    {
        $response = parent::get(route($uri), $headers)
            ->assertStatus(200)
            ->assertJson(
                $resource->response($this->app['request'])->getData(true)
            );

        return $response;
    }

    public function show($uri, JsonResource $resource)
    {
        $response = parent::get(route($uri, ['id' => $resource->id]))
            ->assertStatus(200)
            ->assertJson(
                $resource->response($this->app['request'])->getData(true)
            );

        return $response;
    }

    public function store($uri, JsonResource $resource)
    {
        $response = parent::post(route($uri), $resource->toArray($this->app['request']))
            ->assertStatus(201)
            ->assertJson(
                $resource->response($this->app['request'])->getData(true)
            );

        return $response;
    }

    public function update($uri, JsonResource $resource)
    {
        $response = parent::put(route($uri, ['id' => $resource->id]))
            ->assertStatus(200)
            ->assertJson(
                $resource->response($this->app['request'])->getData(true)
            );

        return $response;
    }

    public function destroy($uri, JsonResource $resource)
    {
        $response = parent::delete(route($uri, ['id' => $resource->id]))
            ->assertStatus(200)
            ->assertJson(['deleted' => 1]);

        $uri = explode('.', $uri);

        $this->assertDatabaseMissing($uri[0], $resource->toArray($this->app['request']));

        return $response;
    }
}
