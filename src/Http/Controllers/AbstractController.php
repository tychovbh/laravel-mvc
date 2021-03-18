<?php

namespace Tychovbh\Mvc\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Tychovbh\Mvc\Http\Resources\FormResource;
use Tychovbh\Mvc\Models\Wildcard;
use Tychovbh\Mvc\Repositories\FormRepository;
use Tychovbh\Mvc\Repositories\Repository;
use Tychovbh\Mvc\Repositories\TableRepository;

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
        $this->setDatabase($request);

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
     * Set default database.
     * @param $request
     */
    private function setDatabase($request)
    {
        $database = get_route_info($request, 'database');
        if ($database) {
            config(['database.default' => $database]);
        }
    }

    /**
     * List all resources.
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $response = $this->resource::collection(
            $this->collection($request, $this->repository::withParams($request->toArray()))
        );

        $this->additional($request, $response);

        return $response;
    }

    /**
     * Add Additional response data
     * @param Request $request
     * @param $response
     */
    private function additional(Request $request, $response)
    {
        $additionals = $request->get('additionals', '');
        $additionals = is_array($additionals) ? $additionals : [$additionals];
        $data = [];

        foreach($additionals as $additional) {
            if (method_exists($this->repository->model, $additional)) {
                $data[$additional] = $this->repository->model::{$additional}($request);
            }
        }

        if ($data) {
            $response->additional($data);
        }
    }

    /**
     * @param Request $request
     * @param Repository $repository
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    private function collection(Request $request, Repository $repository)
    {
        if ($request->has('paginate')) {
            return $repository->paginate((int)$request->get('paginate'));
        }

        return $repository->get();
    }

    /**
     * Show User Resource.
     * @param Request $request
     * @param string|int $id
     * @return JsonResource
     */
    public function show(Request $request, $id): JsonResource
    {
        $params = $request->toArray();
        try {
            if ($params) {
                $response = new $this->resource($this->repository::withParams(array_merge($params, ['id' => $id]))->first());
            } else {
                $response = new $this->resource($this->repository->find($id));
            }

            $this->additional($request, $response);

            return $response;
        } catch (ModelNotFoundException $exception) {
            abort(404, message('model.notfound', ucfirst($this->controller), 'ID', $id));
        }
    }

    /**
     * Create form.
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $table = $this->repository->model->getTable();

        try {

            $table = TableRepository::withParams(array_merge(['name' => $table], $request->toArray()))->first();
            return response()->json([
                'data' => $table->create_form
            ]);
        } catch (ModelNotFoundException $exception) {
            abort(404, message('table.notfound', ucfirst($this->controller), 'ID', $table));
        }
    }

    /**
     * Edit form.
     * @param Request $request
     * @param string|int $id
     * @return JsonResponse
     */
    public function edit(Request $request, $id): JsonResponse
    {
        $table = $this->repository->model->getTable();
        $show = $this->show($request, $id);

        try {
            $table = TableRepository::withParams(array_merge(['name' => $table], $request->toArray()))->first();
            $form = $table->edit_form;
            $form['route'] = Str::replaceFirst('id', $id, $form['route']);
            $form['defaults'] = $show;

            return response()->json([
                'data' => $form
            ]);
        } catch (ModelNotFoundException $exception) {
            abort(404, message('table.notfound', ucfirst($this->controller), 'ID', $table));
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
     * @param string|int $id
     * @return JsonResource
     */
    public function update(Request $request, $id): JsonResource
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
     * @param string|int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        return response()->json([
            'deleted' => $this->repository->destroy([$id])
        ]);
    }
}

