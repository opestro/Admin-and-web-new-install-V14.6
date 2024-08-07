<?php

namespace App\Repositories;

use App\Contracts\Repositories\MostDemandedRepositoryInterface;
use App\Models\MostDemanded;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class MostDemandedRepository implements MostDemandedRepositoryInterface
{
    public function __construct(
        private readonly MostDemanded     $mostDemanded,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->mostDemanded->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->mostDemanded->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->mostDemanded->with($relations)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->mostDemanded->with($relations)
        ->when($searchValue, function ($query) use ($searchValue) {
            return $query->whereHas('product', function ($query) use ($searchValue) {
                return $query->where('name', 'like', "%$searchValue%");
            });
        })->when(isset($filters['status']), function ($query) use ($filters) {
            return $query->where(['status' => $filters['status']]);
        })->when(!empty($orderBy), function ($query) use ($orderBy) {
            $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
        });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        $this->mostDemanded->where('id', $id)->update($data);
        return true;
    }

    public function updateWhere(array $params, array $data): bool
    {
        $this->mostDemanded->where($params)->update($data);
        return true;
    }

    public function delete(array $params): bool
    {
        $this->mostDemanded->where($params)->delete();
        return true;
    }
}
