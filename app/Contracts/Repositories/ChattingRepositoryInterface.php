<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ChattingRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $params
     * @param array $filters
     * @param array $orderBy
     * @param array $relations
     * @return Model|null
     */
    public function getFirstWhereNotNull(array $params, array $filters = [], array $orderBy = [], array $relations = []): ?Model;

    /**
     * @param array $joinColumn
     * @param array $select
     * @param array $filters
     * @param array $orderBy
     * @return Collection
     */
    public function getListBySelectWhere(array $joinColumn = [], array $select = [], array $filters = [], array $orderBy = []): Collection;

    /**
     * @param array $params
     * @param array $data
     * @return bool
     */
    public function updateAllWhere(array $params, array $data) : bool;

    /**
     * @param string|null $searchValue
     * @param array $filters
     * @param array $whereNotNull
     * @param array $data
     * @return bool
     */
    public function updateListWhereNotNull(string $searchValue = null, array $filters = [], array $whereNotNull = [], array $data = []): bool;
}
