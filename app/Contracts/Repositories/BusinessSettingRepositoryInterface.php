<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BusinessSettingRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $orderBy
     * @param string|null $searchValue
     * @param array $filters
     * @param array $whereInFilters
     * @param array $relations
     * @param array $nullFields
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getListWhereIn(array $orderBy = [], string $searchValue = null, array $filters = [], array $whereInFilters = [], array $relations = [], array $nullFields = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;

    /**
     * @param string $type
     * @param mixed $value
     * @return bool
     */
    public function updateOrInsert(string $type, mixed $value): bool;

    /**
     * @param array $params
     * @param array $data
     * @return bool
     */
    public function updateWhere(array $params, array $data): bool;

    /**
     * @param array $params
     * @param array $value
     * @return Model|null
     */
    public function whereJsonContains(array $params, array $value): ?Model;
}
