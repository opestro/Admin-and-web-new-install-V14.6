<?php

namespace App\Repositories;

use App\Contracts\Repositories\ShopFollowerRepositoryInterface;
use App\Models\ShopFollower;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ShopFollowerRepository implements ShopFollowerRepositoryInterface
{
    public function __construct(
        private readonly ShopFollower $shopFollower,
    )
    {
    }
    public function add(array $data): string|object
    {
        return $this->shopFollower->newInstance()->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->shopFollower->with($relations)->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->shopFollower->with($relations)
            ->when(isset($filters['user_id']), function ($query) use ($filters) {
                return $query->where('user_id', $filters['user_id']);
            })
            ->when(isset($filters['shop_id']), function ($query) use ($filters) {
                return $query->where('shop_id', $filters['shop_id']);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(key($orderBy), current($orderBy));
            });
        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
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
