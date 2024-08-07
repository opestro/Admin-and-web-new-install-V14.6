<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DeliveryManRepositoryInterface extends RepositoryInterface
{

    /**
     * @param array $filters
     * @param array $whereHasFilters
     * @param array $relations
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getTopRatedList(array$orderBy = [] ,array $filters = [], array $whereHasFilters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;


    /**
     * @param array $orderBy
     * @param string|null $searchValue
     * @param array $filters
     * @param array $relations
     * @param array $nullFields
     * @param array $withCounts
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getListWhereIn(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], array $nullFields = [], array $withCounts = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;
}
