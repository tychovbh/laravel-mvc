<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature\Commands;

use Tychovbh\Mvc\Role;
use Tychovbh\Mvc\TokenType;
use Tychovbh\Mvc\User;
use Tychovbh\Tests\Mvc\TestCase;

class MvcUserTokenTest extends TestCase
{
    /**
     * @test
     */
    public function itCanCreateUserToken()
    {
        $user = factory(User::class)->create();
        $this->artisan('mvc-user:token', [
            '--email' => $user->email,
            '--type' => TokenType::API_KEY
        ]);
    }
}
