<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Lumen\Testing\TestCase;
use PHPUnit\Framework\Assert as PHPUnit;

trait LumenTestHelper
{
    /**
     * Generate a user with a token to perform authenticated requests.
     * @param array $params
     * @return array
     */
    public function token(array $params = []): array
    {
        $user_id = Arr::get($params, 'id') ?? factory(User::class, 1)->create($params)->first()->id;

        return [
            'HTTP_Authorization' => 'Bearer ' . token($user_id)
        ];
    }

    /**
     * Perform get request but empty cache record first.
     * @param string $route
     * @param array $params
     * @param bool $token
     * @param array $tokenParams
     * @return TestCase
     */
    public function getWithoutCache(
        string $route,
        array $params = [],
        bool $token = false,
        array $tokenParams = []
    ): TestCase
    {
        Cache::tags($route)->flush();
        $route = route($route, $params);
        $name = str_replace(config('app.url'), '', $route);
        Cache::forget($name);
        return $this->get($route, $token ? $this->token($tokenParams) : []);
    }

    /**
     * Perform get request with token
     * @param string $route
     * @param array $params
     * @return TestCase
     */
    public function getWithToken(string $route, array $params = []): TestCase
    {
        return $this->get(route($route, $params), $this->token());
    }

    /**
     * Get content from request
     * @param $request
     * @return array
     */
    public function content($request): array
    {
        return json_decode($request->response->getContent(), true);
    }

    /**
     * See resource
     * @param Resource $resource
     * @param array $except
     * @return TestCase
     */
    public function seeResource(Resource $resource, array $except = [])
    {
        return $this->seeJsonExcept($resource->response($this->app['request'])->getData(true), null, $except);
    }

    /**
     * See resource
     * @param AnonymousResourceCollection $collection
     * @param array $except
     * @return TestCase
     */
    public function seeResourceCollection(AnonymousResourceCollection $collection, array $except = [])
    {
        return $this->seeJsonExcept(
            $collection->response($this->app['request'])->getData(true),
            null,
            $except
        );
    }

    /**
     * See json except some values that we don't want to check
     * @param array $expected
     * @param array|null $actual
     * @param array $except
     * @return TestCase
     */
    public function seeJsonExcept(array $expected, array $actual = null, array $except = []): TestCase
    {
        $actual = $actual ?? json_decode($this->response->getContent(), true);

        foreach ($expected as $key => $item) {
            if ($key !== 0 && in_array($key, $except)) {
                continue;
            }

            PHPUnit::assertTrue(
                Arr::has($actual, $key),
                sprintf(
                    'Unable to find JSON fragment [%s] within [%s].',
                    json_encode($expected),
                    json_encode($actual)
                )
            );

            if (is_array($item)) {
                $this->seeJsonExcept($item, $actual[$key], $except);
                continue;
            }

            PHPUnit::assertTrue(
                $actual[$key] === $item,
                sprintf(
                    'Unable to find JSON fragment [%s] within [%s].',
                    json_encode($expected),
                    json_encode($actual)
                )
            );
        }

        return $this;
    }

    /**
     * @return mixed
     */
    private function showName()
    {
        if ($this->model_name) {
            return $this->model_name;
        }
        return str_replace(['app\tests\feature\\', 'test'], ['', ''], strtolower(get_called_class()));
    }

    /**
     * @return string
     */
    private function indexName()
    {
        if ($this->index_name) {
            return $this->index_name;
        }
        return Str::plural($this->showName());
    }

    /**
     * @return string
     */
    private function modelName()
    {
        if ($this->model_name) {
            return $this->model_name;
        }
        return ucfirst($this->showName());
    }

    /**
     * Perform index videos request
     * @param AnonymousResourceCollection $collection
     * @param array $params
     * @param bool $token
     * @param array $tokenParams
     * @param array $except
     * @return array
     */
    public function index(
        AnonymousResourceCollection $collection,
        array $params = [],
        bool $token = false,
        array $tokenParams = [],
        array $except = []
    ): array
    {
        $request = $this->getWithoutCache($this->indexName() . '.index', $params, $token, $tokenParams)
            ->seeStatusCode(200)
            ->seeResourceCollection($collection, $except);

        return $this->content($request);
    }

    /**
     * Pagination test is a little complicated so to some real magic!
     * @param int $paginate
     * @param int $page
     * @param array $params
     * @return array
     */
    public function paginate(int $paginate, int $page = 1, array $params = []): array
    {
        $model = 'App\\' . $this->modelName();
        $collection = '\App\Http\Resources\\' . $this->modelName() . 'Resource';
        $request = $this->getWithoutCache($this->indexName() . '.index', array_merge([
            'paginate' => $paginate,
            'page' => $page,
        ], $params))
            ->seeStatusCode(200)
            ->seeResourceCollection($collection::collection($model::paginate($paginate)));

        return $this->content($request);
    }

    /**
     * Perform show request
     * @param Resource $resource
     * @param array $params
     * @param bool $token
     * @param array $tokenParams
     * @param array $except
     * @return array
     */
    public function show(
        Resource $resource,
        array $params = [],
        bool $token = false,
        array $tokenParams = [],
        array $except = []
    ): array
    {
        $request = $this->getWithoutCache($this->indexName() . '.show', array_merge($params, [
            'id' => $resource->id,
        ]), $token, $tokenParams)
            ->seeStatusCode(200)
            ->seeResource($resource);

        return $this->content($request);
    }

    /**
     * Perform show but cannot find model.
     * @param int $id
     * @param array $params
     */
    public function cantShow(int $id = -1, array $params = [])
    {
        $this->getWithoutCache($this->indexName() . '.show', array_merge($params, [
            'id' => $id,
        ]))
            ->seeStatusCode(404)
            ->seeJson([
                'message' => message('model.notfound', ucfirst($this->showName()), 'ID', $id)
            ]);
    }

    /**
     * Perform store request
     * @param Resource $resource
     * @param array $params
     * @param array $tokenParams
     * @param array $except
     * @return array
     */
    public function store(Resource $resource, array $params = [], array $tokenParams = [], array $except = []): array
    {
        $request = $this->post(route($this->indexName() . '.store'), $params, $this->token($tokenParams))
            ->seeStatusCode(200)
            ->seeResource($resource, $except);

        return $this->content($request);
    }

    /**
     * Perform update request
     * @param Resource $resource
     * @param array $params
     * @param array $tokenParams
     * @return array
     */
    public function update(Resource $resource, array $params = [], array $tokenParams = []): array
    {
        $request = $this->put(route($this->indexName() . '.update', [
            'id' => $resource->id
        ]), $params, $this->token($tokenParams))
            ->seeStatusCode(200)
            ->seeResource($resource);

        return $this->content($request);
    }

    /**
     * Perform update request but cannot update video
     * @param int $id
     * @param array $params
     * @param array $messages
     * @param array $tokenParams
     */
    public function cantUpdate(int $id, array $params = [], array $messages = [], array $tokenParams = [])
    {
        $this->put(route($this->indexName() . '.update', [
            'id' => $id
        ]), $params, $this->token($tokenParams))
            ->seeStatusCode(400)
            ->seeJson($messages);
    }

    /**
     * Destroy Resource
     * @param int $id
     * @param array $tokenParams
     * @param string $route
     * @param bool $notSeeInDatabase
     * @return TestCase
     */
    public function destroy(int $id, array $tokenParams = [], string $route = null, bool $notSeeInDatabase = true): TestCase
    {
        $request = $this->delete($route ?? route($this->indexName() . '.destroy', ['id' => $id]), [], $this->token($tokenParams))
            ->seeStatusCode(200)
            ->seeJson([
                'deleted' => true
            ]);

        if ($notSeeInDatabase) {
            $this->notSeeInDatabase($this->indexName(), [
                'id' => $id
            ]);
        }

        return $request;
    }

    /**
     * Cant destroy Resource Unauthorized
     * @param int $id
     * @param string $message
     * @param int $status
     * @return TestCase
     */
    public function cantDestroy(int $id, string $message, int $status = 401): TestCase
    {
        return $this->delete(route($this->indexName() . '.destroy', ['id' => $id]), [], $this->token())
            ->seeStatusCode($status)
            ->seeJson([
                'message' => $message
            ]);
    }

    /**
     * Predict store id
     * @param Collection $collection
     * @return int
     */
    public function predictId(Collection $collection): int
    {
        $last = $collection->last();
        return $last ? $last->id + 1 : 1;
    }
}
