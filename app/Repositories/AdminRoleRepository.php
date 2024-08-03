<?php

namespace App\Repositories;

use App\Contracts\Repositories\AdminRoleRepositoryInterface;
use App\Models\AdminRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminRoleRepository implements AdminRoleRepositoryInterface
{
    public function __construct(
        private readonly AdminRole         $adminRole,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->adminRole->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->adminRole->with($relations)->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->adminRole->with($relations)
                ->when(!empty($orderBy), function ($query) use ($orderBy) {
                    return $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
                });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->adminRole
                ->when($searchValue, function ($query) use($searchValue){
                    $query->where('name', 'like', "%$searchValue%");
                })
                ->when(isset($filters['admin_role_id']) && $filters['admin_role_id'] != 'all', function($query)use ($filters){
                    $query->where('admin_role_id', $filters['admin_role_id']);
                });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->adminRole->where('id', $id)->update($data);
    }

    public function delete(array $params): bool
    {
        $this->adminRole->where($params)->delete();
        return true;
    }

    public function getEmployeeRoleList(
        array $orderBy = [],
        string $searchValue = null,
        array $filters = [],
        int|string $dataLimit = DEFAULT_DATA_LIMIT,
        int $offset = null
    ): Collection|LengthAwarePaginator
    {
        $query = $this->adminRole->whereNotIn('id', [1])
                ->when(!empty($orderBy), function ($query) use ($orderBy) {
                    $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
                })->when($searchValue, function ($query) use($searchValue){
                    $query->where('name', 'like', "%$searchValue%");
                })
                ->when(isset($filters['admin_role_id']) && $filters['admin_role_id'] != 'all', function($query)use ($filters){
                    $query->where('admin_role_id', $filters['admin_role_id']);
                });
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }
}
