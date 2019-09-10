<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tychovbh\Mvc\Http\Resources\UserResource;
use Tychovbh\Mvc\Mail\UserPasswordReset;
use Tychovbh\Mvc\PasswordReset;
use Tychovbh\Mvc\User;
use Tychovbh\Tests\Mvc\TestCase;

class PasswordResetTest extends TestCase
{
    /**
     * @test
     */
    public function itCanStorePasswordReset()
    {
        Mail::fake();
        $user = factory(User::class)->create();

        $response = $this->post(route('password_resets.store'), [
            'email' => $user->email
        ])->assertStatus(201)->assertJson([
            'data' => ['email' => $user->email]
        ]);

        Mail::assertQueued(UserPasswordReset::class, function (UserPasswordReset $mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        return json_decode($response->getContent(), true)['data'];
    }

    /**
     * @test
     */
    public function itCanResetPassword()
    {
        Mail::fake();
        $password = random_string();
        $passwordReset = factory(PasswordReset::class)->create();
        $oldUser = DB::table('users')->where('id', $passwordReset->user->id)->first();
        $this->put(route('users.update.password_reset'), [
            'token' => $passwordReset->token,
            'password' => $password
        ])->assertStatus(200)->assertJson([
            'data' => ['email' => $passwordReset['email']]
        ]);

        $newUser = DB::table('users')->where('id', $passwordReset->user->id)->first();

        $this->assertNotTrue($newUser->password === $oldUser->password, 'Failed to reset user password');

        $this->store('users.login', UserResource::make($newUser), [
            'email' => $oldUser->email,
            'password' => $password
        ], 200);
    }
}

