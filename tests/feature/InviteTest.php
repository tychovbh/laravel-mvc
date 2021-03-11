<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\feature;

use Illuminate\Support\Facades\Mail;
use Tychovbh\Mvc\Mail\UserInvite;
use Tychovbh\Mvc\Models\User;
use Tychovbh\Tests\Mvc\TestCase;

class InviteTest extends TestCase
{
//    /**
//     * @test
//     */
//    public function itCanInviteUser()
//    {
//        Mail::fake();
//        $user = factory(User::class)->make();
//
//        $this->invite([
//            'name' => $user->name,
//            'email' => $user->email,
//        ], 201)
//            ->assertJsonStructure(['data']);
//
//        Mail::assertQueued(UserInvite::class, function (UserInvite $mail) use ($user) {
//            return $mail->mail['email'] === $user->email;
//        });
//    }
//
//    /**
//     * @test
//     */
//    public function itCannotInviteUserFieldMissing()
//    {
//        $user = factory(User::class)->make();
//
//        $this->invite([
//            'email' => $user->email,
//        ], 400)->assertJson([
//            'name' => [message('field.required', 'name')]
//        ]);
//    }
//
//
//    /**
//     * @test
//     * TODO implement test when authentication works
//     */
//    public function itCannotInviteUserForbidden()
//    {
//        $this->markTestSkipped('TODO implement test when authentication works');
//        $user = factory(User::class)->make();
//        $this->invite([
//            'name' => $user->name,
//            'email' => $user->email,
//        ], 401, true)->assertJson([
//            'message' => message('auth.unauthorized')
//        ]);
//    }
//
//    /**
//     * Send invite
//     * @param array $data
//     * @param int $status
//     * @param bool $authenticate
//     * @return \Illuminate\Foundation\Testing\TestResponse
//     */
//    private function invite(array $data, int $status, bool $authenticate = false)
//    {
//        return $this->post(route('invites.store'), $data)->assertStatus($status);
//    }
}

