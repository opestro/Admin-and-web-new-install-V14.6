<?php

namespace App\Contracts\Repositories;

interface DeliveryManWalletRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $params
     * @param array $data
     * @return bool
     */
    public function updateWhere(array $params, array $data): bool;
}
