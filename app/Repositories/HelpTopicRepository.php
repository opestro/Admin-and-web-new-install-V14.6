<?php

namespace App\Repositories;

use App\Contracts\Repositories\HelpTopicRepositoryInterface;
use App\Models\HelpTopic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class HelpTopicRepository implements HelpTopicRepositoryInterface
{
    public function __construct(
        private readonly HelpTopic $helpTopic,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->helpTopic->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->helpTopic->with($relations)->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->helpTopic->with($relations)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->helpTopic
            ->with($relations)
            ->when($searchValue, function ($query) use ($searchValue) {
                $query->where('question', 'like', "%{$searchValue}%")
                    ->orWhere('answer', 'like', "%{$searchValue}%");
            })
            ->when(isset($filters['type']), function ($query) use ($filters) {
                return $query->where(['type' => $filters['type']]);
            })
            ->when(isset($filters['ranking']), function ($query) use ($filters) {
                return $query->where(['ranking' => $filters['ranking']]);
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where(['status' => $filters['status']]);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(key($orderBy), current($orderBy));
            });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->helpTopic->where('id', $id)->update($data);
    }

    public function updateWhere(array $params, array $data): bool
    {
        $this->helpTopic->where($params)->update($data);
        return true;
    }

    public function delete(array $params): bool
    {
        $this->helpTopic->where($params)->delete();
        return true;
    }
}
