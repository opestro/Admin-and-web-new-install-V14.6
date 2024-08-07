<?php

namespace App\Repositories;

use App\Contracts\Repositories\ErrorLogsRepositoryInterface;
use App\Models\ErrorLogs;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ErrorLogsRepository implements ErrorLogsRepositoryInterface
{
    public function __construct(
        private readonly ErrorLogs $errorLog
    )
    {

    }
    public function add(array $data): string|object
    {
        // TODO: Implement add() method.
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
        $query = $this->errorLog
                ->when(!empty($orderBy), function ($query) use ($orderBy) {
                    $query->orderBy(key($orderBy), current($orderBy));
                });
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->errorLog->find($id)->update($data);
    }

    public function delete(array $params): bool
    {
        return $this->errorLog->where($params)->delete();
    }
}
