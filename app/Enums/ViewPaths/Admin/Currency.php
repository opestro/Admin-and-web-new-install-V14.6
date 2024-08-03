<?php

namespace App\Enums\ViewPaths\Admin;

enum Currency
{
    const LIST = [
        URI => 'view',
        VIEW => 'admin-views.currency.view'
    ];

    const ADD = [
        URI => 'store',
        VIEW => ''
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.currency.edit'
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];

    const DEFAULT = [
        URI => 'system-currency-update',
        VIEW => ''
    ];


}
