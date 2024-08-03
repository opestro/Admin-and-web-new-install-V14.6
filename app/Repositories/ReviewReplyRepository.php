<?php

namespace App\Repositories;

use App\Contracts\Repositories\ReviewReplyRepositoryInterface;
use App\Models\ReviewReply;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ReviewReplyRepository implements ReviewReplyRepositoryInterface
{

    public function __construct(private readonly ReviewReply $reviewReply)
    {
    }

    public function add(array $data): string|object
    {
        return $this->reviewReply->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->reviewReply->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->reviewReply->with($relations)->when(!empty($orderBy), function ($query) use ($orderBy) {
            $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
        });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->reviewReply->with($relations)
            ->when(!empty($searchValue), function ($query) use ($searchValue) {
                $query->where('review_id', 'like', "%{$searchValue}%");
            })
            ->when(isset($filters['review_id']), function ($query) use ($filters) {
                $query->where(['review_id' => $filters['review_id']]);
            })
            ->when(isset($filters['added_by_id']), function ($query) use ($filters) {
                $query->where(['added_by_id' => $filters['added_by_id']]);
            })
            ->when(isset($filters['whereNull']), function ($query) use ($filters) {
                $query->whereNull($filters['whereNull']['column']);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends(['searchValue' => $searchValue]);
    }

    public function getListWhereIn(bool $globalScope = true, array $orderBy = [], string $searchValue = null, array $filters = [], array $whereInFilters = [], array $relations = [], array $nullFields = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->reviewReply->with($relations)
            ->when(!empty($searchValue), function ($query) use ($searchValue) {
                $query->where('review_id', 'like', "%{$searchValue}%");
            })
            ->when(isset($filters['review_id']), function ($query) use ($filters) {
                $query->where(['review_id' => $filters['review_id']]);
            })
            ->when(isset($filters['added_by_id']), function ($query) use ($filters) {
                $query->where(['added_by_id' => $filters['added_by_id']]);
            })
            ->when(isset($filters['whereNull']), function ($query) use ($filters) {
                $query->whereNull($filters['whereNull']['column']);
            })
            ->when(!empty($nullFields), function ($query) use ($nullFields) {
                $query->whereNull($nullFields);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->reviewReply->find($id)->update($data);
    }

    public function updateWhere(array $params, array $data): bool
    {
        $this->reviewReply->where($params)->update($data);
        return true;
    }

    public function updateOrInsert(array $params, array $data): bool
    {
        $this->reviewReply->updateOrInsert($params, $data);
        return true;
    }

    public function delete(array $params): bool
    {
        $this->reviewReply->where($params)->delete();
        return true;
    }
}
