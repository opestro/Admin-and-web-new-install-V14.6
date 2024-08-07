<?php

namespace App\Enums\ViewPaths\Vendor;

enum DeliveryManWallet
{
    const INDEX = [
        URI => 'index',
        VIEW => 'vendor-views.delivery-man.wallet.index',
        ROUTE => 'vendor.delivery-man.withdraw.index',
    ];
    const ORDER_HISTORY = [
        URI => 'order-history',
        VIEW => 'vendor-views.delivery-man.wallet.order-history'
    ];
    const ORDER_STATUS_HISTORY = [
        URI => 'order-history-status',
        VIEW => 'vendor-views.delivery-man.wallet._order-status-history'
    ];
    const EARNING = [
        URI => 'earning',
        VIEW => 'vendor-views.delivery-man.wallet.earning'
    ];
    const CASH_COLLECT = [
        URI => 'cash-collect',
        VIEW => 'vendor-views.delivery-man.wallet.cash-collect'
    ];
}
