<?php

namespace App\Repositories;

use App\Contracts\Repositories\RobotsMetaContentRepositoryInterface;
use App\Models\RobotsMetaContent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RobotsMetaContentRepository implements RobotsMetaContentRepositoryInterface
{
    public function __construct(
        private readonly RobotsMetaContent $robotsMetaContent,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->robotsMetaContent->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->robotsMetaContent->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->robotsMetaContent
            ->with($relations)
            ->when(isset($filters['id']), function ($query) use ($filters) {
                return $query->where(['id' => $filters['id']]);
            })->when(isset($filters['page_name']), function ($query) use ($filters) {
                return $query->where(['page_name' => $filters['page_name']]);
            })->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getListWhereNotIn(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], array $nullFields = [], array $whereNotIn = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->robotsMetaContent->when($whereNotIn, function ($query) use ($whereNotIn) {
            foreach ($whereNotIn as $key => $whereNotInIndex) {
                $query->whereNotIn($key, $whereNotInIndex);
            }
        })->when(isset($filters['id']), function ($query) use ($filters) {
            return $query->where(['id' => $filters['id']]);
        })->when(isset($filters['page_name']), function ($query) use ($filters) {
            return $query->where(['page_name' => $filters['page_name']]);
        })->when(!empty($orderBy), function ($query) use ($orderBy) {
            $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
        });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->robotsMetaContent->find($id)->update($data);
    }

    public function updateByParams(array $params, array $data): bool
    {
        return $this->robotsMetaContent->where($params)->update($data);
    }

    public function updateOrInsert(array $params, array $data): bool
    {
        $robotsMetaContent = $this->robotsMetaContent->firstOrNew($params);
        $robotsMetaContent->fill($data);
        $robotsMetaContent->save();
        return true;
    }

    public function delete(array $params): bool
    {
        return $this->robotsMetaContent->where($params)->delete();
    }

}
