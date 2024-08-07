<?php

namespace App\Enums\ViewPaths\Vendor;

enum POSOrder
{
    const ORDER_DETAILS = [
        URI => 'order-details',
        VIEW => 'vendor-views.pos.order.order-details',
    ];
    const ORDER_PLACE = [
      VIEW => 'vendor-views.pos.order.order-details',
      URI => 'order-place'

    ];
    const CANCEL_ORDER =[
        VIEW => 'vendor-views.pos.partials._view-hold-orders',
        URI => 'cancel-order',
    ];
    const HOLD_ORDERS =[
        VIEW => 'vendor-views.pos.partials._view-hold-orders',
        URI => 'view-hold-orders',

    ];
}
