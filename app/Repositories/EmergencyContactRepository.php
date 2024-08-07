<?php

namespace App\Repositories;

use App\Contracts\Repositories\EmergencyContactRepositoryInterface;
use App\Models\EmergencyContact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class EmergencyContactRepository  implements EmergencyContactRepositoryInterface
{
    public function __construct(
        private readonly EmergencyContact $emergencyContact
    )
    {

    }

    public function add(array $data): string|object
    {
        return $this->emergencyContact->newInstance()->create($data);

    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->emergencyContact->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->emergencyContact->where($filters)->latest()->paginate($dataLimit);
    }

    public function update(string $id, array $data): bool
    {
        return $this->emergencyContact->find($id)->update($data);
    }

    public function updateWhere(array $params, array $data): bool
    {
        $this->emergencyContact->where($params)->update($data);
        return true;
    }

    public function delete(array $params): bool
    {
        return $this->emergencyContact->where($params)->delete();
    }
}
