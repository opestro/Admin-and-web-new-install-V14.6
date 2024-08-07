<?php

namespace App\Repositories;

use App\Contracts\Repositories\WithdrawalMethodRepositoryInterface;
use App\Models\WithdrawalMethod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class WithdrawalMethodRepository implements WithdrawalMethodRepositoryInterface
{

    public function __construct(private readonly WithdrawalMethod $withdrawalMethod)
    {
    }
    public function add(array $data): string|object
    {
        return $this->withdrawalMethod->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->withdrawalMethod->with($relations)->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->withdrawalMethod->with($relations)->when(!empty($orderBy), function ($query) use ($orderBy) {
            $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
        });
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->withdrawalMethod->where($filters)->with($relations)
            ->when($searchValue, function ($query) use($searchValue){
                $query->where('method_name', 'LIKE', "%$searchValue%");
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        $this->withdrawalMethod->where(['id'=>$id])->update($data);
        return true;
    }

    public function updateWhereNotIn(array $params, array $data): bool
    {
        $this->withdrawalMethod->where(function ($query) use ($params) {
            foreach ($params as $column => $value) {
                $query->whereNotIn($column, [$value]);
            }
        })->update($data);
        return true;
    }

    public function delete(array $params): bool
    {
        $this->withdrawalMethod->where($params)->delete();
        return true;
    }
}
