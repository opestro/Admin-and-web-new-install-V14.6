<?php

namespace App\Repositories;

use App\Contracts\Repositories\NotificationRepositoryInterface;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function __construct(
        private readonly Notification $notification,
    ){

    }
    public function add(array $data): string|object
    {
        return $this->notification->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
       return $this->notification->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query =  $this->notification->with($relations)
            ->when(isset($filters['send_to']),function ($query)use($filters){
                return $query->where('send_to',$filters['send_to']);
            })
            ->when(isset($searchValue),function ($query)use($searchValue){
                return $query->where('title', 'like', "%{$searchValue}%");
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }
    public function getListWhereBetween(array $params = [], array $filters = [], array|string $relations = null, int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->notification->whereBetween('created_at',$params)->where($filters)->whereDoesntHave($relations)->get();
    }


    public function update(string $id, array $data): bool
    {
        return $this->notification->find($id)->update($data);
    }

    public function delete(array $params): bool
    {
        return $this->notification->where($params)->delete();
    }
}
