<?php

namespace App\Enums\ViewPaths\Vendor;

enum ShippingMethod
{

    const INDEX = [
        URI => 'index',
        VIEW => 'vendor-views.shipping-method.index',
        ROUTE =>'vendor.business-settings.shipping-method.index'
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => 'vendor-views.shipping-method.update-view'
    ];
    const UPDATE_STATUS = [
        URI => 'update-status',
        VIEW => 'vendor-views.shipping-method.update-view'
    ];
    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];
}
