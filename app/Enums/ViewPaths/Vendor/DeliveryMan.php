<?php

namespace App\Enums\ViewPaths\Vendor;

enum DeliveryMan
{
    const INDEX = [
        URI => 'index',
        VIEW => 'vendor-views.delivery-man.index'
    ];
    const LIST = [
        URI => 'list',
        VIEW => 'vendor-views.delivery-man.list',
        ROUTE => 'vendor.delivery-man.list'
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => 'vendor-views.delivery-man.update-view'
    ];
    const UPDATE_STATUS = [
        URI => 'update-status',
        VIEW => ''
    ];
    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];
    const RATING = [
        URI => 'rating',
        VIEW => 'vendor-views.delivery-man.rating'
    ];
    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

}
