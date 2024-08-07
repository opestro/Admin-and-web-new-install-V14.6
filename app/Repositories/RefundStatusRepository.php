<?php

namespace App\Repositories;

use App\Contracts\Repositories\RefundStatusRepositoryInterface;
use App\Models\RefundStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class RefundStatusRepository implements RefundStatusRepositoryInterface
{
    public function __construct(
        private readonly RefundStatus $refundStatus,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->refundStatus->create($data);
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
        // TODO: Implement getListWhere() method.
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
