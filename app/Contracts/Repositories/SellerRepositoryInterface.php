<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface SellerRepositoryInterface extends RepositoryInterface
{
    public function getListWithScope(array $orderBy = [], string $searchValue = null, string $scope = null, array $filters = [], array $whereIn = [], array $whereNotIn = [], array $relations = [], array $withCount = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;
}
