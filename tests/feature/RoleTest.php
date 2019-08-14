<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Tychovbh\Mvc\Http\Resources\RoleResource;
use Tychovbh\Mvc\Role;
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
}

