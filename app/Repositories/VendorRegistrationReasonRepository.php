<?php

namespace App\Repositories;

use App\Contracts\Repositories\VendorRegistrationReasonInterface;
use App\Models\VendorRegistrationReason;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class VendorRegistrationReasonRepository implements VendorRegistrationReasonInterface
{

    public function __construct(
        private readonly VendorRegistrationReason $vendorRegistrationReason,
    )
    {
    }
    public function add(array $data): string|object
    {
        return $this->vendorRegistrationReason->newInstance()->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->vendorRegistrationReason->with($relations)->where($params)->first();

    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->vendorRegistrationReason
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(key($orderBy),current($orderBy));
            });
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {

        $query = $this->vendorRegistrationReason->where($filters)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(key($orderBy),current($orderBy));
            });
        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->vendorRegistrationReason->find($id)->update($data);
    }

    public function delete(array $params): bool
    {
        return $this->vendorRegistrationReason->where($params)->delete();
    }
}
