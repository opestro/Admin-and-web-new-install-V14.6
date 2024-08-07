<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BannerRepositoryInterface extends RepositoryInterface
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
}
