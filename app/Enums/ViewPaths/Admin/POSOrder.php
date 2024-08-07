<?php

namespace App\Enums\ViewPaths\Admin;

enum POSOrder
{
    const ORDER_DETAILS = [
        URI => 'order-details',
        VIEW => 'admin-views.pos.order.order-details',
    ];
    const ORDER_PLACE = [
        VIEW => 'admin-views.pos.order.order-details',
        URI => 'order-place'

    ];
    const CANCEL_ORDER =[
        VIEW => 'admin-views.pos.partials._view-hold-orders',
        URI => 'cancel-order',
    ];
    const HOLD_ORDERS =[
        VIEW => 'admin-views.pos.partials._view-hold-orders',
        URI => 'view-hold-orders',

    ];
}
