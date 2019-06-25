<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Tychovbh\Mvc\Field;
use Tychovbh\Mvc\Form;
use Tychovbh\Mvc\Http\Resources\FormResource;
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

    /**
     * @test
     */
    public function itCanCreateForm()
    {
        $form = factory(Form::class)->create([
            'name' => 'test_users'
        ]);
        factory(Field::class, 2)->create([
            'form_id' => $form->id,
        ]);

        $form = new FormResource($form);

        $this->get(route('users.create'))
            ->assertStatus(200)
            ->assertJson(
                $form->response($this->app['request'])
                    ->getData(true)
            );
    }
}
