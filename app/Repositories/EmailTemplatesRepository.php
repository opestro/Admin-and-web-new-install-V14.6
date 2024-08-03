<?php

namespace App\Repositories;

use App\Contracts\Repositories\EmailTemplatesRepositoryInterface;
use App\Models\EmailTemplate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class EmailTemplatesRepository implements EmailTemplatesRepositoryInterface
{
    public function __construct(
        private readonly EmailTemplate $emailTemplate,
    )
    {
    }
    public function add(array $data): string|object
    {
        return $this->emailTemplate->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->emailTemplate->with($relations)->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->emailTemplate->with($relations)->where($filters)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            })->get();

    }

    public function update(string $id, array $data): bool
    {
        return $this->emailTemplate->find($id)->update($data);

    }

    public function delete(array $params): bool
    {
        return $this->emailTemplate->where($params)->delete();
    }
}
