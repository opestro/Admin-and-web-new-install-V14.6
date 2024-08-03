<?php

namespace App\Repositories;

use App\Contracts\Repositories\ChattingRepositoryInterface;
use App\Models\Chatting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ChattingRepository implements ChattingRepositoryInterface
{
    public function __construct(
        private readonly Chatting $chatting
    )
    {
    }
    public function add(array $data): string|object
    {
        return $this->chatting->newInstance()->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
       return $this->chatting->where($params)->first();
    }
    public function getFirstWhereNotNull(array $params,array $filters = [],array $orderBy = [],array $relations = []): ?Model
    {
        return $this->chatting->where($params)->whereNotNull($filters)->orderBy(key($orderBy), current($orderBy))->first();
    }
    public function getListBySelectWhere(array $joinColumn = [], array $select = [],array $filters = [],array $orderBy = []): Collection
    {
        list($table, $first, $operator, $second) = $joinColumn;
        return $this->chatting
            ->join($table, $first, $operator, $second)
            ->select($select)
            ->where($filters)
            ->orderBy(key($orderBy), current($orderBy))
            ->get();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->chatting->with($relations)
            ->when(isset($filters['user_id']), function ($query) use ($filters) {
                return $query->where(['user_id'=>$filters['user_id']]);
            })
            ->when(isset($filters['seller_id']), function ($query) use ($filters) {
                return $query->where(['seller_id'=>$filters['seller_id']]);
            })
            ->when(isset($filters['delivery_man_id']), function ($query) use ($filters) {
                return $query->where(['delivery_man_id'=>$filters['delivery_man_id']]);
            })
            ->when(isset($filters['admin_id']), function ($query) use ($filters) {
                return $query->where(['admin_id'=>$filters['admin_id']]);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getListWhereNotNull(array $orderBy = [], string $searchValue = null, array $filters = [], array $whereNotNull = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->chatting->with($relations)
            ->when(isset($filters['user_id']), function ($query) use ($filters) {
                return $query->where(['user_id'=>$filters['user_id']]);
            })
            ->when(isset($filters['seller_id']), function ($query) use ($filters) {
                return $query->where(['seller_id'=>$filters['seller_id']]);
            })
            ->when(isset($filters['delivery_man_id']), function ($query) use ($filters) {
                return $query->where(['delivery_man_id'=>$filters['delivery_man_id']]);
            })
            ->when(isset($filters['admin_id']), function ($query) use ($filters) {
                return $query->where(['admin_id'=>$filters['admin_id']]);
            })
            ->when(isset($filters['notification_receiver']), function ($query) use ($filters) {
                return $query->where(['notification_receiver'=>$filters['notification_receiver']]);
            })
            ->when(isset($filters['seen_notification']), function ($query) use ($filters) {
                return $query->where(['seen_notification'=>$filters['seen_notification']]);
            })
            ->whereNotNull($whereNotNull)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function updateAllWhere(array $params, array $data) : bool
    {
        return $this->chatting->where($params)->update($data);
    }

    public function updateListWhereNotNull(string $searchValue = null, array $filters = [], array $whereNotNull = [], array $data = []): bool
    {
        $this->chatting
            ->when(isset($filters['user_id']), function ($query) use ($filters) {
                return $query->where(['user_id'=>$filters['user_id']]);
            })
            ->when(isset($filters['seller_id']), function ($query) use ($filters) {
                return $query->where(['seller_id'=>$filters['seller_id']]);
            })
            ->when(isset($filters['delivery_man_id']), function ($query) use ($filters) {
                return $query->where(['delivery_man_id'=>$filters['delivery_man_id']]);
            })
            ->when(isset($filters['admin_id']), function ($query) use ($filters) {
                return $query->where(['admin_id'=>$filters['admin_id']]);
            })
            ->when(isset($filters['notification_receiver']), function ($query) use ($filters) {
                return $query->where(['notification_receiver'=>$filters['notification_receiver']]);
            })
            ->when(isset($filters['seen_notification']), function ($query) use ($filters) {
                return $query->where(['seen_notification'=>$filters['seen_notification']]);
            })
            ->whereNotNull($whereNotNull)->update($data);

        return true;
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
