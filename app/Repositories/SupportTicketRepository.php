<?php

namespace App\Repositories;

use App\Contracts\Repositories\SupportTicketRepositoryInterface;
use App\Models\SupportTicket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class SupportTicketRepository implements SupportTicketRepositoryInterface
{
    public function __construct(
        private readonly SupportTicket $supportTicket,
    )
    {
    }


    public function add(array $data): string|object
    {
        return $this->supportTicket->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->supportTicket->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->supportTicket->with($relations)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->supportTicket
            ->with($relations)
            ->when($searchValue, function ($query) use($searchValue){
                return $query->Where('subject', 'like', "%{$searchValue}%")
                    ->orWhere('type', 'like', "%{$searchValue}%")
                    ->orWhere('description', 'like', "%{$searchValue}%")
                    ->orWhere('status', 'like', "%{$searchValue}%");
            })
            ->when(isset($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(isset($filters['priority']) && $filters['priority'] != 'all', function ($query) use ($filters) {
                $query->where('priority', $filters['priority']);
            })
            ->when(isset($filters['status']) && $filters['status'] != 'all', function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }


    public function update(string $id, array $data): bool
    {
        return $this->supportTicket->where('id', $id)->update($data);
    }

    public function delete(array $params): bool
    {
        $this->supportTicket->where($params)->delete();
        return true;
    }

}
