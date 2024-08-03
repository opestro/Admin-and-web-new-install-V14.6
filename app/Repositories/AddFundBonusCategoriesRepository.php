<?php

namespace App\Repositories;

use App\Contracts\Repositories\AddFundBonusCategoriesRepositoryInterface;
use App\Models\AddFundBonusCategories;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class AddFundBonusCategoriesRepository implements AddFundBonusCategoriesRepositoryInterface
{
    public function __construct(
        private readonly AddFundBonusCategories   $addFundBonusCategories,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->addFundBonusCategories->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->addFundBonusCategories->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->addFundBonusCategories->with($relations)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->addFundBonusCategories->where($filters)->with($relations)
            ->when($searchValue, function ($query) use($searchValue){
                $query->where('title', 'like', "%$searchValue%");
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });
        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->addFundBonusCategories->where('id', $id)->update($data);
    }

    public function delete(array $params): bool
    {
        $this->addFundBonusCategories->where($params)->delete();
        return true;
    }
}
