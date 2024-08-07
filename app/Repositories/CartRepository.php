<?php

namespace App\Repositories;

use App\Contracts\Repositories\CartRepositoryInterface;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class CartRepository implements CartRepositoryInterface
{
    public function __construct(private readonly Cart $cart)
    {
    }

    public function add(array $data): string|object
    {
        return $this->cart->newInstance()->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->cart->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {

    }

    public function getListWhere(array $orderBy=[], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->cart->whereIn('seller_id', [auth('seller')->id(), '0'])
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

    public function update(string $id, array $data): bool
    {
        return $this->cart->where('id', $id)->update($data);
    }

    public function delete(array $params): bool
    {
        return $this->cart->where($params)->delete();
    }


}
