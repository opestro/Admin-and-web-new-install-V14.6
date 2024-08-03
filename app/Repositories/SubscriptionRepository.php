<?php

namespace App\Repositories;

use App\Contracts\Repositories\SubscriptionRepositoryInterface;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function __construct(
        private readonly Subscription   $subscription,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->subscription->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->subscription->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->subscription->with($relations)
                ->when(!empty($orderBy), function ($query) use ($orderBy) {
                    return $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
                });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->subscription->where($filters)->with($relations)
            ->when($searchValue, function ($query) use($searchValue){
                $query->orWhere('email', 'like', "%$searchValue%");
            })->latest();
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends(['searchValue' => $searchValue]);
    }

    public function update(string $id, array $data): bool
    {
        return $this->subscription->where('id', $id)->update($data);
    }

    public function delete(array $params): bool
    {
        $this->subscription->where($params)->delete();
        return true;
    }
}
