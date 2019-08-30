<?php

namespace Tychovbh\Mvc\Repositories;

use Chelout\OffsetPagination\OffsetPaginator;
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
     * Retrieve an offset paginated collection.
     * @param int $paginate
     * @return OffsetPaginator
     */
    public function offsetPaginate(int $paginate): OffsetPaginator;

    /**
     * Start query with params.
     * @param array $params
     * @return Repository
     */
    public static function withParams(array $params) : Repository;

    /**
     * Find a resource by ID.
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * Retrieve a collection.
     * @return Collection
     */
    public function get(): Collection;

    /**
     * Retrieve first resource.
     * @return mixed
     */
    public function first();

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
     * Save new resource when not found or update existing resource.
     * @param string $field
     * @param string $search
     * @param array $data
     */
    public function saveOrUpdate(string $field, string $search, array $data = []);

    /**
     * Destroy resources.
     * @param array $ids
     * @return bool
     */
    public function destroy(array $ids): bool;
}

