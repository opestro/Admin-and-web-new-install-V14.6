<?php

namespace App\Enums\ViewPaths\Admin;

enum ShippingMethod
{

    const INDEX = [
        URI => 'index',
        VIEW => 'admin-views.shipping-method.index',
        ROUTE =>'admin.business-settings.shipping-method.index'
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.shipping-method.update-view'
    ];
    const UPDATE_STATUS = [
        URI => 'update-status',
        VIEW => ''
    ];
    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];
    const UPDATE_SHIPPING_RESPONSIBILITY = [
        URI => 'update-shipping-responsibility',
        VIEW => ''
    ];

}
