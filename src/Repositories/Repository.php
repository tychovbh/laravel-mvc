<?php

namespace Tychovbh\Mvc\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
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
     * Start where query.
     * @param array $params
     * @return Repository
     */
    public function params(array $params) : Repository;

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

