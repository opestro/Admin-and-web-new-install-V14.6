<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductSeoRepositoryInterface;
use App\Models\ProductSeo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductSeoRepository implements ProductSeoRepositoryInterface
{
    public function __construct(
        private readonly ProductSeo $productSeo,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->productSeo->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->productSeo->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->productSeo
            ->with($relations)
            ->when(isset($filters['product_id']), function ($query) use ($filters) {
                return $query->where(['product_id' => $filters['product_id']]);
            })->when(isset($filters['key']), function ($query) use ($filters) {
                return $query->where(['key' => $filters['key']]);
            })->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->productSeo->where('id', $id)->update($data);
    }

    public function updateByParams(array $params, array $data): bool
    {
        return $this->productSeo->where($params)->update($data);
    }

    public function updateOrInsert(array $params, array $data): bool
    {
        $productSeo = $this->productSeo->firstOrNew($params);
        $productSeo->fill($data);
        $productSeo->save();
        return true;
    }

    public function delete(array $params): bool
    {
        return $this->productSeo->where($params)->delete();
    }

}
