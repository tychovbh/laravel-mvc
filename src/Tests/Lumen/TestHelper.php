<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Tests\Lumen;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Lumen\Testing\TestCase;
use PHPUnit\Framework\Assert as PHPUnit;

trait TestHelper
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
     * @param array $tokenParams
     * @return TestCase
     */
    public function getWithToken(string $route, array $params = [], array $tokenParams = []): TestCase
    {
        return $this->get(route($route, $params), $this->token($tokenParams));
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
     * @param JsonResource $resource
     * @param array $except
     * @return TestCase
     */
    public function seeResource(JsonResource $resource, array $except = [])
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
     * see if collection in database
     * @param string $table
     * @param array $collection
     */
    public function seeCollectionInDatabase(string $table, array $collection)
    {
        foreach ($collection as $rowInDatabase) {
            $this->seeInDatabase($table, $rowInDatabase);
        }
    }

    /**
     * @return mixed
     */
    private function showName()
    {
        if ($this->model_name) {
            return $this->model_name;
        }

        $class = explode('\\', strtolower(str_replace('Test', '', get_called_class())));
        return array_pop($class);
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
            ->seeResourceCollection($collection, $except)
            ->seeStatusCode(200);

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
            ->seeResourceCollection($collection::collection($model::paginate($paginate)))
            ->seeStatusCode(200);

        return $this->content($request);
    }

    /**
     * Perform show request
     * @param JsonResource $resource
     * @param array $params
     * @param bool $token
     * @param array $tokenParams
     * @param array $except
     * @return array
     */
    public function show(
        JsonResource $resource,
        array $params = [],
        bool $token = false,
        array $tokenParams = [],
        array $except = []
    ): array
    {
        $request = $this->getWithoutCache($this->indexName() . '.show', array_merge($params, [
            'id' => $resource->id,
        ]), $token, $tokenParams)
            ->seeResource($resource)
            ->seeStatusCode(200);

        return $this->content($request);
    }

    /**
     * Perform show but cannot find model.
     * @param int $id
     * @param array $params
     * @param bool $token
     * @param array $tokenParams
     */
    public function cantShow(int $id = -1, array $params = [], bool $token = false, array $tokenParams = [])
    {
        $this->getWithoutCache($this->indexName() . '.show', array_merge($params, [
            'id' => $id,
        ]), $token, $tokenParams)
            ->seeJson([
                'message' => message('model.notfound', ucfirst($this->showName()), 'ID', $id)
            ])
            ->seeStatusCode(404);
    }

    /**
     * Perform store request
     * @param JsonResource $resource
     * @param array $params
     * @param array $tokenParams
     * @param array $except
     * @return array
     */
    public function store(JsonResource $resource, array $params = [], array $tokenParams = [], array $except = []): array
    {
        $request = $this->post(route($this->indexName() . '.store'), $params, $this->token($tokenParams))
            ->seeResource($resource, $except)
            ->seeStatusCode(200);

        return $this->content($request);
    }

    /**
     * Perform store request but cannot store model
     * @param array $params
     * @param array $messages
     * @param array $tokenParams
     */
    public function cantStore(array $params = [], array $messages = [], array $tokenParams = [])
    {
        $this->post(route($this->indexName() . '.store'), $params, $this->token($tokenParams))
            ->seeJson($messages)
            ->seeStatusCode(400);
    }

    /**
     * Perform update request
     * @param JsonResource $resource
     * @param array $params
     * @param array $tokenParams
     * @param array $except
     * @return array
     */
    public function update(JsonResource $resource, array $params = [], array $tokenParams = [], array $except = []): array
    {
        $request = $this->put(route($this->indexName() . '.update', [
            'id' => $resource->id
        ]), $params, $this->token($tokenParams))
            ->seeResource($resource, $except)
            ->seeStatusCode(200);

        return $this->content($request);
    }

    /**
     * Perform update request but cannot update model
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
            ->seeJson($messages)
            ->seeStatusCode(400);
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
            ->seeJson([
                'deleted' => true
            ])
            ->seeStatusCode(200);

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
            ->seeJson([
                'message' => $message
            ])
            ->seeStatusCode($status);

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
