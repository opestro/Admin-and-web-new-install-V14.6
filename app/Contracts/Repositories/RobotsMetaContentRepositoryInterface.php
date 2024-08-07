<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RobotsMetaContentRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $orderBy
     * @param string|null $searchValue
     * @param array $filters
     * @param array $relations
     * @param array $nullFields
     * @param array $whereNotIn
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getListWhereNotIn(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], array $nullFields = [], array $whereNotIn = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;

    /**
     * @param array $params
     * @param array $data
     * @return bool
     */
    public function updateByParams(array $params, array $data): bool;

    /**
     * @param array $params
     * @param array $data
     * @return bool
     */
    public function updateOrInsert(array $params, array $data): bool;
}
