<?php

namespace App\Repositories;

use App\Contracts\Repositories\NotificationMessageRepositoryInterface;
use App\Models\NotificationMessage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationMessageRepository implements NotificationMessageRepositoryInterface
{
    public function __construct(
        private readonly NotificationMessage $notificationMessage
    )
    {

    }
    public function add(array $data): string|object
    {
        return $this->notificationMessage->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        // TODO: Implement getFirstWhere() method.
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {

    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->notificationMessage->with($relations)->where($filters)->get();
    }

    public function update(string $id, array $data): bool
    {
        return $this->notificationMessage->where(['id'=>$id])->update($data);
    }

    public function delete(array $params): bool
    {
        return $this->notificationMessage->where($params)->delete();
    }
}
