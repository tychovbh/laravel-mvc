<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tychovbh\Mvc\Http\Resources\UserResource;
use Tychovbh\Mvc\Mail\UserCreated;
use Tychovbh\Mvc\Role;
use Tychovbh\Tests\Mvc\TestCase;

use Illuminate\Support\Arr;
use Tychovbh\Mvc\Invite;
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
    }

    /**
     * @test
     */
    public function itCanStoreViaToken()
    {
        Mail::fake();

        $user = $this->user();
        $email = $user['data']['email'];

        $invite = factory(Invite::class)->create([
            'reference' => random_string(),
            'token' => token([
                'email' => $email,
                'role_id' => $user['data']['role_id']
            ])
        ]);

        $user['data']['token'] = $invite->reference;
        Arr::forget($user['data'], ['email', 'role_id']);

        $this->storeUser($user);

        $this->assertDatabaseMissing('invites', [
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
        $this->storeUserMissingField($user, 'password', 'password');
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
            'password' => $password
        ]);
        $this->store('users.login', UserResource::make($user), [
            'email' => $user->email,
            'password' => $password
        ], 200);
    }
}


