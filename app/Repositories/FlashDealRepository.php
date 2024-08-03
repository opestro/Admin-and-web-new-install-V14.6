<?php

namespace App\Repositories;

use App\Contracts\Repositories\FlashDealRepositoryInterface;
use App\Models\FlashDeal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class FlashDealRepository implements FlashDealRepositoryInterface
{
    public function __construct(private readonly FlashDeal $flashDeal)
    {
    }

    public function add(array $data): string|object
    {
        return $this->flashDeal->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->flashDeal->where($params)->first();
    }


    public function getFirstWhereWithoutGlobalScope(array $params, array $relations = []): ?Model
    {
        return $this->flashDeal->withoutGlobalScopes()->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {

    }

    public function getListWhere(array $orderBy=[], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        //
    }

    public function update(string $id, array $data): bool
    {
        return $this->flashDeal->where('id', $id)->update($data);
    }

    public function updateWhere(array $params, array $data): bool
    {
        $this->flashDeal->where($params)->update($data);
        return true;
    }

    public function delete(array $params): bool
    {
        return $this->flashDeal->where($params)->delete();
    }




    public function getListWithRelations(array $orderBy=[], string $searchValue = null, array $filters = [], array $withCount = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->flashDeal
            ->with($relations)
            ->when($searchValue, function ($query) use($searchValue){
                $query->orWhere('title', 'like', "%$searchValue%");
            })
            ->when(isset($filters['deal_type']), function ($query) use ($filters) {
                return $query->where(['deal_type'=>$filters['deal_type']]);
            })
            ->when(isset($withCount['products']), function ($query) use ($withCount) {
                return $query->withCount([$withCount['products']=> function ($query) {
                    return $query->whereHas('product',function ($query){
                        return $query->active();
                    });
                }]);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }


}
