<?php

namespace App\Repositories;

use App\Contracts\Repositories\ShippingMethodRepositoryInterface;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ShippingMethodRepository implements ShippingMethodRepositoryInterface
{
    public function __construct(
        private readonly ShippingMethod $shippingMethod
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->shippingMethod->newInstance()->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
       return $this->shippingMethod->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query =  $this->shippingMethod->with($relations)->where($filters)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(key($orderBy),current($orderBy));
            });
        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->shippingMethod->find($id)->update($data);
    }

    public function delete(array $params): bool
    {
      return $this->shippingMethod->where($params)->delete();
    }
}
