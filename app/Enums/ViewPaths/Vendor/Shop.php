<?php

namespace App\Enums\ViewPaths\Vendor;

enum Shop
{
    const INDEX = [
        URI => 'index',
        VIEW => 'vendor-views.shop.index',
        ROUTE => 'vendor.shop.index',
    ];
    const ORDER_SETTINGS = [
        URI => 'update-order-settings',
        VIEW => 'vendor-views.shop.order-settings-view'
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => 'vendor-views.shop.update-view'
    ];
    const VACATION = [
        URI => 'add-vacation',
        VIEW => ''
    ];
    const TEMPORARY_CLOSE = [
        URI => 'close-shop-temporary',
        VIEW => ''
    ];
}
