<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait PaginatorTrait
{
    protected function getPaginationData(LengthAwarePaginator $collection): array
    {
        return [
            'links' => $collection->links(),
            'total' => $collection->total(),
        ];
    }
}
