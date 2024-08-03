<?php

namespace App\Services;

class ProductCompareService
{
    public function getAddData(int|string $customerId,int|string $productId):array
    {
        return [
            'user_id' => $customerId,
            'product_id' => $productId,
        ];
    }
}
