<?php

namespace App\Repositories;

use App\Contracts\Repositories\SupportTicketConvRepositoryInterface;
use App\Models\SupportTicketConv;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class SupportTicketConvRepository implements SupportTicketConvRepositoryInterface
{
    public function __construct(
        private readonly SupportTicketConv $supportTicketConv,
    )
    {
    }


    public function add(array $data): string|object
    {
        return $this->supportTicketConv->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->supportTicketConv->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->supportTicketConv->with($relations)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {

    }


    public function update(string $id, array $data): bool
    {
        return $this->supportTicketConv->where('id', $id)->update($data);
    }

    public function delete(array $params): bool
    {
        $this->supportTicketConv->where($params)->delete();
        return true;
    }

}
