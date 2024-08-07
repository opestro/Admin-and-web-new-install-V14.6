<?php

namespace App\Repositories;

use App\Contracts\Repositories\DigitalProductVariationRepositoryInterface;
use App\Models\DigitalProductVariation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class DigitalProductVariationRepository implements DigitalProductVariationRepositoryInterface
{
    public function __construct(
        private readonly DigitalProductVariation $digitalProductVariation,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->digitalProductVariation->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->digitalProductVariation->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->digitalProductVariation
            ->with($relations)
            ->when(isset($filters['product_id']), function ($query) use ($filters) {
                return $query->where(['product_id' => $filters['product_id']]);
            })->when(isset($filters['variant_key']), function ($query) use ($filters) {
                return $query->where(['variant_key' => $filters['variant_key']]);
            })->when(isset($filters['sku']), function ($query) use ($filters) {
                return $query->where(['sku' => $filters['sku']]);
            })->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->digitalProductVariation->where('id', $id)->update($data);
    }

    public function updateByParams(array $params, array $data): bool
    {
        return $this->digitalProductVariation->where($params)->update($data);
    }

    public function delete(array $params): bool
    {
        return $this->digitalProductVariation->where($params)->delete();
    }

}
