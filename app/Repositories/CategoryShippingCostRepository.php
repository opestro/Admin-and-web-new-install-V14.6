<?php

namespace App\Repositories;

use App\Contracts\Repositories\CategoryShippingCostRepositoryInterface;
use App\Models\CategoryShippingCost;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryShippingCostRepository implements CategoryShippingCostRepositoryInterface
{
    public function __construct(
        private readonly CategoryShippingCost    $categoryShippingCost
    )
    {
    }
    public function add(array $data): string|object
    {
        return $this->categoryShippingCost->newInstance()->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->categoryShippingCost->with($relations)->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
       return $this->categoryShippingCost->with($relations)->where($filters)->get();
    }

    public function update(string $id, array $data): bool
    {
        return $this->categoryShippingCost->find($id)->update($data);
    }

    public function updateOrInsert(array $params, array $data): bool
    {
        $this->categoryShippingCost->updateOrInsert($params, $data);
        return true;
    }

    public function delete(array $params): bool
    {
        // TODO: Implement delete() method.
    }
}
