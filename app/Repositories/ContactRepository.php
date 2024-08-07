<?php

namespace App\Repositories;

use App\Contracts\Repositories\ContactRepositoryInterface;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactRepository implements ContactRepositoryInterface
{
    public function __construct(private readonly Contact $contact)
    {
    }

    public function add(array $data): string|object
    {
        return $this->contact->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->contact->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->contact
            ->with($relations)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy=[], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->contact->with($relations)
                ->when($searchValue, function ($query) use($searchValue){
                    $query->orWhere('name', 'like', "%$searchValue%")
                        ->orWhere('email', 'like', "%$searchValue%")
                        ->orWhere('mobile_number', 'like', "%$searchValue%");
                })
                ->when(isset($filters['reply']) && $filters['reply'] == 'replied',function ($query){
                    return $query->whereNotNull('reply');
                })
                ->when(isset($filters['reply']) && $filters['reply'] == 'not_replied',function ($query){
                    return $query->whereNull('reply');
                })
                ->when(!empty($orderBy), function ($query) use ($orderBy) {
                    $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
                });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        $this->contact->where('id', $id)->update($data);
        return true;
    }


    public function delete(array $params): bool
    {
        return $this->contact->where($params)->delete();
    }


}
