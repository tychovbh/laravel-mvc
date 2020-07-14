<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Illuminate\Support\Carbon;
use Tychovbh\Mvc\Http\Resources\RoleResource;
use Tychovbh\Mvc\Role;
use Tychovbh\Mvc\User;
use Tychovbh\Tests\Mvc\TestCase;

class RoleTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $roles = factory(Role::class, 10)->create();
        $this->index('roles.index', RoleResource::collection($roles));
    }

    /**
     * @test
     */
    public function itCanUpdate()
    {
        $role = factory(Role::class)->create();
        $update = factory(Role::class)->make();
        $params = $update->toArray();
        $params['users'] = factory(User::class, 3)->create()->map(function (User $user) {
            return $user->id;
        })->toArray();
        $update->id = $role->id;
        $update->updated_at = Carbon::now();
        $update->created_at = $role->created_at;

        $this->update('roles.update', RoleResource::make($update), $params);

        foreach ($params['users'] as $id) {
            $this->assertDatabaseHas('user_roles', [
                'role_id' => $role->id,
                'user_id' => $id,
            ]);
        }
    }
}

