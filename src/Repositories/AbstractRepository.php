<?php

namespace Tychovbh\Mvc\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property $model
 */
abstract class AbstractRepository
{
    /**
     * @var Builder
     */
    private $query;

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
        if ($this->query) {
            $query = $this->query;
            $this->query = null;
            return $query->get();
        }
        return $this->model::all();
    }

    /**
     * Add select to query.
     * @param string $select
     * @return Repository
     */
    public function select(string $select = '*'): Repository
    {
        $this->query = $this->model::select($select);
        return $this;
    }

    /**
     * Add where to query.
     * @param array $filters
     * @return Repository
     */
    public function where(array $filters): Repository
    {
        if (!$this->query) {
            $this->select();
        }

        foreach ($filters as $filter => $value) {
            if (is_array($value)) {
                $this->query->whereIn($filter, $value);
                continue;
            }
            $this->query->where($filter, $value);
        }

        return $this;
    }

    /**
     * Add order by to query.
     * @param string $key
     * @param string $direction
     * @return Repository
     */
    public function orderBy(string $key, string $direction = 'asc'): Repository
    {
        if (!$this->query) {
            $this->select();
        }
        $this->query->orderBy($key, $direction);
        return $this;
    }

    /**
     * Retrieve a paginated collection
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function paginate(int $paginate): LengthAwarePaginator
    {
        if ($this->query) {
            $query = $this->query;
            $this->query = null;
            return $query->paginate($paginate);
        }

        return $this->model::paginate($paginate);
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
}

