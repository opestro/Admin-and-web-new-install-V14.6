<?php

namespace App\Repositories;

use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ReviewRepository implements ReviewRepositoryInterface
{

    public function __construct(private readonly Review $review)
    {
    }

    public function add(array $data): string|object
    {
        return $this->review->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->review->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->review->with($relations)->when(!empty($orderBy), function ($query) use ($orderBy) {
            $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
        });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->review->with($relations)
                ->when(!empty($searchValue), function ($query) use ($searchValue) {
                    $query->whereHas('order', function ($query) use ($searchValue) {
                        $query->where('id', 'like', "%{$searchValue}%");
                    });
                })
                ->when(isset($filters['from']) && isset($filters['to']), function ($query) use ($filters) {
                    $query->whereBetween('created_at', [$filters['from'] . ' 00:00:00', $filters['to'] . ' 23:59:59']);
                })
                ->when(isset($filters['rating']), function ($query) use ($filters) {
                    $query->where(['rating'=>$filters['rating']]);
                })
                ->when(isset($filters['delivery_man_id']), function ($query) use ($filters) {
                    $query->where(['delivery_man_id'=>$filters['delivery_man_id']]);
                })
                ->when(isset($filters['whereNull']), function ($query) use ($filters) {
                    $query->whereNull($filters['whereNull']['column']);
                })
                ->when(isset($filters['product_id']) && $filters['product_id'] != 0, function ($query) use ($filters) {
                    $query->where(['product_id'=>$filters['product_id']]);
                })
                ->when(isset($filters['customer_id']) && $filters['customer_id'] != 'all', function ($query) use ($filters) {
                    $query->where(['customer_id'=>$filters['customer_id']]);
                })
                ->when(isset($filters['status']), function ($query) use ($filters) {
                    $query->where(['status'=>$filters['status']]);
                })
                ->when(!empty($orderBy), function ($query) use ($orderBy) {
                    $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
                });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends(['searchValue' => $searchValue]);
    }

    public function getListWhereIn(bool $globalScope = true, array $orderBy = [], string $searchValue = null, array $filters = [], array $whereInFilters = [], array $relations = [], array $nullFields = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->review->with($relations)
                ->when(!empty($searchValue), function ($query) use ($searchValue) {
                    $query->whereHas('order', function ($query) use ($searchValue) {
                        $query->where('id', 'like', "%{$searchValue}%");
                    });
                })
                ->when(isset($filters['added_by']) &&  $filters['added_by'] == 'seller',function ($query)use($filters){
                    return $query->whereHas('product',function ($query)use($filters){
                        return $query->where('added_by',$filters['added_by']);
                    });
                })
                ->when(!$globalScope,function ($query){
                    $query->withoutGlobalScopes();
                })
                ->when(!empty($whereInFilters['product_id']) && $whereInFilters['product_id'] != 0, function ($query) use ($whereInFilters) {
                    $query->whereIn('product_id', $whereInFilters['product_id']);
                })->when(!empty($whereInFilters['customer_id']) && $whereInFilters['customer_id'] != 0, function ($query) use ($whereInFilters) {
                    $query->whereIn('customer_id', $whereInFilters['customer_id']);
                })
                ->when((isset($whereInFilters['product_id'])&& empty($whereInFilters['product_id']))&& (isset($whereInFilters['customer_id']) && empty($whereInFilters['customer_id'])), function ($query) use ($whereInFilters) {
                    $query->where('id', null);
                })
                ->when(isset($filters['from']) && isset($filters['to']), function ($query) use ($filters) {
                    $query->whereBetween('created_at', [$filters['from'] . ' 00:00:00', $filters['to'] . ' 23:59:59']);
                })
                ->when(isset($filters['product_id']) && $filters['product_id'] != 0, function ($query) use ($filters) {
                    $query->where('product_id', $filters['product_id']);
                })
                ->when(isset($filters['customer_id']) && $filters['customer_id'] != 'all', function ($query) use ($filters) {
                    $query->where('customer_id', $filters['customer_id']);
                })
                ->when(isset($filters['product_user_id']) && $filters['product_user_id'] != '0', function ($query) use ($filters) {
                    $query->whereHas('product', function ($query) use ($filters) {
                        $query->where('user_id', $filters['product_user_id'])->where('added_by', 'seller');
                    });
                })
                ->when(isset($filters['status']), function ($query) use ($filters) {
                    $query->where('status', $filters['status']);
                })
                ->when(!empty($nullFields), function ($query) use ($nullFields) {
                    $query->whereNull($nullFields);
                })
                ->when(!empty($orderBy), function ($query) use ($orderBy) {
                    $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
                });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getListWhereHas(bool $globalScope = true, string $whereHas, array $whereHasFilter = []  , array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], array $nullFields = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->review->with($relations)->whereHas($whereHas, function ($query) use ($whereHasFilter) {
                $query->where($whereHasFilter);
            })
            ->when(!$globalScope,function ($query){
                $query->withoutGlobalScopes();
            })
            ->when(!empty($searchValue), function ($query) use ($searchValue) {
                $query->whereHas('order', function ($query) use ($searchValue) {
                    $query->where('id', 'like', "%{$searchValue}%");
                });
            })
            ->when(isset($filters['from']) && isset($filters['to']), function ($query) use ($filters) {
                $query->whereBetween('created_at', [$filters['from'] . ' 00:00:00', $filters['to'] . ' 23:59:59']);
            })
            ->when(isset($filters['product_id']) && $filters['product_id'] != 0, function ($query) use ($filters) {
                $query->where('product_id', $filters['product_id']);
            })
            ->when(isset($filters['customer_id']) && $filters['customer_id'] != 'all', function ($query) use ($filters) {
                $query->where('customer_id', $filters['customer_id']);
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(!empty($nullFields), function ($query) use ($nullFields) {
                $query->whereNull($nullFields);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });
        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getCount(array $params=[], array $whereInFilters=[]): int|null
    {
        return $this->review->when(!empty($whereInFilters), function ($query) use ($whereInFilters) {
            foreach ($whereInFilters as $key => $filterIndex){
                $query->orWhereIn($key , $filterIndex);
            }
        })->count();
    }

    public function update(string $id, array $data): bool
    {
        return $this->review->find($id)->update($data);
    }

    public function updateWhere(array $params, array $data): bool
    {
        $this->review->where($params)->update($data);
        return true;
    }

    public function updateOrInsert(array $params, array $data): bool
    {
        $this->review->updateOrInsert($params, $data);
        return true;
    }

    public function delete(array $params): bool
    {
        $this->review->where($params)->delete();
        return true;
    }
}
