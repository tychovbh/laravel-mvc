<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tychovbh\Mvc\Repositories\Repository;


use Tychovbh\Tests\Mvc\App\TestUser;
use Tychovbh\Tests\Mvc\App\TestUserRepository;
use Tychovbh\Tests\Mvc\TestCase;

/**
 * @property TestUserRepository repository
 */
class RepositoryTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function itCanInstantiate()
    {
        $repository = new TestUserRepository();
        $this->assertInstanceOf(Repository::class, $repository);
        return $repository;
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     */
    public function itCanListAllUsers(TestUserRepository $repository)
    {
        TestUser::destroy(TestUser::select('id')->get()->toArray());
        $collection = factory(TestUser::class, 10)->create();

        $all = $repository->all();
        $this->assertInstanceOf(Collection::class, $all);
        $this->assertEquals($collection->toArray(), $all->toArray());
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     * @throws \Exception
     */
    public function itCanFilterUsers(TestUserRepository $repository)
    {
        TestUser::destroy(TestUser::select('id')->get()->toArray());
        $user = factory(TestUser::class, 10)->create()->first();
        $all = $repository::withParams([
            'id' => $user->id
        ])->get();

        $this->assertEquals($user->toArray(), $all->first()->toArray());
    }

    /**
     * @test
     * TODO test this method sqllite is broke so cannot run tests
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     * @throws \Exception
     */
    public function itCanLimitUsers(TestUserRepository $repository)
    {
        $this->markTestIncomplete('Not yet tested');
        TestUser::destroy(TestUser::select('id')->get()->toArray());
        $users = factory(TestUser::class, 10)->create();
        $all = $repository::withParams()->limit(5)->get();

        $this->assertEquals($users->slice(0, 5)->toArray(), $all->toArray());
    }

    /**
     * @test
     * TODO test this method sqllite is broke so cannot run tests
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     * @throws \Exception
     */
    public function itCanSelectFieldsUsers(TestUserRepository $repository)
    {
        $this->markTestIncomplete('Not yet tested');
        TestUser::destroy(TestUser::select('id')->get()->toArray());
        $users = factory(TestUser::class, 10)->create()->map(function(TestUser $user) {
            return [
                'email' => $user->email,
                'name' => $user->name,
            ];
        });
        $all = $repository::withParams()->select(['email', 'name'])->get();

        $this->assertEquals($users->toArray(), $all->toArray());
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     * @throws \Exception
     */
    public function itCanIndexUsersParamFieldDoesNotExists(TestUserRepository $repository)
    {
        TestUser::destroy(TestUser::select('id')->get()->toArray());
        $user = factory(TestUser::class, 10)->create()->first();
        $all = $repository::withParams([
            'unknownfield' => 'unkownvalue'
        ])->get();

        $this->assertEquals($user->toArray(), $all->first()->toArray());
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     * @throws \Exception
     */
    public function itCanFilterCollectionOfUsers(TestUserRepository $repository)
    {
        $users = factory(TestUser::class, 10)->create();
        factory(TestUser::class, 10)->create();
        $all = $repository::withParams([
            'id' => $users->map(function (TestUser $user) {
                return $user->id;
            })->toArray()
        ])->get();

        $this->assertEquals($users->toArray(), $all->toArray());
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     * @throws \Exception
     */
    public function itCanOrderByCollectionOfUsers(TestUserRepository $repository)
    {
        TestUser::destroy(TestUser::select('id')->get()->toArray());
        factory(TestUser::class, 10)->create();
        $users = TestUser::orderBy('id', 'asc')->get();
        $all = $repository::withParams(['sort' => 'id asc'])->get();

        $this->assertEquals($users->toArray(), $all->toArray());
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     * @throws \Exception
     */
    public function itCanCustomFilterUsers(TestUserRepository $repository)
    {
        TestUser::destroy(TestUser::select('id')->get()->toArray());
        factory(TestUser::class, 10)->create();
        $user = factory(TestUser::class)->create();

        $all = $repository::withParams(['search' => $user->email])->get();
        $this->assertEquals([$user->toArray()], $all->toArray());

//        $all = $repository::withParams(['search' => $user->firstname])->get();
//        $this->assertEquals([$user->toArray()], $all->toArray());

//        $all = $repository::withParams(['search' => $user->surname])->get();
//        $this->assertEquals([$user->toArray()], $all->toArray());
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     */
    public function itCanPaginateUsers(TestUserRepository $repository)
    {
        TestUser::destroy(TestUser::select('id')->get()->toArray());
        factory(TestUser::class, 10)->create();
        $users = TestUser::paginate(4);
        $paginated = $repository->paginate(4);
        $this->assertEquals(4, $paginated->count());
        $this->assertEquals($users->toArray(), $paginated->toArray());
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     * @throws \Exception
     */
    public function itCanFilterAndPaginateUsers(TestUserRepository $repository)
    {
        TestUser::destroy(TestUser::select('id')->get()->toArray());
        factory(TestUser::class, 10)->create();
        $users = TestUser::paginate(2);
        $paginated = $repository::withParams([
            'id' => $users->map(function (TestUser $user) {
                return $user->id;
            })->toArray()
        ])->paginate(4);
        $this->assertEquals(2, $paginated->count());
        $this->assertEquals($users->toArray()['data'], $paginated->toArray()['data']);
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     */
    public function itCanFindUserById(TestUserRepository $repository)
    {
        $user = factory(TestUser::class)->create();
        $result = $repository->find($user->id);
        $this->assertInstanceOf(TestUser::class, $result);
        $this->assertEquals($user->toArray(), $result->toArray());
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     * @throws \Exception
     */
    public function itCanFindUserWithCustomFilter(TestUserRepository $repository)
    {
        $this->markTestIncomplete();
        $this->expectException(ModelNotFoundException::class);
        $user = factory(TestUser::class)->create([
            'hidden' => 1
        ]);
        $result = $repository::withParams(['hidden' => 0, 'id' => $user->id])->first();
        $this->assertInstanceOf(TestUser::class, $result);
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     */
    public function itCanSaveUser(TestUserRepository $repository)
    {
        $formData = factory(TestUser::class)->make();
        $user = $repository->save(array_merge($formData->toArray(), [
            'password' => bcrypt('secret')
        ]));
        $this->assertInstanceOf(TestUser::class, $user);
        $this->assertNotEmpty($user->id);
        $this->assertEquals($formData->email, $user->email);
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     */
    public function itCanUpdateUser(TestUserRepository $repository)
    {
        $existing = factory(TestUser::class)->create();
        $update = factory(TestUser::class)->make();
        $user = $repository->update($update->toArray(), $existing->id);
        $this->assertInstanceOf(TestUser::class, $user);
        $this->assertEquals($update->email, $user->email);
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param TestUserRepository $repository
     */
    public function itCanDestroyUser(TestUserRepository $repository)
    {
        $model = factory(TestUser::class)->create();
        $this->assertTrue($repository->destroy([$model->id]));

        try {
            $repository->find($model->id);
        } catch (\Exception $exception) {
            $this->assertInstanceOf(ModelNotFoundException::class, $exception);
        }
    }
}

