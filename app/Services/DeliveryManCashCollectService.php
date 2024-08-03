<?php

namespace App\Services;

use App\Traits\PushNotificationTrait;

class DeliveryManCashCollectService
{
    public function getIdentityImages(object $request, object $deliveryMan): array
    {
        return [
            'delivery_man_id' => $deliveryMan['id'],
            'user_id'         => 0,
            'user_type'       => 'admin',
            'credit'           => usdToDefaultCurrency(amount: $request['amount']),
            'transaction_type' => 'cash_in_hand'
        ];

    }


}
