<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tychovbh\Mvc\Field;
use Tychovbh\Mvc\Form;
use Tychovbh\Mvc\Http\Resources\FormResource;
use Tychovbh\Mvc\Element;
use Tychovbh\Mvc\Property;
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
        $form = new FormResource(Form::where('name', 'test_users')->first());

        $response = $this->get(route('test_users.create'))
            ->assertStatus(200)
            ->assertJson(
                $form->response($this->app['request'])
                    ->getData(true)
            );

        $form = json_decode($response->getContent(), true)['data'];

        return $form;
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
     * @depends itCanCreateForm
     * @param array $form
     */
    public function storeFromCreate(array $form)
    {
        $user = factory(TestUser::class)->make([
            'password' => uniqid(),
            'id' => 1,
        ]);

        $store = [];
        foreach ($form['fields'] as $field) {
            $store[$field['properties']['name']] = $user->{$field['properties']['name']};
        }

        $user = new TestUserResource($user);

        $this->post($form['route'], $store)
            ->assertStatus(201)
            ->assertJson(
                $user->response($this->app['request'])
                    ->getData(true)
            );
    }
}
