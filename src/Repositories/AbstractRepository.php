<?php

namespace Tychovbh\Mvc\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Model $model
 */
abstract class AbstractRepository
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var Builder
     */
    protected $query;

    /**
     * AbstractRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->model = model(get_called_class());
    }

    /**
     * Retrieve a collection.
     * @return Collection
     */
    public function all(): Collection
    {
        if (!$this->params) {
            return $this->model::all();
        }

        return $this->applyIndexParams()->get([$this->model->getTable() . '.*']);
    }

    /**
     * Apply index params
     * @return Builder
     */
    private function applyIndexParams(): Builder
    {
        return $this->applyParams('index');
    }

    /**
     * Apply show params
     * @return Builder
     */
    private function applyShowParams(): Builder
    {
        return $this->applyParams('show');
    }

    /**
     * @param string $type
     * @return Builder
     */
    private function applyParams(string $type): Builder
    {
        $this->query = $this->model::query();
        foreach ($this->params as $param => $value) {
            $method = str_replace('_', ' ', $param);
            $method = ucwords($method);
            $method = str_replace(' ', '', $method);
            $method = $type . $method . 'Param';

            if (method_exists($this, $method)) {
                $this->{$method}($value);
                continue;
            }


            is_array($value) ? $this->query->whereIn($param, $value) : $this->query->where($param, $value);
        }

        $this->params = [];
        return $this->query;
    }

    /**
     * Add filter params before retrieving data.
     * @param array $params
     * @return Repository
     */
    public function params(array $params): Repository
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Retrieve a paginated collection
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function paginate(int $paginate): LengthAwarePaginator
    {
        if (!$this->params) {
            return $this->model::paginate($paginate);
        }

        return $this->applyIndexParams()->paginate($paginate, [$this->model->getTable() . '.*']);
    }

    /**
     * Find a resource by ID
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        if (!$this->params) {
            return $this->model::findOrFail($id);
        }

        return $this->applyShowParams()->where('id', $id)->firstOrFail();
    }

    /**
     * Find a resource by key
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function findBy(string $key, string $value)
    {
        return $this->model::where($key, $value)->firstOrFail();
    }

    /**
     * Save new resource.
     * @param array $data
     * @return mixed
     */
    public function save(array $data)
    {
        return $this->model::create($data);
    }

    /**
     * Update existing resource.
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id)
    {
        $model = $this->find($id);
        $model->fill($data);
        $model->save();
        return $model;
    }

    /**
     * Destroy resources
     * @param array $ids
     * @return bool
     */
    public function destroy(array $ids): bool
    {
        return $this->model::destroy($ids);
    }

    /**
     * Add param random
     * @param $value
     */
    public function indexRandomParam($value)
    {
        $this->query->orderByRaw('RAND()');
    }

    /**
     * Add param sort
     * @param string $value
     */
    public function indexSortParam(string $value)
    {
        $sort = explode(' ', $value);
        $this->query->orderBy(...$sort);
    }
}

