<?php

namespace App\Repositories;

use App\Contracts\Repositories\WithdrawRequestRepositoryInterface;
use App\Models\WithdrawRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class WithdrawRequestRepository implements WithdrawRequestRepositoryInterface
{
    public function __construct(
        private readonly WithdrawRequest $withdrawRequest,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->withdrawRequest->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
       return $this->withdrawRequest->with($relations)->where($params)->first();
    }

    public function getFirstWhereNotNull(array $params,array $filters = [],array $orderBy = [],array $relations = []): ?Model
    {
        return $this->withdrawRequest->where($params)->whereNotNull($filters)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(key($orderBy),current($orderBy));
            })
            ->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy=[], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->withdrawRequest->with($relations)
            ->when($searchValue, function ($query) use ($searchValue,$relations) {
                $query->whereHas(current($relations), function ($query) use ($searchValue) {
                    $searchTerms = explode(' ', $searchValue);
                    foreach ($searchTerms as $term) {
                        $query->where(function ($query) use ($term) {
                            $query->where('f_name', 'like', "%$term%")
                                ->orWhere('l_name', 'like', "%$term%");
                        });
                    }
                });
            })
            ->when(isset($filters['vendorId']),function ($query)use($filters){
                $query->where(['seller_id'=>$filters['vendorId']]);
            })->when(isset($filters['admin_id']),function ($query)use($filters){
                $query->where(['admin_id'=>$filters['admin_id']]);
            })
            ->when(isset($filters['whereNotNull']) && $filters['whereNotNull'] =='delivery_man_id'  ,function ($query){
                $query->whereNotNull('delivery_man_id');
            })
            ->when(isset($filters['status']) && $filters['status'] == 'approved', function ($query) {
                return $query->where('approved', 1);
            })
            ->when(isset($filters['status']) && $filters['status'] == 'denied', function ($query) {
                return $query->where('approved', 2);
            })
            ->when(isset($filters['status']) && $filters['status'] == 'pending', function ($query) {
                return $query->where('approved', 0);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(key($orderBy),current($orderBy));
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getListWhereNull(array $orderBy=[], string $searchValue = null, array $filters = [], array $nullFilters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->withdrawRequest->with($relations)
            ->when($searchValue == 'delivery_withdraw_status_filter' ,function ($query){
                $query->whereNotNull('delivery_man_id');
            })
            ->when(isset($filters) && $filters['approved'] == 'approved', function ($query) {
                return $query->where('approved', 1);
            })
            ->when(isset($filters) && $filters['approved'] == 'denied', function ($query) {
                return $query->where('approved', 2);
            })
            ->when(isset($filters) && $filters['approved'] == 'pending', function ($query) {
                return $query->where('approved', 0);
            })->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        $this->withdrawRequest->where(['id'=>$id])->update($data);
        return true;
    }

    public function delete(array $params): bool
    {
       return $this->withdrawRequest->where($params)->delete();
    }
}
