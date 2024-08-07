<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface LoyaltyPointTransactionRepositoryInterface extends RepositoryInterface
{

    /**
     * @param array $orderBy
     * @param string|null $searchValue
     * @param array $filters
     * @param array $relations
     * @param int|string $dataLimit
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getListWhereSelect(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;

    public function addLoyaltyPointTransaction(string|int $userId, string $reference, string|int|float $amount, string $transactionType): bool;
}
