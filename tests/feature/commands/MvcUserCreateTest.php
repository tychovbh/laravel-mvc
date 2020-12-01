<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature\Commands;

use Tychovbh\Mvc\Models\Role;
use Tychovbh\Mvc\Models\TokenType;
use Tychovbh\Mvc\Models\User;
use Tychovbh\Tests\Mvc\TestCase;

class MvcUserCreateTestTest extends TestCase
{
    /**
     * @test
     */
    public function itCanCreateUser()
    {
        $user = factory(User::class)->make();
        $role = factory(Role::class)->create();
        $this->artisan('mvc-user:create', [
            '--email' => $user->email,
            '--password' => $user->password,
            '--name' => $user->name,
            '--role' => $role->label,
            '--admin' => $user->is_admin,
            '--type' => TokenType::API_KEY
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'name' => $user->name,
            'is_admin' => $user->is_admin
        ]);

        $this->assertDatabaseHas('user_roles', [
            'user_id' => 1,
            'role_id' => $role->id,
        ]);
    }
}
