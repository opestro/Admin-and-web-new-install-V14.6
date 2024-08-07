<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductTagRepositoryInterface;
use App\Models\ProductTag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductTagRepository implements ProductTagRepositoryInterface
{
    public function __construct(
        private readonly ProductTag $productTag,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->productTag->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        // TODO: Implement getFirstWhere() method.
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
        // TODO: Implement update() method.
    }

    public function delete(array $params): bool
    {
        // TODO: Implement delete() method.
    }

    public function getIds(string $fieldName = 'tag_id', array $filters = []): \Illuminate\Support\Collection|array
    {
        return $this->productTag->when(isset($filters['product_id']), function ($query) use ($filters) {
            return $query->where('product_id', $filters['product_id']);
        })->pluck($fieldName);
    }
}
