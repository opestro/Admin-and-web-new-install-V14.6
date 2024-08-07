<?php

namespace App\Repositories;

use App\Contracts\Repositories\DeliveryManWalletRepositoryInterface;
use App\Models\DeliveryManTransaction;
use App\Models\DeliverymanWallet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class DeliveryManWalletRepository implements DeliveryManWalletRepositoryInterface
{

    public function __construct(
        private readonly DeliverymanWallet $deliveryManWallet,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->deliveryManWallet->newInstance()->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->deliveryManWallet->where($params)->first();
    }

    public function getList(array $orderBy=[] ,array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [],string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getListWhere() method.
    }

    public function update(string $id, array $data): bool
    {
        return $this->deliveryManWallet->where('id', $id)->update($data);
    }

    public function delete(array $params): bool
    {
        $this->deliveryManWallet->where($params)->delete();
        return true;
    }

    public function updateWhere(array $params, array $data): bool
    {
        return $this->deliveryManWallet->where($params)->update($data);
    }
}
