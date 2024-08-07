<?php
namespace App\Contracts\Repositories;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


interface OrderRepositoryInterface extends RepositoryInterface
{
    /**
     * @param string $addedBy
     * @param string|null $searchValue
     * @param array $filters Filters value must be in key and value pair structure, support one level nested array, ex: Filters = ['category'=>[1,2,5,8], 'email'=>['x@x.com','test@test.com']]
     * @param array $relations
     * @param int|string $dataLimit If you need all data without pagination, you need to set dataLimit = 'all'
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getDeliveryManOrderListWhere(string $addedBy, string $searchValue = null, array $filters = [] , array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;

    /**
     * @param array $params
     * @param array $data
     * @return bool
     */
    public function updateWhere(array $params, array $data): bool;


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
     * @param string|null $searchValue
     * @param array $filters
     * @param array $relations
     * @return int
     */
    public function getListWhereCount(string $searchValue = null, array $filters = [], array $relations = []): int;


    /**
     * @param object $request
     * @param string|int $userId
     * @param string $userType
     * @return bool
     */
    public function updateAmountDate(object $request, string|int $userId, string $userType): bool;

    /**
     * @param array $filters
     * @param string|null $dateType
     * @param array $filterDate
     * @param array $relations
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getListWhereDate(array $filters = [], string $dateType = null, array $filterDate = [] , array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator ;


    /**
     * @param string|int $orderId
     * @param string $status
     * @return bool
     */
    public function updateStockOnOrderStatusChange(string|int $orderId, string $status): bool;

    /**
     * @param object $order
     * @param string $receivedBy
     * @return bool
     */
    public function manageWalletOnOrderStatusChange(object $order, string $receivedBy): bool;

    /**
     * @param array $filters
     * @param array $relations
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getTopCustomerList(array $filters = [] , array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;
    public function getListWhereBetween(array $filters = [], string $selectColumn = null, string $whereBetween = null, array $whereBetweenFilters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;

}
