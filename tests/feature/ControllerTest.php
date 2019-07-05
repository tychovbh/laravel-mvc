<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        $this->get(route('test_users.index'))
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

        $this->get(route('test_users.create'))
            ->assertStatus(200)
            ->assertJson(
                $form->response($this->app['request'])
                    ->getData(true)
            );
    }

    /**
     * @test
     */
    public function itCanStore()
    {
        Storage::fake('app/public/avatars');

        $user = factory(TestUser::class)->make([
            'avatar' => UploadedFile::fake()->image('fake_photo.jpg')
        ]);
        $store = $user->toArray();
        $store['password'] = uniqid();

        $response = $this->post(route('test_users.store'), $store)
            ->assertStatus(201);
        $user = json_decode($response->baseResponse->getContent(), true)['data'];

        Storage::disk('app/public/avatars')->assertExists(str_replace('avatars/', '', $user['avatar']));
    }

    /**
     * @test
     */
    public function itCanUpdate()
    {
        Storage::fake('app/public/avatars');

        $user = factory(TestUser::class)->create();
        $update = factory(TestUser::class)->make();
        $update->avatar = UploadedFile::fake()->image('fake_photo.jpg');

        $response = $this->put(route('test_users.update', ['id' => $user->id]), $update->toArray())
            ->assertStatus(200);
        $response = json_decode($response->getContent(), true)['data'];

        Storage::disk('app/public/avatars')->assertMissing($user->avatar);
        Storage::disk('app/public/avatars')->assertExists(str_replace('avatars/', '', $response['avatar']));

        $this->assertEquals($update->email, $response['email']);
    }

    /**
     * @test
     */
    public function storeFromCreate()
    {
        $this->seedTestForm();

        $form = $this->get(route('test_users.create'));
        $content = json_decode($form->baseResponse->getContent());

        $user = factory(TestUser::class)->make([
            'password' => uniqid(),
            'id' => 1,
        ]);

        $store = [];
        foreach ($content->data->fields as $field) {
            $store[$field->name] = $user->{$field->name};
        }

        $user = new TestUserResource($user);

        $this->post($content->data->route, $store)
            ->assertStatus(201)
            ->assertJson(
                $user->response($this->app['request'])
                    ->getData(true)
            );
    }

    /**
     * Seed a test form
     */
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
        factory(Field::class)->create([
            'label' => 'avatar',
            'name' => 'avatar',
            'description' => 'avatar',
            'placeholder' => '',
            'required' => 'true',
            'form_id' => $form->id,
        ]);
    }
}
