<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface WishlistRepositoryInterface extends RepositoryInterface
{
    /**
     * @param string|null $searchValue
     * @param array $filters
     * @param array $relations
     * @return int
     */
    public function getListWhereCount(string $searchValue = null, array $filters = [], array $relations = []): int;

    /**
     * @param array $params
     * @return int|null
     */
    public function getCount(array $params): int|null;
}
