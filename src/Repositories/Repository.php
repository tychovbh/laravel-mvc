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
     * Set Params.
     * @param array $params
     */
    public function setParams(array $params);

    /**
     * Start query with params.
     * @param array $params
     * @return Repository
     */
    public static function withParams(array $params = []): Repository;

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
     * Count a collection.
     * @return int
     */
    public function count(): int;

    /**
     * Set query limit
     * @param int $limit
     * @return Repository
     */
    public function limit(int $limit): Repository;

    /**
     * Set query group by
     * @param string $groupBy
     * @return Repository
     */
    public function groupBy(string $groupBy): Repository;

    /**
     * Disable query group by
     * @return Repository
     */
    public function disableGroupBy(): Repository;

    /**
     * Set query select
     * @param array $select
     * @return Repository
     */
    public function select(array $select): Repository;

    /**
     * Retrieve first resource.
     * @return mixed
     */
    public function first();

    /**
     * Find a resource by key.
     * @param string $key
     * @param string|int $value
     * @return mixed
     */
    public function findBy(string $key, $value);

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

