<?php

namespace App\Repositories;

use App\Contracts\Repositories\VendorWalletRepositoryInterface;
use App\Models\SellerWallet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class VendorWalletRepository implements VendorWalletRepositoryInterface
{
    public function __construct(
        private readonly SellerWallet  $vendorWallet,
    ){

    }

    public function add(array $data): string|object
    {
        return $this->vendorWallet->newInstance()->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->vendorWallet->with($relations)->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->vendorWallet
            ->with($relations)
            ->whereHas('seller', function ($query) {
                return $query;
            })
            ->when($searchValue, function ($query) use($searchValue){
                $query->where('seller_id', 'like', "%$searchValue%");
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
       return $this->vendorWallet->where(['id'=>$id])->update($data);
    }

    public function updateWhere(array $params, array $data): bool
    {
        $this->vendorWallet->where($params)->update($data);
        return true;
    }

    public function delete(array $params): bool
    {
        // TODO: Implement delete() method.
    }
}
