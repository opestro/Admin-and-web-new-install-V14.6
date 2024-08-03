<?php

namespace App\Repositories;

use App\Contracts\Repositories\OfflinePaymentMethodRepositoryInterface;
use App\Models\OfflinePaymentMethod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class OfflinePaymentMethodRepository implements OfflinePaymentMethodRepositoryInterface
{
    public function __construct(
        private readonly OfflinePaymentMethod $offlinePaymentMethod
    )
    {

    }

    public function add(array $data): string|object
    {
        return $this->offlinePaymentMethod->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->offlinePaymentMethod->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query =  $this->offlinePaymentMethod->with($relations)
            ->when(isset($filters['status']) && $filters['status'] == 'active' ,function ($query)use($filters){
                return $query->where('status',1);
            })
            ->when(isset($filters['status']) && $filters['status'] == 'inactive' ,function ($query)use($filters){
                return $query->where('status',0);
            })
            ->when(isset($searchValue),function ($query)use($searchValue){
                return $query->where('method_name', 'like', "%{$searchValue}%");
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(key($orderBy),current($orderBy));
            });
        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->offlinePaymentMethod->find($id)->update($data);
    }

    public function delete(array $params): bool
    {
        return $this->offlinePaymentMethod->where($params)->delete();
    }
}
