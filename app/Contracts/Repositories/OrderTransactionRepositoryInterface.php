<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderTransactionRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $filters
     * @param string|null $whereBetween
     * @param string|null $selectColumn
     * @param array $whereBetweenFilters
     * @param array $relations
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getListWhereBetween(array $filters = [], string $whereBetween = null, string $selectColumn = null,array $whereBetweenFilters = [] , array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null):  Collection|LengthAwarePaginator;

    /**
     * @param string|null $sellerIs
     * @param array $dataRange
     * @param array $groupBy
     * @param int $dateStart
     * @param int $dateEnd
     * @return array
     */
    public function getCommissionEarningStatisticsData(string $sellerIs = null, array $dataRange = [], array $groupBy = [], int $dateStart = 1, int $dateEnd = 12): array;

    /**
     * @param string|null $sellerIs
     * @param array $dataRange
     * @param array $groupBy
     * @param int $dateStart
     * @param int $dateEnd
     * @return array
     */
    public function getEarningStatisticsData(string $sellerIs = null, array $dataRange = [], array $groupBy = [], int $dateStart = 1, int $dateEnd = 12): array;
}
