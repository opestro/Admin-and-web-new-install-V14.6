<?php

namespace App\Contracts\Repositories;

interface WithdrawalMethodRepositoryInterface extends RepositoryInterface
{

    /**
     * @param array $params
     * @param array $data
     * @return bool
     */
    public function updateWhereNotIn(array $params, array $data): bool;
}
