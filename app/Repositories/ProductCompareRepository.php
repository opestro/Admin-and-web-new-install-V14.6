<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductCompareRepositoryInterface;
use App\Models\ProductCompare;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductCompareRepository implements ProductCompareRepositoryInterface
{
    public function __construct(
        private readonly ProductCompare $productCompare
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->productCompare->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->productCompare->with($relations)->where($params)->first();
    }

    public function getCount(array $params): int|null
    {
        return $this->productCompare->when(isset($params['product_id']), function ($query) use($params){
            return $query->where('product_id', $params['product_id']);
        })->when(isset($params['customer_id']), function ($query) use($params){
            return $query->where('user_id', $params['customer_id']);
        })->count();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->productCompare->with($relations)
            ->when(isset($filters['user_id']), function ($query) use ($filters) {
                return $query->where('user_id', $filters['user_id']);
            })
            ->when(isset($filters['whereHas']), function ($query) use ($filters) {
                return $query->whereHas($filters['whereHas']);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(key($orderBy), current($orderBy));
            });
        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->productCompare->where(['id'=>$id])->update($data);
    }

    public function delete(array $params): bool
    {
        $this->productCompare->where($params)->delete();
        return true;
    }
}
