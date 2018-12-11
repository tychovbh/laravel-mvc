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
        return $this->model::all();
    }

    /**
     * Start where query.
     * @param array $filters
     * @param string $select
     * @return Builder
     */
    public function where(array $filters, string $select = '*'): Builder
    {
        $query = $this->model::select($select);

        foreach ($filters as $filter => $value) {
            if (is_array($value)) {
                $query->whereIn($filter, $value);
                continue;
            }
            $query->where($filter, $value);
        }

        return $query;
    }

    /**
     * Retrieve a paginated collection
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function paginate(int $paginate): LengthAwarePaginator
    {
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

