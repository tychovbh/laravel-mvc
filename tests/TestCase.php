<?php

declare(strict_types=1);

namespace Tychovbh\Tests\Mvc;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tychovbh\Mvc\Events\PaymentUpdated;
use Tychovbh\Mvc\MvcServiceProvider;
use Faker\Factory;
use Tychovbh\Mvc\Routes\AddressRoute;
use Tychovbh\Mvc\Routes\PasswordResetRoute;
use Tychovbh\Mvc\Routes\PaymentRoute;
use Tychovbh\Mvc\Routes\ProductRoute;
use Tychovbh\Mvc\Routes\RoleRoute;
use Tychovbh\Mvc\Routes\InviteRoute;
use Tychovbh\Mvc\Routes\UserRoute;
use Tychovbh\Tests\Mvc\App\TestUserController;
use Tychovbh\Tests\Mvc\App\TestUserRepository;

class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
        Config::set('mvc-forms.forms', [
            [
                'name' => 'test_users',
                'fields' => [
                    [
                        'element' => 'input',
                        'properties' => ['name' => 'email', 'type' => 'email', 'required' => true, 'placeholder' => 'test@example.com'],
                    ],
                    [
                        'element' => 'input',
                        'properties' => ['name' => 'password', 'type' => 'password', 'required' => true],
                    ],
                    [
                        'element' => 'input',
                        'properties' => ['name' => 'name', 'type' => 'text', 'required' => true],
                    ],
                    [
                        'element' => 'input',
                        'properties' => ['name' => 'avatar', 'type' => 'file'],
                    ],
                ]
            ],
            [
                'name' => 'users',
                'fields' => [
                    [
                        'element' => 'input',
                        'properties' => ['name' => 'email', 'type' => 'email', 'required' => true, 'placeholder' => 'test@example.com'],
                    ],
                    [
                        'element' => 'input',
                        'properties' => ['name' => 'password', 'type' => 'password', 'required' => true],
                    ],
                    [
                        'element' => 'input',
                        'properties' => ['name' => 'name', 'type' => 'text', 'required' => true],
                    ],
                    [
                        'element' => 'input',
                        'properties' => ['name' => 'avatar', 'type' => 'file'],
                    ],
                    [
                        'element' => 'select',
                        'properties' => [
                            'name' => 'role_id',
                            'options' => [],
                            'source' => function () {
                                return route('roles.index');
                            },
                            'label_key' => 'label',
                            'value_key' => 'id'
                        ],
                    ],
                ]
            ],
        ]);

        $faker = Factory::create();
        Config::set('mvc-collections', array_merge(config('mvc-collections'), [
            [
                'table' => 'test_users',
                'repository' => TestUserRepository::class,
                'update_by' => 'email',
                'items' => [
                    [
                        'email' => $faker->email,
                        'password' => $faker->password,
                        'name' => $faker->name
                    ],
                    [
                        'email' => $faker->email,
                        'password' => $faker->password,
                        'name' => $faker->name
                    ],
                    [
                        'email' => $faker->email,
                        'password' => $faker->password,
                        'name' => $faker->name
                    ],
                ],
            ]
        ]));
        $this->withFactories(__DIR__ . '/database/factories');
        $this->withFactories(__DIR__ . '/../database/factories');
        $this->artisan('migrate', ['--database' => 'testing']);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->artisan('mvc:update');
        $this->artisan('mvc:collections');
    }

    private function setConfig(string $name)
    {
        $config = require __DIR__ . '/../config/' . $name . '.php';
        Config::set($name, $config);
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        putenv('AUTH_EMAIL_VERIFY_ENABLED=true');
        putenv('MOLLIE_KEY=test_stnkpmwu8T5VhmyMxnnyMQRVtyrNCm');
        $this->setConfig('mvc-messages');
        $this->setConfig('mvc-collections');
        $this->setConfig('mvc-forms');
        $this->setConfig('mvc-auth');
        $this->setConfig('mvc-mail');
        $app['config']->set('env', 'testing');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('filesystems.disks.local.root', storage_path('framework/testing/disks/app'));
        $app['config']->set('mvc-auth.secret', 'sec!ReT423*&');
        $app['config']->set('mvc-auth.id', '1');
        $app['config']->set('mvc-auth.email_verify_enabled', true);
        $app['config']->set('mvc-auth.url', 'https://localhost:3000/users/create/{reference}');
        $app['config']->set('mvc-auth.password_reset_url', 'https://localhost:3000/users/password_reset/{reference}');

        $app['config']->set('mvc-payments.return', 'https://localhost:3000/payments/{id}');
        $app['config']->set('mvc-payments.broadcasting', [
            'enabled' => true,
            'event' => PaymentUpdated::class,
        ]);

        $app['router']->get('test_users', TestUserController::class . '@index')->name('test_users.index');
        $app['router']->get('test_users/create', TestUserController::class . '@create')->name('test_users.create');
        $app['router']->post('test_users', TestUserController::class . '@store')->name('test_users.store');
        $app['router']->put('test_users/{id}', TestUserController::class . '@update')->name('test_users.update');
        InviteRoute::routes();
        UserRoute::routes();
        RoleRoute::routes();
        PasswordResetRoute::routes();
        PaymentRoute::routes();
        ProductRoute::routes();
        AddressRoute::routes();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [MvcServiceProvider::class];
    }

    /**
     * Index resource
     * @param $uri
     * @param JsonResource $resource
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function index($uri, JsonResource $resource, array $headers = [])
    {
        $response = parent::get(route($uri), $headers)
            ->assertStatus(200)
            ->assertJson(
                $resource->response($this->app['request'])->getData(true)
            );

        return $response;
    }

    /**
     * Show resource
     * @param $uri
     * @param JsonResource $resource
     * @param int $status
     * @param mixed $assert
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function show($uri, JsonResource $resource, int $status = 200, array $assert = [])
    {
        $response = parent::get(route($uri, ['id' => $resource->id]))
            ->assertStatus($status)
            ->assertJson(
                $assert ?? $resource->response($this->app['request'])->getData(true)
            );

        return $response;
    }

    /**
     * Show resource
     * @param $uri
     * @param JsonResource $resource
     * @param int $status
     * @param mixed $assert
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function create($uri, JsonResource $resource, int $status = 200, array $assert = [])
    {
        $response = parent::get(route($uri))
            ->assertStatus($status)
            ->assertJson(
                $assert ?? $resource->response($this->app['request'])->getData(true)
            );

        return $response;
    }

    /**
     * Store resource
     * @param $uri
     * @param JsonResource $resource
     * @param array $data
     * @param int $status
     * @param array $assert
     * @param int|null $user_id
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function store(
        $uri,
        JsonResource $resource,
        array $data = [],
        int $status = 201,
        array $assert = [],
        int $user_id = null
    ) {
        return parent::post(route($uri), $data, $user_id ? [
            'HTTP_Authorization' => 'Bearer ' . token($user_id)
        ] : [])
            ->assertStatus($status)
            ->assertJson(
                $assert ?? $resource->response($this->app['request'])->getData(true)
            );
    }

    /**
     * Update resource
     * @param $uri
     * @param JsonResource $resource
     * @param array $params
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function update($uri, JsonResource $resource, array $params)
    {
        $response = parent::put(route($uri, ['id' => $resource->id]), $params)
            ->assertStatus(200)
            ->assertJson(
                $resource->response($this->app['request'])->getData(true)
            );

        return $response;
    }

    /**
     * Destroy resource
     * @param $uri
     * @param JsonResource $resource
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function destroy($uri, JsonResource $resource)
    {
        $response = parent::delete(route($uri, ['id' => $resource->id]))
            ->assertStatus(200)
            ->assertJson(['deleted' => 1]);

        $uri = explode('.', $uri);

        $this->assertDatabaseMissing($uri[0], [
            'id' => $resource->id
        ]);

        return $response;
    }

    /**
     * Assert that for each item in a collection, a given where condition exists in the database.
     * @param string $table
     * @param array $collection
     */
    public function assertDatabaseHasCollection(string $table, array $collection)
    {
        foreach ($collection as $item) {
            $this->assertDatabaseHas($table, $item);
        }
    }
}
