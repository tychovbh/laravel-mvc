<?php

namespace Tychovbh\Mvc\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Model $model
 * @property string name
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
     * @var array
     */
    protected $joins = [];

    /**
     * AbstractRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->model = model(get_called_class());
        $this->name = $this->model->getTable();
    }

    /**
     * Join tables in param methods, without conflicting when already joined.
     * @param string $table
     * @param string $first
     * @param string $second
     */
    public function join(string $table, string $first, string $second)
    {
        if (!in_array($table, $this->joins)) {
            $this->joins[] = $table;
            $this->query->join($table, $first, $second);
        }
    }

    /**
     * Retrieve a collection.
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model::all();
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

            if (has_column($this->model, $param)) {
                $key = $this->name . '.' . $param;
                is_array($value) ? $this->query->whereIn($key, $value) : $this->query->where($key, $value);
            }
        }

        $this->params = [];
        $this->query->groupBy($this->name . '.id');
        return $this->query;
    }

    /**
     * Add filter params before retrieving data.
     * @param array $params
     * @return Repository
     * @throws \Exception
     */
    public static function withParams(array $params): Repository
    {
        $repository = new static();
        $repository->params = $params;

        return $repository;
    }

    /**
     * Get param filtered Collection.
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->applyParams('index')->get([$this->name . '.*']);
    }

    /**
     * Get param filtered first item.
     * @return Model
     */
    public function first()
    {
        return $this->applyParams('show')->firstOrFail([$this->name . '.*']);
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

        return $this->applyParams('index')->paginate($paginate, [$this->name . '.*']);
    }

    /**
     * Find a resource by ID
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->model::findOrFail($id);
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

