<?php

namespace App\Repositories;

use App\Contracts\Repositories\DealOfTheDayRepositoryInterface;
use App\Models\DealOfTheDay;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class DealOfTheDayRepository implements DealOfTheDayRepositoryInterface
{
    public function __construct(private readonly DealOfTheDay $dealOfTheDay)
    {
    }

    public function add(array $data): string|object
    {
        return $this->dealOfTheDay->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->dealOfTheDay->where($params)->first();
    }

    public function getFirstWhereWithoutGlobalScope(array $params, array $relations = []): ?Model
    {
        return $this->dealOfTheDay->withoutGlobalScopes()->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {

    }

    public function getListWhere(array $orderBy=[], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->dealOfTheDay
            ->with($relations)
            ->when($searchValue, function ($query) use($searchValue){
                $query->orWhere('title', 'like', "%$searchValue%");
            })
            ->when(isset($filters['product_id']), function ($query) use ($filters){
                return $query->where(['product_id'=>$filters['product_id']]);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        $this->dealOfTheDay->where('id', $id)->update($data);
        return true;
    }


    public function updateWhere(array $params, array $data): bool
    {
        $this->dealOfTheDay->where($params)->update($data);
        return true;
    }

    public function delete(array $params): bool
    {
        return $this->dealOfTheDay->where($params)->delete();
    }


}
