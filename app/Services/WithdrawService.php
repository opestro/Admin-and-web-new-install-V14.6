<?php

namespace App\Services;

class WithdrawService
{
    /**
     * @param object $withdrawRequest
     * @param object $wallet
     * @return array
     */
    public function getVendorWalletData(object $withdrawRequest, object $wallet):array
    {
        return [
            'total_earning' => $wallet['total_earning'] + currencyConverter($withdrawRequest['amount'],to:'usd'),
            'pending_withdraw' => $wallet['pending_withdraw'] - currencyConverter($withdrawRequest['amount'],to:'usd'),
        ];
    }

}
