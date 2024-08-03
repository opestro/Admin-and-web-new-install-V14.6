<?php

namespace App\Repositories;

use App\Contracts\Repositories\FlashDealProductRepositoryInterface;
use App\Models\FlashDealProduct;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class FlashDealProductRepository implements FlashDealProductRepositoryInterface
{
    public function __construct(private readonly FlashDealProduct $flashDealProduct)
    {
    }

    public function add(array $data): string|object
    {
        return $this->flashDealProduct->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->flashDealProduct->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {

    }

    public function getListWhere(array $orderBy=[], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->flashDealProduct
            ->with($relations)
            ->when($searchValue, function ($query) use($searchValue){
                $query->orWhere('title', 'like', "%$searchValue%");
            })
            ->when(isset($filters['flash_deal_id']), function ($query) use ($filters){
                return $query->where(['flash_deal_id'=>$filters['flash_deal_id']]);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        //
    }

    public function delete(array $params): bool
    {
        $this->flashDealProduct->where($params)->delete();
        return true;
    }


}
