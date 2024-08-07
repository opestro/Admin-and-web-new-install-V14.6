<?php

namespace App\Repositories;

use App\Contracts\Repositories\ShippingTypeRepositoryInterface;
use App\Models\ShippingType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ShippingTypeRepository implements ShippingTypeRepositoryInterface
{
    public function __construct(private readonly ShippingType $shippingType)
    {
    }
    public function add(array $data): string|object
    {
        return $this->shippingType->newInstance()->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->shippingType->where($params)->first();
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
        return $this->shippingType->where('id',$id)->update($data);
    }

    public function delete(array $params): bool
    {
        // TODO: Implement delete() method.
    }
}
