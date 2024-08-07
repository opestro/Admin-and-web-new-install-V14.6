<?php

namespace App\Enums\ViewPaths\Admin;

enum POS
{
    const SUMMARY =[
        VIEW => 'admin-views.pos.partials._cart-summary',
    ];
    const CART =[
        VIEW => 'admin-views.pos.partials._cart',
    ];

    const INDEX =[
        URI => '/',
        VIEW => 'admin-views.pos.index',
        ROUTE => 'admin.pos.index',
    ];
    const ORDER_CANCEL =[
        URI => 'order-cancel',
        VIEW => 'admin-views.pos.partials._view-hold-orders',
    ];
    const CHANGE_CUSTOMER =[
        URI => 'change-customer',
    ];
    const UPDATE_DISCOUNT =[
        URI => 'update-discount',
    ];
    const COUPON_DISCOUNT =[
        URI => 'coupon-discount',
    ];
    const STORE_KEY =[
        URI => 'store-key',
    ];
    const QUICK_VIEW = [
        URI => 'quick-view',
        VIEW => 'admin-views.pos.partials._quick-view'
    ];
    const SEARCH = [
        URI => 'search-product',
        VIEW => 'admin-views.pos.partials._search-product'
    ];

}
