<?php

namespace App\Repositories;

use App\Contracts\Repositories\CustomerWalletRepositoryInterface;
use App\Models\CustomerWallet;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerWalletRepository implements CustomerWalletRepositoryInterface
{
    public function __construct(
        private readonly CustomerWallet           $customerWallet,
    )
    {
    }


    public function add(array $data): string|object
    {
        return $this->customerWallet->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->customerWallet->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getListWhere() method.
    }

    public function update(string $id, array $data): bool
    {
        // TODO: Implement update() method.
    }

    public function delete(array $params): bool
    {
        $this->customerWallet->where($params)->delete();
        return true;
    }
}
