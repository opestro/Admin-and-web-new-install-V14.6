<?php

namespace App\Repositories;

use App\Contracts\Repositories\StorageRepositoryInterface;
use App\Models\Storage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class StorageRepository implements StorageRepositoryInterface
{
    public function __construct(
        private readonly Storage $storage,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->storage->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->storage->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->storage
            ->with($relations)
            ->when(isset($filters['id']), function ($query) use ($filters) {
                return $query->where(['id' => $filters['id']]);
            })->when(isset($filters['data_type']), function ($query) use ($filters) {
                return $query->where(['data_type' => $filters['data_type']]);
            })->when(isset($filters['data_id']), function ($query) use ($filters) {
                return $query->where(['data_id' => $filters['data_id']]);
            })->when(isset($filters['key']), function ($query) use ($filters) {
                return $query->where(['key' => $filters['key']]);
            })->when(isset($filters['value']), function ($query) use ($filters) {
                return $query->where(['value' => $filters['value']]);
            })->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getListWhereNotIn(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], array $nullFields = [], array $whereNotIn = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->storage->when($whereNotIn, function ($query) use ($whereNotIn) {
            foreach ($whereNotIn as $key => $whereNotInIndex) {
                $query->whereNotIn($key, $whereNotInIndex);
            }
        })->when(isset($filters['id']), function ($query) use ($filters) {
            return $query->where(['id' => $filters['id']]);
        })->when(isset($filters['data_type']), function ($query) use ($filters) {
            return $query->where(['data_type' => $filters['data_type']]);
        })->when(isset($filters['data_id']), function ($query) use ($filters) {
            return $query->where(['data_id' => $filters['data_id']]);
        })->when(isset($filters['key']), function ($query) use ($filters) {
            return $query->where(['key' => $filters['key']]);
        })->when(isset($filters['value']), function ($query) use ($filters) {
            return $query->where(['value' => $filters['value']]);
        })->when(!empty($orderBy), function ($query) use ($orderBy) {
            $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
        });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->storage->where('id', $id)->update($data);
    }

    public function updateByParams(array $params, array $data): bool
    {
        return $this->storage->where($params)->update($data);
    }

    public function updateOrInsert(array $params, array $data): bool
    {
        $this->storage->updateOrInsert($params, $data);
        return true;
    }

    public function delete(array $params): bool
    {
        return $this->storage->where($params)->delete();
    }

}
