<?php

namespace App\Repositories;

use App\Contracts\Repositories\CouponRepositoryInterface;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class CouponRepository implements CouponRepositoryInterface
{
    public function __construct(private readonly Coupon $coupon)
    {
    }

    public function add(array $data): string|object
    {
        return $this->coupon->newInstance()->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->coupon->with($relations)->where($params)->first();
    }
    public function getFirstWhereFilters(array $filters = [], array $relations = []): ?Model
    {
        return $this->coupon->with($relations)
            ->when(isset($filters['added_by']), function ($query) use ($filters) {
                $query->where('added_by', $filters['added_by']);
            })
            ->when(isset($filters['code']), function ($query) use ($filters) {
                $query->where('code', $filters['code']);
            })
            ->when(isset($filters['coupon_bearer']), function ($query) use ($filters) {
                $query->where('coupon_bearer', $filters['coupon_bearer']);
            })
            ->when(isset($filters['limit']), function ($query) use ($filters) {
                $query->where('limit', '>', $filters['limit']);
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(isset($filters['start_date']), function ($query) use ($filters) {
                $query->whereDate('start_date', '<=', $filters['start_date']);
            })
            ->when(isset($filters['expire_date']), function ($query) use ($filters) {
                $query->whereDate('expire_date', '>=', $filters['expire_date']);
            })
            ->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {

    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->coupon->with($relations)
            ->when(!empty($searchValue), function ($query) use ($searchValue) {
                return $query->where(function ($query) use ($searchValue) {
                    return $query->orWhere('title', 'like', "%{$searchValue}%")
                        ->orWhere('code', 'like', "%{$searchValue}%")
                        ->orWhere('discount_type', 'like', "%{$searchValue}%");
                });
            })
            ->when(isset($filters['added_by']) && $filters['added_by'] == 'seller', function ($query) use ($filters) {
                return $query->whereIn('seller_id', ['0', $filters['vendorId']]);
            })
            ->when(isset($filters['added_by']) && $filters['added_by'] == 'admin', function ($query) use ($filters) {
                return $query->where(['added_by' => $filters['added_by']]);
            })
            ->withCount('order')
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(key($orderBy), current($orderBy));
            });
        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->coupon->where('id', $id)->update($data);
    }

    public function delete(array $params): bool
    {
        return $this->coupon->where($params)->delete();
    }


}
