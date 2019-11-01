<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Tychovbh\Mvc\Form;
use Tychovbh\Mvc\Http\Resources\FormResource;
use Tychovbh\Mvc\Http\Resources\UserResource;
use Tychovbh\Mvc\Mail\UserCreated;
use Tychovbh\Mvc\Mail\UserVerify;
use Tychovbh\Mvc\TokenType;
use Tychovbh\Mvc\Role;
use Tychovbh\Tests\Mvc\TestCase;

use Illuminate\Support\Arr;
use Tychovbh\Mvc\Token;
use Tychovbh\Mvc\User;
use Faker\Factory;
use Illuminate\Support\Facades\Mail;


class UserTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $users = factory(User::class, 3)->create();
        $this->index('users.index', UserResource::collection($users));
    }

    /**
     * @test
     */
    public function itCanShow()
    {
        $user = factory(User::class)->create();
        $this->show('users.show', UserResource::make($user));
    }

    /**
     * @test
     */
    public function itCanCreate()
    {
        $response = $this->create('users.create', FormResource::make(Form::where('name', 'users')->first()));
        return json_decode($response->getContent(), true)['data'];
    }

    /**
     * @test
     */
    public function itCannotFind()
    {
        $user = factory(User::class)->create();
        $user->id = -1;
        $this->show('users.show', UserResource::make($user), 404, [
            'message' => message('model.notfound', 'User', 'ID', -1)
        ]);
    }

    /**
     * @test
     */
    public function itCanStore()
    {
        Mail::fake();

        $user = $this->user();
        $this->storeUser($user);

        $this->assertDatabaseHas('user_roles', [
            'role_id' => $user['data']['role_id'],
            'user_id' => $user['resource']->id,
        ]);

        Mail::assertQueued(UserCreated::class, function (UserCreated $mail) use ($user) {
            return $mail->email = $user['data']['email'];
        });

        Mail::assertQueued(UserVerify::class, function (UserVerify $mail) use ($user) {
            return $mail->mail['email'] = $user['data']['email'];
        });
    }

    /**
     * @test
     * @depends itCanCreate
     * @param array $form
     */
    public function storeFromCreate(array $form)
    {
        Mail::fake();
        $user = factory(User::class)->make([
            'password' => uniqid(),
            'id' => 1,
        ]);

        $role = factory(Role::class)->create();

        $store = [];
        foreach ($form['fields'] as $field) {
            $properties = $field['properties'];

            if (Arr::has($properties, 'source')) {
                $response = $this->get($properties['source']);
                $data = json_decode($response->getContent(), true)['data'];
                $store[$properties['name']] = $data[0][$properties['value_key']];
                continue;
            }

            $store[$properties['name']] = $user->{$properties['name']};
        }

        $user->roles = new Collection([$role]);
        $user = new UserResource($user);

        $this->post($form['route'], $store)
            ->assertStatus(201)
            ->assertJson(
                $user->response($this->app['request'])
                    ->getData(true)
            );
    }

    /**
     * @test
     */
    public function itCanUpdate()
    {
        $user = factory(User::class)->create();
        $update = factory(User::class)->make();
        $params = $update->toArray();
        $update->id = $user->id;
        $update->updated_at = Carbon::now();

        $this->update('users.update', UserResource::make($update), $params);
    }

    /**
     * @test
     */
    public function itCanUpdateAndVerify()
    {
        $user = factory(User::class)->create();
        $token = factory(Token::class)->create([
            'type' => TokenType::VERIFY_EMAIL,
            'value' => token([
                'id' => $user->id,
                'email' => $user->email,
            ]),
        ]);

        $this->update('users.update', UserResource::make($user), [
            'token' => $token->reference
        ]);

        $user = User::find($user->id);

        $this->assertNotEmpty($user->email_verified_at, 'Email verified not updated');

        $this->assertDatabaseMissing('tokens', [
            'value' => $token->value
        ]);
    }

    /**
     * @test
     */
    public function itCanSendVerificationEmail()
    {
        Mail::fake();
        $user = factory(User::class)->create();
        $this->store('users.send_verify_email', UserResource::make($user), [
            'email' => $user->email
        ], 200);

        Mail::assertQueued(UserVerify::class, function (UserVerify $mail) use ($user) {
            return $mail->mail['email'] = $user->email;
        });
    }

    /**
     * @test
     */
    public function itCanStoreViaToken()
    {
        // TODO fix test case
        Mail::fake();

        $user = $this->user();
        $email = $user['data']['email'];

        $invite = factory(Token::class)->create([
            'reference' => uniqid(),
            'value' => token([
                'email' => $email,
                'role_id' => $user['data']['role_id']
            ])
        ]);

        $user['data']['token'] = $invite->reference;
        Arr::forget($user['data'], ['email', 'role_id']);

        $this->storeUser($user);

        $this->assertDatabaseMissing('tokens', [
            'reference' => $invite->reference
        ]);

        Mail::assertQueued(UserCreated::class, function (UserCreated $mail) use ($email) {
            return $mail->email = $email;
        });
    }

    /**
     * @test
     */
    public function itCannotStoreTokenNotFound()
    {
        $user = $this->user();
        $user['data']['token'] = random_string();
        $this->storeUser($user, 404, [
            'message' => message('model.notfound', 'Invite', 'Reference', $user['data']['token'])
        ]);
    }

    /**
     * @test
     */
    public function itCannotStoreDuplicateEmail()
    {
        $user = $this->user();
        factory(User::class)->create([
            'email' => $user['data']['email']
        ]);

        $this->store('users.store', UserResource::make($user), $user['data'], 400, [
            'email' => [message('field.unique', 'email')]
        ]);
    }

    /**
     * @test
     */
    public function itCannotStoreMissingField()
    {
        $user = $this->user();
        $this->storeUserMissingField($user, 'email', 'email');
    }

    /**
     * Store a user Resource, but misses a field
     * @param array $user
     * @param string $field
     * @param string $translation
     */
    private function storeUserMissingField(array $user, string $field, string $translation)
    {
        unset($user['data'][$field]);
        $this->storeUser($user, 400, [
            $field => [message('field.required', $translation)]
        ]);
    }

    /**
     * @test
     */
    public function itCannotStorePasswordToShort()
    {
        $user = $this->user();
        $user['data']['password'] = 1234567;

        $this->storeUser($user, 400, [
            'password' => [message('field.min', 'password', 8)]
        ]);
    }

    /**
     * Get user for store
     * @return array
     */
    private function user(): array
    {
        $faker = Factory::create();
        $user = factory(User::class)->make();
        $data = $user->toArray();
        $data['role_id'] = factory(Role::class)->create()->id;
        $data['password'] = $faker->password(8);
        $user->id = 1;

        return [
            'resource' => $user,
            'data' => $data
        ];
    }

    /**
     * Store User
     * @param array $user
     * @param int $status
     * @param array $assert
     */
    private function storeUser(array $user, int $status = 201, array $assert = [])
    {
        $this->store('users.store', UserResource::make($user['resource']), $user['data'], $status, $assert);
    }

    /**
     * @test
     */
    public function itCanLogin()
    {
        $password = random_string();
        $user = factory(User::class)->create([
            'password' => $password,
        ]);
        $this->store('users.login', UserResource::make($user), [
            'email' => $user->email,
            'password' => $password
        ], 200);
    }

    /**
     * @test
     */
    public function itCantLoginEmailNotVerified()
    {
        $password = random_string();
        $user = factory(User::class)->create([
            'password' => $password,
            'email_verified_at' => null
        ]);
        $this->store('users.login', UserResource::make($user), [
            'email' => $user->email,
            'password' => $password
        ], 401, [
            'message' => message('login.email.unverified')
        ]);
    }

    /**
     * @test
     */
    public function itCanLoginById()
    {
        $password = random_string();
        $user = factory(User::class)->create([
            'password' => $password
        ]);
        $this->store('users.login', UserResource::make($user), [
            'id' => $user->id,
            'password' => $password,
            'login_field' => 'id'
        ], 200);
    }

    /**
     * @test
     */
    public function itCanDestroy()
    {
        $user = factory(User::class)->create();
        $this->destroy('users.destroy', UserResource::make($user));
    }
}


