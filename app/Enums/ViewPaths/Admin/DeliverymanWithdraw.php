<?php

namespace App\Enums\ViewPaths\Admin;

enum DeliverymanWithdraw
{

    const LIST = [
        URI => 'withdraw-list',
        VIEW => 'admin-views.delivery-man.withdraw.index',
        TABLE_VIEW=> 'admin-views.delivery-man.withdraw._table',
    ];

    const EXPORT_LIST = [
        URI => 'withdraw-list-export',
        VIEW => ''
    ];

    const VIEW = [
        URI => 'withdraw-view',
        VIEW => 'admin-views.delivery-man.withdraw._details'
    ];

    const UPDATE = [
        URI => 'withdraw-update-status',
        VIEW => ''
    ];

}
