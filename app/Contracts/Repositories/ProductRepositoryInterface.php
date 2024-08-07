<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 *
 */
interface ProductRepositoryInterface extends RepositoryInterface
{

    /**
     * @param array $params
     * @param array $relations
     * @return Model|null
     */
    public function getFirstWhereActive(array $params, array $relations = []): ?Model;

    /**
     * @param array $params
     * @param array $relations
     * @param array $withCount
     * @return Model|null
     */
    public function getWebFirstWhereActive(array $params, array $relations = [], array $withCount = []): ?Model;

    /**
     * @param array $params
     * @param array $relations
     * @return Model|null
     */
    public function getFirstWhereWithoutGlobalScope(array $params, array $relations = []): ?Model;

    /**
     * @param array $orderBy
     * @param string|null $searchValue
     * @param array $filters
     * @param array $withCount
     * @param array $relations
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getStockLimitListWhere(array $orderBy=[], string $searchValue = null, array $filters = [], array $withCount = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;

    /**
     * @param array $data
     * @return bool
     */
    public function addArray(array $data): bool;


    /**
     * @param array $filters
     * @param array $whereNotIn
     * @param array $relations
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getListWhereNotIn(array $filters = [], array $whereNotIn = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;

    /**
     * @param array $orderBy
     * @param string|null $searchValue
     * @param string|null $scope
     * @param array $filters
     * @param array $whereIn
     * @param array $whereNotIn
     * @param array $relations
     * @param array $withCount
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getListWithScope(array $orderBy = [], string $searchValue = null, string $scope = null, array $filters = [], array $whereIn = [], array $whereNotIn = [], array $relations = [], array $withCount = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;

    /**
     * @param array $orderBy
     * @param string|null $searchValue
     * @param string|null $scope
     * @param array $filters
     * @param array $whereHas
     * @param array $whereIn
     * @param array $whereNotIn
     * @param array $relations
     * @param array $withCount
     * @param array $withSum
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getWebListWithScope(array $orderBy = [], string $searchValue = null, string $scope = null, array $filters = [], array $whereHas = [], array $whereIn = [], array $whereNotIn = [], array $relations = [], array $withCount = [], array $withSum = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;

    /**
     * @param array $filters
     * @param array $relations
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getTopRatedList(array $filters = [] , array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;

    /**
     * @param array $filters
     * @param array $relations
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getTopSellList(array $filters = [] ,array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator ;

    /**
     * @param array $params
     * @param array $withCount
     * @param array $relations
     * @return Model|null
     */
    public function getFirstWhereWithCount(array $params, array $withCount = [] , array $relations = []): ?Model;

    /**
     * @param array $filters
     * @return Collection|array
     */
    public function getProductIds(array $filters = []): \Illuminate\Support\Collection|array;

    public function updateByParams(array $params, array $data): bool;

}
