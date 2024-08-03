<?php

namespace App\Repositories;

use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class SettingRepository implements SettingRepositoryInterface
{
    public function __construct(
        private readonly Setting $setting,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->setting->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->setting->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->setting
                ->with($relations)
                ->when(!empty($orderBy), function ($query) use ($orderBy) {
                    $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
                });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(
        array      $orderBy = [],
        string     $searchValue = null,
        array      $filters = [], array $relations = [],
        int|string $dataLimit = DEFAULT_DATA_LIMIT,
        int        $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->setting
                ->when($searchValue, function ($query) use ($searchValue) {
                    return $query->where('key_name', 'like', "%$searchValue%");
                })
                ->when(!empty($orderBy), function ($query) use ($orderBy) {
                    $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
                });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getListWhereIn(array $orderBy = [], string $searchValue = null, array $filters = [], array $whereInFilters = [], array $relations = [], array $nullFields = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->setting
            ->with($relations)
            ->when($searchValue, function ($query) use($searchValue){
                return $query->where('key_name', 'like', "%$searchValue%");
            })
            ->when(isset($filters['id']) , function ($query) use ($filters){
                return $query->where(['id' => $filters['id']]);
            })
            ->when(isset($filters['key_name']) , function ($query) use ($filters){
                return $query->where(['key_name' => $filters['key_name']]);
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
        return $this->setting->where('id', $id)->update($data);
    }

    public function updateWhere(array $params, array $data): bool
    {
        return $this->setting->where($params)->update($data);
    }

    public function updateOrInsert(array $params, array $data): bool
    {
        $this->setting->updateOrInsert($params, $data);
        return true;
    }

    public function delete(array $params): bool
    {
        $this->setting->where($params)->delete();
        return true;
    }
}
