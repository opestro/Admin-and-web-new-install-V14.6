<?php

namespace App\Contracts\Repositories;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RefundRequestRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $orderBy
     * @param string|null $searchValue
     * @param array $filters
     * @param string|null $whereHas
     * @param array $whereHasFilters
     * @param array $relations
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getListWhereHas(array $orderBy = [], string $searchValue = null, array $filters = [], string $whereHas = null, array $whereHasFilters = [] , array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;

    /**
     * @param array $params
     * @param string|null $whereHas
     * @param array $whereHasFilters
     * @param array $relations
     * @return Model|null
     */
    public function getFirstWhereHas(array $params, string $whereHas = null, array $whereHasFilters = [], array $relations = []): ?Model;

}
