<?php

namespace App\Enums\ViewPaths\Vendor;

enum Dashboard
{
    const INDEX = [
        URI => '/',
        VIEW => 'vendor-views.dashboard.index',
        ROUTE => 'vendor.dashboard.index'
    ];

    const ORDER_STATUS = [
        URI => 'order-status',
        VIEW => 'vendor-views.partials._dashboard-order-status'
    ];
    const EARNING_STATISTICS = [
        URI => 'earning-statistics',
        VIEW => 'vendor-views.dashboard.partials.earning-statistics'
    ];
    const WITHDRAW_REQUEST = [
            URI => 'withdraw-request',
        VIEW => ''
    ];
    const METHOD_LIST = [
        URI => 'method-list',
        VIEW => ''
    ];

}
