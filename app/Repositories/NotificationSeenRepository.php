<?php

namespace App\Repositories;

use App\Contracts\Repositories\NotificationSeenRepositoryInterface;
use App\Models\NotificationSeen;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationSeenRepository implements NotificationSeenRepositoryInterface
{
    public function __construct(
        private readonly NotificationSeen $notificationSeen
    )
    {

    }

    public function add(array $data): string|object
    {
        // TODO: Implement add() method.
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return  $this->notificationSeen->where($params)->first();
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
       return $this->notificationSeen->find($id)->update($data);
    }

    public function delete(array $params): bool
    {
        // TODO: Implement delete() method.
    }
}
