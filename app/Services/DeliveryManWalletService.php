<?php

namespace App\Services;

use App\Traits\PushNotificationTrait;
class DeliveryManWalletService
{
    public function getDeliveryManData(string|int $id, float $deliverymanCharge, float $cashInHand): array
    {
        return [
            'delivery_man_id' => $id,
            'current_balance' => currencyConverter($deliverymanCharge) ?? 0,
            'cash_in_hand' => currencyConverter($cashInHand),
            'pending_withdraw' => 0,
            'total_withdraw' => 0,
        ];
    }
    public function getDeliveryManWalletData(object $request, object $wallet, object $withdraw):array
    {
       return [
           'total_withdraw' => $request['approved'] == 1 ? ($wallet['total_withdraw'] + currencyConverter($withdraw['amount'])) : $wallet['total_withdraw'],
           'pending_withdraw' => $wallet['pending_withdraw'] - currencyConverter($withdraw['amount']),
           'current_balance' =>  $request['approved'] == 1 ? ($wallet['current_balance'] - currencyConverter($withdraw['amount'])) : $wallet['current_balance'],
        ];
    }
}
