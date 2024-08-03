<?php

namespace App\Enums\ViewPaths\Vendor;

enum DeliveryManWithdraw
{
    const INDEX = [
        URI => 'index',
        VIEW => 'vendor-views.delivery-man.withdraw.index',
        TABLE_VIEW=>'vendor-views.delivery-man.withdraw._table'
    ];
    const DETAILS = [
        URI => 'details',
        VIEW => 'vendor-views.delivery-man.withdraw._details'
    ];
    const UPDATE_STATUS = [
        URI => 'update-status',
        VIEW => ''
    ];
    const EXPORT = [
        URI => 'export',
        VIEW => '',
        FILE_NAME => 'Delivery-Man-Withdraw-Request.xlsx'
    ];


}
