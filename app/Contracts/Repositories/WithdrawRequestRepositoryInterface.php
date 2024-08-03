<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface WithdrawRequestRepositoryInterface extends RepositoryInterface
{

    /**
     * @param array $orderBy
     * @param string|null $searchValue
     * @param array $filters
     * @param array $nullFilters
     * @param array $relations
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getListWhereNull(array $orderBy=[], string $searchValue = null, array $filters = [], array $nullFilters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;


    /**
     * @param array $params
     * @param array $filters
     * @param array $orderBy
     * @param array $relations
     * @return Model|null
     */
    public function getFirstWhereNotNull(array $params, array $filters = [], array $orderBy = [], array $relations = []): ?Model;
}
