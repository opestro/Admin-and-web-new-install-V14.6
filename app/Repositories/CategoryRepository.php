<?php

namespace App\Repositories;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Translation;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private readonly Category       $category,
        private readonly Translation    $translation
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->category->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->category->where($params)->with($relations)->withoutGlobalScopes()->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->category->with($relations)
                ->when(!empty($orderBy), function ($query) use ($orderBy) {
                    return $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
                });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->category->with($relations)
            ->where($filters)
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $translation_ids = $this->translation->where('translationable_type', 'App\Models\Category')
                    ->where('key', 'name')
                    ->where(function ($q) use ($searchValue) {
                        $q->orWhere('value', 'like', "%$searchValue%");
                    })->pluck('translationable_id');
                $query->where('name', 'like', "%$searchValue%")->orWhereIn('id', $translation_ids);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->category->where('id', $id)->update($data);
    }

    public function delete(array $params): bool
    {
        $categories = $this->category->where(['id'=>$params['id']])->with(['childes.childes'])->get();

        foreach ($categories as $category) {
            if ($category->childes){
                foreach ($category->childes as $child) {
                    if ($child->childes) {
                        foreach ($child->childes as $item) {
                            $this->translation->where('translationable_type', 'App\Models\Category')->where('translationable_id', $item['id'])->delete();
                            $this->category->where('id', $item['id'])->delete();
                        }
                    }
                    $this->translation->where('translationable_type', 'App\Models\Category')->where('translationable_id', $child['id'])->delete();
                    $this->category->where('id', $child['id'])->delete();
                }
            }
        }

        $this->translation->where('translationable_type', 'App\Models\Category')->where('translationable_id', $params['id'])->delete();
        $this->category->where('id', $params['id'])->delete();
        return true;
    }

}
