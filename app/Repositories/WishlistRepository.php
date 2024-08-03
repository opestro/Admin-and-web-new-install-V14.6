<?php

namespace App\Repositories;

use App\Contracts\Repositories\WishlistRepositoryInterface;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class WishlistRepository implements WishlistRepositoryInterface
{
    public function __construct(private readonly Wishlist $wishlist)
    {
    }

    public function add(array $data): string|object
    {
        return $this->wishlist->newInstance()->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->wishlist->where($params)->first();
    }

    public function getCount(array $params): int|null
    {
        return $this->wishlist->when(isset($params['product_id']), function ($query) use($params){
            return $query->where('product_id', $params['product_id']);
        })->when(isset($params['customer_id']), function ($query) use($params){
            return $query->where('customer_id', $params['customer_id']);
        })->count();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {

    }

    public function getListWhere(array $orderBy=[], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->wishlist->whereIn('seller_id', [auth('seller')->id(), '0'])
            ->when(!empty($searchValue), function ($query) use ($searchValue) {
                $key = explode(' ', $searchValue);
                foreach ($key as $value) {
                    $query->where('title', 'like', "%{$value}%")
                        ->orWhere('code', 'like', "%{$value}%")
                        ->orWhere('discount_type', 'like', "%{$value}%");
                }
            })
            ->withCount('order')->latest()->paginate($dataLimit)->appends($filters);
    }

    public function getListWhereCount(string $searchValue = null, array $filters = [], array $relations = []): int
    {
        return $this->wishlist->with($relations)
            ->when(isset($filters['product_id']), function ($query) use ($filters) {
                return $query->where(['product_id' => $filters['product_id']]);
            })->when(isset($filters['customer_id']), function ($query) use ($filters) {
                return $query->where(['customer_id' => $filters['customer_id']]);
            })->count();
    }

    public function update(string $id, array $data): bool
    {
        return $this->wishlist->where('id', $id)->update($data);
    }

    public function delete(array $params): bool
    {
        return $this->wishlist->where($params)->delete();
    }


}
