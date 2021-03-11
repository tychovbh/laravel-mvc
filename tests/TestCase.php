<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Testing\TestResponse;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tychovbh\Mvc\Events\PaymentUpdated;
use Tychovbh\Mvc\Models\Model;
use Tychovbh\Mvc\MvcServiceProvider;
use Faker\Factory;
use Tychovbh\Mvc\Routes\AddressRoute;
use Tychovbh\Mvc\Routes\ContractRoute;
use Tychovbh\Mvc\Routes\CountryRoute;
use Tychovbh\Mvc\Routes\DatabaseRoute;
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

        Config::set('mvc-document-sign', [
                'default' => 'SignRequest',
                'providers' => [
                    // SANDBOX account
                    'SignRequest' => [
                        'token' => '69d3a60fbb9c08bbfbb7525cb704ac1984b2f9db',
                        'subdomain' => 'https://bespokeweb.signrequest.com/api/v1',
                    ],
                    'DocuSign' => [
                        //
                    ]
                ]
            ]
        );

        Config::set('mvc-html-converter', [
            'default' => 'PhantomMagickConverter',
        ]);

        Config::set('mvc-address-lookup', [
            'default' => 'PdokService',
            'providers' => [
                'PdokService' => [
                    'base_url' => 'https://geodata.nationaalgeoregister.nl/locatieserver/v3/free'
                ]
            ]
        ]);

        Config::set('mvc-contracts', [
            'pdf' => [
                'enabled' => true
            ],
            'document_sign' => [
                'enabled' => true,
                'return' => 'http://localhost/contracts/{id}',
                'from_email' => 'noreply@bespokeweb.nl',
                'from_name' => 'bespokeweb'
            ]
        ]);

        Config::set('mvc-shop', [
            'default' => 'Shopify',
            'providers' => [
                'Shopify' => [
                    'api_key' => 'e8ce5aea5b75cb7ffe085f69c88cc168',
                    'password' => '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',
                    'domain' => 'wijzerwonen.myshopify.com',
                    'version' => '2021-01',
                ]
            ]
        ]);

        Config::set('mvc-voucher', [
            'default' => 'WinstUitJeWoning',
            'providers' => [
                'WinstUitJeWoning' => [
                    'url' => '',
                    'token' => '',
                    'store' => [
                        'id' => 0,
                        'name' => ''
                    ]
                ]
            ]
        ]);

//        $this->withFactories(__DIR__ . '/database/factories');
//        $this->withFactories(__DIR__ . '/../database/factories');
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

    protected function getPackageAliases($app)
    {
        return [
            'Tychovbh\Mvc' => 'Tychovbh\Mvc\Facade'
        ];
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
        $app['config']->set('view.paths.0', __DIR__ . '/resources/views');
        $app['config']->set('mvc-payments.return', 'https://localhost:3000/payments/{id}');
        $app['config']->set('mvc-payments.broadcasting', [
            'enabled' => true,
            'event' => PaymentUpdated::class,
        ]);

        $app['router']->get('test_users', TestUserController::class . '@index')->name('test_users.index');
        $app['router']->get('test_users/create', TestUserController::class . '@create')->name('test_users.create');
        $app['router']->post('test_users', TestUserController::class . '@store')->name('test_users.store');
        $app['router']->put('test_users/{id}', TestUserController::class . '@update')->name('test_users.update');
        $app['router']->delete('test_users/{id}', TestUserController::class . '@destroy')->name('test_users.destroy');
        InviteRoute::routes();
        UserRoute::routes();
        RoleRoute::routes();
        PasswordResetRoute::routes();
        PaymentRoute::routes();
        DatabaseRoute::routes();
        ProductRoute::routes();
        AddressRoute::routes();
        CountryRoute::routes();
        ContractRoute::routes();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [MvcServiceProvider::class];
    }

    /**
     * Index resource
     * @param $uri
     * @param JsonResource $expected
     * @param array $params
     * @param array $headers
     * @return TestResponse
     */
    public function index($uri, JsonResource $expected, array $params = [], array $headers = []): TestResponse
    {
        return parent::get(route($uri, $params), $headers)
            ->assertJson(
                $expected->response($this->app['request'])->getData(true)
            )
            ->assertStatus(200);
    }
//
//    /**
//     * Show resource
//     * @param $uri
//     * @param JsonResource $expected
//     * @param int $status
//     * @param mixed $assert
//     * @return \Illuminate\Foundation\Testing\TestResponse
//     */
//    public function show($uri, JsonResource $expected, int $status = 200, array $assert = [])
//    {
//        return parent::get(route($uri, ['id' => $expected->id]))
//            ->assertJson(
//                $assert ?? $expected->response($this->app['request'])->getData(true)
//            )
//            ->assertStatus($status);
//    }
//
//    /**
//     * Show resource
//     * @param $uri
//     * @param JsonResource $resource
//     * @param int $status
//     * @param mixed $assert
//     * @return \Illuminate\Foundation\Testing\TestResponse
//     */
//    public function create($uri, JsonResource $resource, int $status = 200, array $assert = [])
//    {
//        $response = parent::get(route($uri))
//            ->assertStatus($status)
//            ->assertJson(
//                $assert ?? $resource->response($this->app['request'])->getData(true)
//            );
//
//        return $response;
//    }
//
    /**
     * Store resource
     * @param $uri
     * @param JsonResource $expected
     * @param array $params
     * @param int $status
     * @param array $assert
     * @param int|null $user_id
     * @return TestResponse
     */
    public function store(
        $uri,
        JsonResource $expected,
        array $params = [],
        int $status = 201,
        array $assert = [],
        int $user_id = null
    ): TestResponse
    {
        return parent::post(route($uri), $params, $user_id ? [
            'HTTP_Authorization' => 'Bearer ' . token($user_id)
        ] : [])
            ->assertJson(
                !empty($assert) ? $assert : $expected->response($this->app['request'])->getData(true)
            )
            ->assertStatus($status);
    }
//
//    /**
//     * Update resource
//     * @param $uri
//     * @param JsonResource $expected
//     * @param array $params
//     * @return \Illuminate\Foundation\Testing\TestResponse
//     */
//    public function update($uri, JsonResource $expected, array $params)
//    {
//        $response = parent::put(route($uri, ['id' => $expected->id]), $params)
//            ->assertJson(
//                $expected->response($this->app['request'])->getData(true)
//            )
//            ->assertStatus(200);
//
//        return $response;
//    }
//
//    /**
//     * Destroy resource
//     * @param $uri
//     * @param Model $model
//     * @return \Illuminate\Foundation\Testing\TestResponse
//     */
//    public function destroy($uri, Model $model)
//    {
//        $response = parent::delete(route($uri, ['id' => $model->id]))
//            ->assertJson(['deleted' => 1])
//            ->assertStatus(200);
//
//        $uri = explode('.', $uri);
//
//        $this->assertDatabaseMissing($uri[0], [
//            'id' => $model->id
//        ]);
//
//        return $response;
//    }
//
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
