<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface FlashDealRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $params
     * @param array $relations
     * @return Model|null
     */
    public function getFirstWhereWithoutGlobalScope(array $params, array $relations = []): ?Model;

    /**
     * @param array $params
     * @param array $data
     * @return bool
     */
    public function updateWhere(array $params, array $data): bool;
}
