<?php

namespace App\Enums\ViewPaths\Vendor;

enum Coupon
{
    const INDEX = [
        URI => 'index',
        VIEW => 'vendor-views.coupon.index',
        ROUTE => 'vendor.coupon.index'
    ];
    const ADD = [
        URI => 'add',
        VIEW => ''
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => 'vendor-views.coupon.update-view'
    ];
    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];
    const QUICK_VIEW = [
        URI => 'quick-view',
        VIEW => 'vendor-views.coupon.quick-view'
    ];
    const UPDATE_STATUS = [
        URI => 'update-status',
        VIEW => ''
    ];
    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];


}
