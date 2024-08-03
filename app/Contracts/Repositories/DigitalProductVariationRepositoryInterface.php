<?php

namespace App\Contracts\Repositories;

interface DigitalProductVariationRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $params
     * @param array $data
     * @return bool
     */
    public function updateByParams(array $params, array $data): bool;
}
