<?php

namespace App\Repositories;

use App\Contracts\Repositories\AdminWalletRepositoryInterface;
use App\Models\AdminWallet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminWalletRepository implements AdminWalletRepositoryInterface
{
    public function __construct(
        private readonly AdminWallet $adminWallet,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->adminWallet->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->adminWallet->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->adminWallet->with($relations)
                ->when(!empty($orderBy), function ($query) use ($orderBy) {
                    return $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
                });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->adminWallet
                ->when($filters['admin_id'], function($query)use($filters){
                    $query->where('admin_id', $filters['admin_id']);
                });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->adminWallet->where('id', $id)->update($data);
    }

    public function updateWhere(array $params, array $data): bool
    {
        $this->adminWallet->where($params)->update($data);
        return true;
    }

    public function delete(array $params): bool
    {
        $this->adminWallet->where($params)->delete();
        return true;
    }

}
