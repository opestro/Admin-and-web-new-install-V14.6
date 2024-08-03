<?php

namespace App\Repositories;

use App\Contracts\Repositories\RefundRequestRepositoryInterface;
use App\Models\RefundRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class RefundRequestRepository implements RefundRequestRepositoryInterface
{
    public function __construct(
        private readonly RefundRequest $refundRequest,
    )
    {
    }

    public function add(array $data): string|object
    {
        // TODO: Implement add() method.
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->refundRequest->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getListWhere() method.
    }

    public function getListWhereHas(array $orderBy = [], string $searchValue = null, array $filters = [], string $whereHas = null, array $whereHasFilters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->refundRequest->whereHas($whereHas, function ($query) use ($whereHasFilters) {
                return $query->when(isset($whereHasFilters['seller_is']) && $whereHasFilters['seller_is'] == 'admin', function ($query) use ($whereHasFilters) {
                    return $query->where(['seller_is' => $whereHasFilters['seller_is']]);
                })->when(isset($whereHasFilters['seller_is']) && $whereHasFilters['seller_is'] == 'seller', function ($query) use ($whereHasFilters) {
                    return $query->where(['seller_is' => $whereHasFilters['seller_is']])
                        ->when(isset($whereHasFilters['seller_id']), function($query) use($whereHasFilters){
                            return $query->where(['seller_id' => $whereHasFilters['seller_id']]);
                        });
                });
            })
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $key = explode(' ', $searchValue);
                $query->where(function ($subQuery) use ($key) {
                    foreach ($key as $value) {
                        $subQuery->orWhere('order_id', 'like', "%{$value}%")
                            ->orWhere('id', 'like', "%{$value}%");
                    }
                });
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                $query->where(['status' => $filters['status']]);
            })
            ->orderBy(key($orderBy), current($orderBy));

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getFirstWhereHas(array $params, string $whereHas = null, array $whereHasFilters = [], array $relations = []): ?Model
    {
        return $this->refundRequest->with($relations)->whereHas($whereHas, function ($query) use ($whereHasFilters) {
            $query->where($whereHasFilters);
        })->where($params)->first();
    }

    public function update(string $id, array $data): bool
    {
        return $this->refundRequest->find($id)->update($data);
    }

    public function delete(array $params): bool
    {
        // TODO: Implement delete() method.
    }
}
