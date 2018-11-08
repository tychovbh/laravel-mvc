<?php

namespace Tychovbh\Mvc\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface Repository
{
    /**
     * Retrieve a collection.
     * @param array $filters
     * @return Collection
     */
    public function all(array $filters = []): Collection;

    /**
     * Retrieve a paginated collection
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function paginate(int $paginate) : LengthAwarePaginator;

    /**
     * Find a resource by ID
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * Find a resource by key
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function findBy(string $key, string $value);

    /**
     * Save new resource.
     * @param array $data
     * @return mixed
     */
    public function save(array $data);

    /**
     * Update existing resource.
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id);

    /**
     * Destroy resources
     * @param array $ids
     * @return bool
     */
    public function destroy(array $ids) : bool;
}

