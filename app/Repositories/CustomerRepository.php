<?php

namespace App\Repositories;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(
        private readonly User            $user,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->user->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->user->with($relations)->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->user->with($relations)->when(!empty($orderBy), function ($query) use ($orderBy) {
            $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
        });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->user->with($relations)
            ->when(empty($filters['withCount']),function ($query)use($filters){
                return $query->where($filters);
            })
            ->when($searchValue, function ($query) use ($searchValue) {
                $query->orWhere('f_name', 'like', "%$searchValue%")
                    ->orWhere('l_name', 'like', "%$searchValue%")
                    ->orWhere('phone', 'like', "%$searchValue%")
                    ->orWhere('email', 'like', "%$searchValue%");
            })
            ->when(isset($filters['withCount']),function ($query)use($filters){
                return $query->withCount($filters['withCount']);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends(['searchValue' => $searchValue]);
    }

    public function getListWhereNotIn(array $ids = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->user->whereNotIn('id', $ids)->get();
    }


    public function update(string $id, array $data): bool
    {
        return $this->user->where('id', $id)->update($data);
    }

    public function delete(array $params): bool
    {
        $this->user->where($params)->delete();
        return true;
    }

    public function getCustomerNameList(object $request, int|string $dataLimit = DEFAULT_DATA_LIMIT): object
    {
        $searchValue = explode(' ', $request['searchValue']);
        return $this->user->where('id','!=',0)
            ->where(function ($query) use ($searchValue) {
                foreach ($searchValue as $value) {
                    $query->orWhere('f_name', 'like', "%$value%")
                        ->orWhere('l_name', 'like', "%$value%")
                        ->orWhere('phone', 'like', "%$value%");
                }
            })
            ->limit($dataLimit)
            ->get([DB::raw('id,IF(id <> "0", CONCAT(f_name, " ", l_name, " (", phone ,")"),CONCAT(f_name, " ", l_name)) as text')]);
    }

    public function deleteAuthAccessTokens(string|int $id): bool
    {
        DB::table('oauth_access_tokens')->where('user_id', $id)->delete();
        return true;
    }
}
