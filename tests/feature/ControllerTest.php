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

    /**
     * @test
     */
    public function storeFromCreate()
    {
        $this->seedTestForm();

        $form = $this->get(route('users.create'));
        $content = json_decode($form->baseResponse->getContent());

        $user = factory(TestUser::class)->make();
        $user->password = uniqid();

        $params = [];
        foreach ($content->data->fields as $field) {
            $params[$field->name] = $user->{$field->name};
        }

        $user = new TestUserResource($user);

        $this->post(route($content->data->route, $params))
            ->assertStatus(201)
            ->assertJson(
                $user->response($this->app['request'])
                    ->getData(true)
            );
    }

    private function seedTestForm()
    {
        $form = factory(Form::class)->create([
            'name' => 'test_users'
        ]);
        factory(Field::class)->create([
            'label' => 'email',
            'name' => 'email',
            'description' => 'email',
            'placeholder' => 'test@example.com',
            'required' => 'true',
            'form_id' => $form->id,
        ]);
        factory(Field::class)->create([
            'label' => 'password',
            'name' => 'password',
            'description' => 'password',
            'placeholder' => '',
            'required' => 'true',
            'form_id' => $form->id,
        ]);
    }
}
