<?php

namespace App\Repositories;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Models\BusinessSetting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BusinessSettingRepository implements BusinessSettingRepositoryInterface
{
    public function __construct(
        private readonly BusinessSetting  $businessSetting,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->businessSetting->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->businessSetting->with($relations)->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->businessSetting->with($relations)
                ->when(!empty($orderBy), function ($query) use ($orderBy) {
                    return $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
                });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->businessSetting
            ->with($relations)
            ->when($searchValue, function ($query) use($searchValue){
                $query->where('value', 'like', "%{$searchValue}%");
            })
            ->when(isset($filters['id']) , function ($query) use ($filters){
                return $query->where(['id' => $filters['id']]);
            })
            ->when(isset($filters['type']) , function ($query) use ($filters){
                return $query->where(['type' => $filters['type']]);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(key($orderBy),current($orderBy));
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getListWhereIn(array $orderBy = [], string $searchValue = null, array $filters = [], array $whereInFilters = [], array $relations = [], array $nullFields = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->businessSetting
            ->with($relations)->where($filters)
            ->when($searchValue, function ($query) use($searchValue){
                return $query->where('value', 'like', "%$searchValue%");
            })
            ->when(isset($filters['id']) , function ($query) use ($filters){
                return $query->where(['id' => $filters['id']]);
            })
            ->when(isset($filters['type']) , function ($query) use ($filters){
                return $query->where(['type' => $filters['type']]);
            })
            ->when(!empty($whereInFilters), function ($query) use ($whereInFilters) {
                foreach ($whereInFilters as $key => $filterIndex){
                    $query->whereIn($key , $filterIndex);
                }
            })
            ->when(!empty($nullFields), function ($query) use ($nullFields) {
                return $query->whereNull($nullFields);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }



    public function update(string $id, array $data): bool
    {
        return $this->businessSetting->where('id', $id)->update($data);
    }

    public function updateWhere(array $params, array $data): bool
    {
        $this->businessSetting->where($params)->update($data);
        return true;
    }

    public function updateOrInsert(string $type, mixed $value): bool
    {
        $this->businessSetting->updateOrInsert(['type' => $type], [
            'value' => $value,
            'updated_at' => now()
        ]);

        return true;
    }

    public function whereJsonContains(array $params, array $value): ?Model
    {
        return $this->businessSetting->where($params)->whereJsonContains('value', $value)->first();
    }

    public function delete(array $params): bool
    {
        $this->businessSetting->where($params)->delete();
        return true;
    }
}
