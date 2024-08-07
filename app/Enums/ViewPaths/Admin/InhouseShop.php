<?php

namespace App\Enums\ViewPaths\Admin;

enum InhouseShop
{
    const VIEW = [
        URI => 'inhouse-shop',
        VIEW => 'admin-views.product-settings.inhouse-shop'
    ];

    const UPDATE = [
        URI => 'inhouse-shop',
        VIEW => 'admin-views.product-settings.inhouse-shop-edit'
    ];

    const VACATION_ADD = [
        URI => 'vacation-add',
        VIEW => ''
    ];

    const TEMPORARY_CLOSE = [
        URI => 'inhouse-shop-temporary-close',
        VIEW => ''
    ];
}
