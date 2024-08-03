<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface CouponRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $filters
     * @param array $relations
     * @return Model|null
     */
    public function getFirstWhereFilters(array $filters = [], array $relations = []): ?Model;
}
