<?php

namespace Tychovbh\Mvc\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Tychovbh\Mvc\Exceptions\Handler;
use Tychovbh\Mvc\Http\Resources\FormResource;
use Tychovbh\Mvc\Repositories\FormRepository;
use Tychovbh\Mvc\Repositories\Repository;

/**
 * Class Controller
 * @package Tychovbh\Mvc\Http\Controllers
 */
abstract class AbstractController implements ControllerInterface
{
    /**
     * @var Repository
     */
    public $repository;

    /**
     * @var String
     */
    public $controller;

    /**
     * @var string
     */
    public $resource;

    /**
     * AbstractController constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $request = app('request');

        App::singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            Handler::class
        );

        $this->repository = $this->setRepository($request);
        $this->resource = $this->setResource($request);
        $this->setModel($request);
        $this->controller = controller(get_called_class());
    }

    /**
     * Set repository
     * @return Repository
     * @throws Exception
     */
    private function setRepository($request): Repository
    {
        $repository = get_route_info($request, 'repository');
        if ($repository) {
            return new $repository;
        }

        return $this->repository ?? repository(get_called_class());
    }

    /**
     * Set repository model
     */
    private function setModel($request)
    {
        $model = get_route_info($request, 'model');

        if ($model) {
            $this->repository->model = new $model;
        }
    }

    /**
     * Set resource
     * @return string
     * @throws Exception
     */
    private function setResource($request): string
    {
        $resource = get_route_info($request, 'resource');
        return $resource ?? $this->resource ?? resource(get_called_class());
    }

    /**
     * List all resources.
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = $this->repository::withParams($request->toArray());

        return $this->resource::collection($request->has('paginate') ?
            $query->paginate((int)$request->get('paginate')) :
            $query->get());
    }

    /**
     * Show User Resource.
     * @param Request $request
     * @param int $id
     * @return JsonResource
     */
    public function show(Request $request, int $id): JsonResource
    {
        $params = $request->toArray();
        try {
            if ($params) {
                return new $this->resource($this->repository::withParams(array_merge($params, ['id' => $id]))->first());
            }
            return new $this->resource($this->repository->find($id));
        } catch (ModelNotFoundException $exception) {
            abort(404, message('model.notfound', ucfirst($this->controller), 'ID', $id));
        }
    }

    /**
     * Store new Resource.
     * @param Request $request
     * @return JsonResource
     */
    public function store(Request $request): JsonResource
    {
        $model = $this->repository->save($request->toArray());
        return new $this->resource($model);
    }

    /**
     * Update existing Resource.
     * @param Request $request
     * @param int $id
     * @return JsonResource
     */
    public function update(Request $request, int $id): JsonResource
    {
        try {
            $model = $this->repository->update($request->toArray(), $id);
            return new $this->resource($model);
        } catch (QueryException $exception) {
            error($exception->getMessage(), [
                'method' => __METHOD__,
                'id' => $id,
                'request' => $request->toArray(),
            ]);
            abort(400, message('model.invalid'));
        } catch (Exception $exception) {
            error($exception->getMessage(), [
                'method' => __METHOD__,
                'id' => $id,
                'request' => $request->toArray(),
            ]);
            abort(500, message('server.error'));
        }
    }

    /**
     * Destroy Resource.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return response()->json([
            'deleted' => $this->repository->destroy([$id])
        ]);
    }

    /**
     * Return the form.
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function create(Request $request)
    {
        $forms = new FormRepository();
        $form = $forms->findBy('name', $request->has('name') ? $request->get('name') : $this->repository->name);
        return new FormResource($form);
    }
}

