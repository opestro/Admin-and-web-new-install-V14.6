<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderStatusHistoryRepositoryInterface;
use App\Models\OrderStatusHistory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderStatusHistoryRepository implements OrderStatusHistoryRepositoryInterface
{
    public function __construct(private readonly OrderStatusHistory $orderStatusHistory)
    {
    }

    public function add(array $data): string|object
    {
        return $this->orderStatusHistory->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        // TODO: Implement getFirstWhere() method.
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->orderStatusHistory->where($filters)->latest()->get();
    }

    public function update(string $id, array $data): bool
    {
        // TODO: Implement update() method.
    }

    public function delete(array $params): bool
    {
        // TODO: Implement delete() method.
    }
}
