<?php

namespace App\Services;

use App\Traits\PushNotificationTrait;
use Ramsey\Uuid\Uuid;

class DeliveryManTransactionService
{

    /**
     * @param float $amount
     * @param string $addedBy
     * @param string|int $id
     * @param string $transactionType
     * @return array
     */
    public function getDeliveryManTransactionData(float $amount, string $addedBy, string|int $id, string $transactionType): array
    {
        return [
            'delivery_man_id' => $id,
            'user_id' => $addedBy == 'seller' ? auth('seller')->id() : 0,
            'user_type' => $addedBy,
            'credit' => currencyConverter($amount),
            'transaction_id' => Uuid::uuid4(),
            'transaction_type' => $transactionType,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
