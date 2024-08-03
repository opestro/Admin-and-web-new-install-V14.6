<?php

namespace App\Repositories;

use App\Contracts\Repositories\BannerRepositoryInterface;
use App\Models\Banner;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BannerRepository implements BannerRepositoryInterface
{
    public function __construct(
        private readonly Banner $banner,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->banner->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->banner->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->banner->with($relations)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->banner->with($relations)
            ->when($searchValue, function ($query) use($searchValue){
                return $query->orWhere('banner_type', 'like', "%$searchValue%");
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            })
            ->when(isset($filters['resource_type']) && isset($filters['resource_id']), function ($query) use($filters){
                return $query->where(['resource_type'=>$filters['resource_type'],'resource_id'=>$filters['resource_id']]);
            })
            ->when(isset($filters['theme']), function ($query) use($filters){
                return $query->where('theme', $filters['theme']);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getListWhereIn(array $orderBy = [], string $searchValue = null, array $filters = [], array $whereInFilters = [], array $relations = [], array $nullFields = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->banner
            ->with($relations)
            ->when($searchValue, function ($query) use($searchValue){
                return $query->orWhere('banner_type', 'like', "%$searchValue%");
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
            })->when($filters['theme'], function ($query) use($filters){
                return $query->where('theme', $filters['theme']);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }


    public function update(string $id, array $data): bool
    {
        $this->banner->where('id', $id)->update($data);
        return true;
    }

    public function delete(array $params): bool
    {
        $this->banner->where($params)->delete();
        return true;
    }
}
