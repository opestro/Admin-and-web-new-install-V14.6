<?php

namespace App\Repositories;

use App\Contracts\Repositories\PasswordResetRepositoryInterface;
use App\Models\PasswordReset;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class PasswordResetRepository implements PasswordResetRepositoryInterface
{
    public function __construct(
        private readonly PasswordReset $passwordReset
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->passwordReset->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->passwordReset->with($relations)->where($params)->first();
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
        $this->passwordReset->where($params)->delete();
        return true;
    }
}
