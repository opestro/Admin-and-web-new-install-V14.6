<?php

namespace App\Services;

class ShopFollowerService
{
    public function getAddData(string|int $customerId,string|int $shopId):array
    {
        return [
            'user_id' => $customerId,
            'shop_id' => $shopId
        ];
    }
}
