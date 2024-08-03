<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderTransactionRepositoryInterface;
use App\Models\OrderTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderTransactionRepository implements OrderTransactionRepositoryInterface
{
    public function __construct(private readonly OrderTransaction $orderTransaction)
    {
    }

    public function getByStatusExcept(string $status, array $relations = [], int $paginateBy = DEFAULT_DATA_LIMIT): Collection|array|LengthAwarePaginator
    {
        return $this->orderTransaction->with($relations)->whereNotIn('status', [$status])->paginate($paginateBy);
    }


    public function add(array $data): string|object
    {
        return $this->orderTransaction->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->orderTransaction->with($relations)->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->orderTransaction->with($relations)->when(!empty($orderBy), function ($query) use ($orderBy) {
            $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
        });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->orderTransaction->with($relations)
            ->when(isset($filters['seller_is']), function ($query) use ($filters) {
                return $query->where('seller_is',$filters['seller_is']);
            })
            ->when(isset($filters['seller_id']), function ($query) use ($filters) {
                return $query->where('seller_id',$filters['seller_id']);
            })
            ->when(isset($filters['status']) &&  $filters['status'] != 'all' , function ($query) use ($filters) {
                return $query->where('status',$filters['status']);
            })
            ->when($searchValue, function ($query) use ($searchValue) {
                $query->where('order_id',  'like', "%$searchValue%")
                    ->orWhere('transaction_id', 'like', "%$searchValue%");
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });
        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getListWhereBetween(array $filters = [], string $selectColumn = null, string $whereBetween = null, array $whereBetweenFilters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->orderTransaction->with($relations)->where($filters)
            ->when($selectColumn == 'seller_amount', function ($query) {
                $query->select(
                    DB::raw('IFNULL(sum(seller_amount),0) as sums'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month,DAY(created_at) day,DAYNAME(created_at) day_of_week')
                );
            })
            ->when($selectColumn == 'admin_commission', function ($query) {
                $query->select(
                    DB::raw('IFNULL(sum(admin_commission),0) as sums'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month,DAY(created_at) day,DAYNAME(created_at) day_of_week')
                );
            })
            ->whereBetween($whereBetween, $whereBetweenFilters)
            ->groupby('year', 'month')
            ->get();
    }

    public function update(string $id, array $data): bool
    {
        return $this->orderTransaction->where(['id' => $id])->update($data);
    }

    public function delete(array $params): bool
    {
        $this->orderTransaction->where($params)->delete();
        return true;
    }

    public function getCommissionEarningStatisticsData(string $sellerIs = null, array $dataRange = [], array $groupBy = [], int $dateStart = 1, int $dateEnd = 12): array
    {
        $commissionEarningStatisticsData = [];
        $commissionEarnings = $this->orderTransaction
            ->when(isset($sellerIs), function ($query) use ($sellerIs) {
                return $query->where('seller_is', $sellerIs);
            })
            ->where(['status' => 'disburse'])
            ->select(DB::raw('IFNULL(sum(admin_commission),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )
            ->when($dataRange, function ($query) use ($dataRange) {
                return $query->whereBetween('created_at', [$dataRange['from'], $dataRange['to']]);
            })
            ->when($groupBy, function ($query) use ($groupBy) {
                return $query->groupby($groupBy);
            })->get()->toArray();

        if (in_array('day', $groupBy)) {
            for ($inc = $dateStart; $inc <= $dateEnd; $inc++) {
                $commissionEarningStatisticsData[$inc] = 0;
                foreach ($commissionEarnings as $match) {
                    if ($match['day'] == $inc) {
                        $commissionEarningStatisticsData[$inc] = $match['sums'];
                    }
                }
            }
        } else {
            for ($inc = $dateStart; $inc <= $dateEnd; $inc++) {
                $commissionEarningStatisticsData[$inc] = 0;
                foreach ($commissionEarnings as $match) {
                    if ($match['month'] == $inc) {
                        $commissionEarningStatisticsData[$inc] = $match['sums'];
                    }
                }
            }
        }

        return $commissionEarningStatisticsData;
    }

    public function getEarningStatisticsData(string $sellerIs = null, array $dataRange = [], array $groupBy = [], int $dateStart = 1, int $dateEnd = 12): array
    {
        $inhouseEarningStatisticsData = [];
        $inhouseEarnings = $this->orderTransaction
            ->when(isset($sellerIs), function ($query) use ($sellerIs) {
                return $query->where('seller_is', $sellerIs);
            })
            ->where(['status' => 'disburse'])
            ->select(DB::raw('IFNULL(sum(seller_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )
            ->when($dataRange, function ($query) use ($dataRange) {
                return $query->whereBetween('created_at', [$dataRange['from'], $dataRange['to']]);
            })
            ->when($groupBy, function ($query) use ($groupBy) {
                return $query->groupby($groupBy);
            })
            ->get()
            ->toArray();

        if (in_array('day', $groupBy)) {
            for ($inc = $dateStart; $inc <= $dateEnd; $inc++) {
                $inhouseEarningStatisticsData[$inc] = 0;
                foreach ($inhouseEarnings as $match) {
                    if ($match['day'] == $inc) {
                        $inhouseEarningStatisticsData[$inc] = $match['sums'];
                    }
                }
            }
        } else {
            for ($inc = $dateStart; $inc <= 12; $inc++) {
                $inhouseEarningStatisticsData[$inc] = 0;
                foreach ($inhouseEarnings as $match) {
                    if ($match['month'] == $inc) {
                        $inhouseEarningStatisticsData[$inc] = $match['sums'];
                    }
                }
            }
        }

        return $inhouseEarningStatisticsData;
    }


}
