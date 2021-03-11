<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tychovbh\Mvc\Http\Resources\UserResource;
use Tychovbh\Mvc\Mail\UserPasswordReset;
use Tychovbh\Mvc\Models\Token;
use Tychovbh\Mvc\Models\TokenType;
use Tychovbh\Mvc\Models\User;
use Tychovbh\Tests\Mvc\TestCase;

class PasswordResetTest extends TestCase
{
//    /**
//     * @test
//     */
//    public function itCanStore()
//    {
//        Mail::fake();
//        $user = factory(User::class)->create();
//
//        $this->post(route('password_resets.store'), [
//            'email' => $user->email
//        ])->assertStatus(201);
//
//        Mail::assertQueued(UserPasswordReset::class, function (UserPasswordReset $mail) use ($user) {
//            return $mail->mail['email'] === $user->email;
//        });
//    }
//
//    /**
//     * @test
//     */
//    public function itCanResetPassword()
//    {
//        Mail::fake();
//        $password = random_string();
//        $user = factory(User::class)->create();
//        $token = factory(Token::class)->create([
//            'type' => TokenType::PASSWORD_RESET,
//            'value' => token([
//                'id' => $user->id,
//                'email' => $user->email,
//                'type' => TokenType::PASSWORD_RESET
//            ]),
//        ]);
//        $oldUser = DB::table('users')->where('id', $user->id)->first();
//        $this->put(route('users.password_reset'), [
//            'token' => $token->reference,
//            'password' => $password
//        ])->assertStatus(200)->assertJson([
//            'data' => ['email' => $user->email]
//        ]);
//
//        $newUser = DB::table('users')->where('id', $user->id)->first();
//
//        $this->assertDatabaseMissing('tokens', [
//            'id' => $token->id
//        ]);
//        $this->assertNotTrue($newUser->password === $oldUser->password, 'Failed to reset user password');
//
//        $this->store('users.login', UserResource::make($newUser), [
//            'email' => $oldUser->email,
//            'password' => $password
//        ], 200);
//    }
//
//    /**
//     * @test
//     */
//    public function itCannotStoreUserNotFound()
//    {
//        $user = factory(User::class)->make();
//
//        $this->post(route('password_resets.store'), [
//            'email' => $user->email
//        ])->assertStatus(400)->assertJson([
//            'email' => [
//                message('field.exists', 'email')
//            ]
//        ]);
//    }
}

