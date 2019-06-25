<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Tychovbh\Mvc\Form;
use Tychovbh\Mvc\Http\Resources\FormResource;
use Tychovbh\Mvc\Repositories\FormRepository;

/**
 * Trait Rest
 * @property FormRepository forms
 * @package Tychovbh\Mvc\Http\Controllers
 */
trait Rest
{
    /**
     * @var \Tychovbh\Mvc\Repositories\Repository
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
     * @throws \Exception
     */
    public function __construct()
    {
        $this->repository = $this->repository ?? repository(get_called_class());
        $this->resource = $this->resource ?? resource(get_called_class());
        $this->controller = controller(get_called_class());
    }

    /**
     * List all resources.
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = $this->repository::withParams($request->toArray());

        return $this->resource::collection($request->has('paginate') ? $query->paginate((int)$request->get('paginate')) : $query->get());
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
        } catch (\Exception $exception) {
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
     * @return mixed
     * @throws \Exception
     */
    public function create()
    {
        $this->forms = new FormRepository();
        $table_name = explode('_', $this->controller)[1] . 's';

        $form = $this->forms->findBy('table', $table_name);
        return new FormResource($form);
    }
}
