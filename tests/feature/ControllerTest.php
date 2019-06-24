<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Tychovbh\Tests\Mvc\App\TestUser;
use Tychovbh\Tests\Mvc\App\TestUserResource;
use Tychovbh\Tests\Mvc\TestCase;

class ControllerTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $users = factory(TestUser::class, 1)->create();
        $this->get(route('users.index'))
            ->assertStatus(200)
            ->assertJson(
                TestUserResource::collection($users)
                    ->response($this->app['request'])
                    ->getData(true)
            );
    }
}
