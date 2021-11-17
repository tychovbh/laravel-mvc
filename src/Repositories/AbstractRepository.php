<?php

namespace Tychovbh\Mvc\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * @var int
     */
    protected $limit;

    /**
     * @var array
     */
    protected $select;

    /**
     * @var string
     */
    protected $groupBy;

    /**
     * @var Model
     */
    public $model;

    /**
     * @var string
     */
    public $name;

    /**
     * AbstractRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->model = $this->model ?? model(get_called_class());
        $this->name = $this->model->getTable();
    }

    /**
     * Join tables in param methods, without conflicting when already joined.
     * @param string $table
     * @param string $first
     * @param string $second
     * @param string type
     */
    public function join(string $table, string $first, string $second, string $type = '')
    {
        if (!in_array($table, $this->joins)) {
            $this->joins[] = $table;
            $method = $type ? $type . 'Join' : 'join';
            $this->query->{$method}($table, $first, $second);
        }
    }

    /**
     * Left join
     * @param string $table
     * @param string $first
     * @param string $second
     */
    public function leftJoin(string $table, string $first, string $second)
    {
        $this->join($table, $first, $second, 'left');
    }

    /**
     * Right join
     * @param string $table
     * @param string $first
     * @param string $second
     */
    public function rightJoin(string $table, string $first, string $second)
    {
        $this->join($table, $first, $second, 'right');
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

            if (!has_column($this->model, $param)) {
                continue;
            }

            $key = $this->name . '.' . $param;

            if ($value === null || $value === 'null') {
                $this->query->whereNull($key);
                continue;
            }

            is_array($value) ? $this->query->whereIn($key, $value) : $this->query->where($key, $value);
        }

        $this->params = [];
        $this->query->groupBy($this->groupBy ?? $this->name . '.id');
        if ($this->limit) {
            $this->query->limit($this->limit);
        }

        if ($this->select) {
            $this->query->select($this->select);
        }
        return $this->query;
    }

    /**
     * Add filter params before retrieving data.
     * @param array $params
     * @return Repository
     * @throws \Exception
     */
    public static function withParams(array $params = []): Repository
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
     * Set query limit
     * @param int $limit
     * @return Repository
     */
    public function limit(int $limit): Repository
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Set query select
     * @param array $select
     * @return Repository
     */
    public function select(array $select): Repository
    {
        $this->select = $select;
        return $this;
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
     * @param string|int $value
     * @return mixed
     */
    public function findBy(string $key, $value)
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
     * Save new resource when not found or update existing resource.
     * @param string $field
     * @param string $search
     * @param array $data
     * @return mixed
     */
    public function saveOrUpdate(string $field, string $search, array $data = [])
    {
        try {
            $property = $this->findBy($field, $search);
            return $this->update($data, $property->id);
        } catch (ModelNotFoundException $exception) {
            return $this->save($data);
        }
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
     */
    public function indexRandomParam()
    {
        $this->query->orderByRaw('RAND()');
    }

    /**
     * Add param sort
     * @param string|array $value
     */
    public function indexSortParam($value)
    {
        if (is_string($value)) {
            $sort = explode(' ', $value);
            $this->query->orderBy(...$sort);
        }

        if (is_array($value)) {
            foreach ($value as $order) {
                $sort = explode(' ', $order);
                $this->query->orderBy(...$sort);
            }
        }
    }

    /**
     * Filter users on from created_at
     * @param string $from
     */
    public function indexFromParam(string $from)
    {
        $this->query->where($this->name . '.created_at', '>=', $from);
    }

    /**
     * Filter users on till created_at
     * @param string $till
     */
    public function indexTillParam(string $till)
    {
        $this->query->where($this->name . '.created_at', '<=', $till);
    }
}
