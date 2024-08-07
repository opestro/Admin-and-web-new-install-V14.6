<?php

namespace App\Contracts\Repositories;

interface ProductTagRepositoryInterface extends RepositoryInterface
{
    public function getIds(string $fieldName='tag_id', array $filters = []): \Illuminate\Support\Collection|array;
}
