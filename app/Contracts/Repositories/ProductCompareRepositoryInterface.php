<?php

namespace App\Contracts\Repositories;

interface ProductCompareRepositoryInterface extends RepositoryInterface
{

    /**
     * @param array $params
     * @return int|null
     */
    public function getCount(array $params): int|null;
}
