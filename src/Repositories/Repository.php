<?php

namespace Tychovbh\Mvc\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface Repository
{
    /**
     * Retrieve a collection.
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Retrieve a paginated collection.
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function paginate(int $paginate): LengthAwarePaginator;

    /**
     * Add select to query.
     * @param string $select
     * @return Repository
     */
    public function select(string $select = '*'): Repository;

    /**
     * Add where to query.
     * @param array $filters
     * @return Repository
     */
    public function where(array $filters): Repository;

    /**
     * Add order by to query.
     * @param string $key
     * @param string $direction
     * @return Repository
     */
    public function orderBy(string $key, string $direction = 'asc'): Repository;

    /**
     * Find a resource by ID.
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * Find a resource by key.
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
     * Destroy resources.
     * @param array $ids
     * @return bool
     */
    public function destroy(array $ids): bool;
}

