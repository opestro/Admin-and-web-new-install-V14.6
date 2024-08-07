<?php

namespace App\Repositories;

use App\Contracts\Repositories\TagRepositoryInterface;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class TagRepository implements TagRepositoryInterface
{

    public function __construct(private readonly Tag $tag)
    {
    }
    public function add(array $data): string|object
    {
        return $this->tag->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        // TODO: Implement getFirstWhere() method.
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getListWhere() method.
    }

    public function update(string $id, array $data): bool
    {
        // TODO: Implement update() method.
    }

    public function delete(array $params): bool
    {
        // TODO: Implement delete() method.
    }

    public function incrementVisitCount(array $whereIn = []): bool
    {
        $this->tag->when(isset($whereIn), function ($query) use ($whereIn) {
            foreach ($whereIn as $key => $whereInIndex) {
                return $query->whereIn($key, $whereInIndex);
            }
        })->increment('visit_count');

        return true;
    }
}
