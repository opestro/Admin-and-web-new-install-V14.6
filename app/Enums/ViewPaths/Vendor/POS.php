<?php

namespace App\Enums\ViewPaths\Vendor;

enum POS
{
    const SUMMARY =[
        VIEW => 'vendor-views.pos.partials._cart-summary',
    ];
    const CART =[
        VIEW => 'vendor-views.pos.partials._cart',
    ];
    const INDEX =[
        URI => '/',
        VIEW => 'vendor-views.pos.index',
        ROUTE => 'vendor.pos.index',
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
        VIEW => 'vendor-views.pos.partials._quick-view'
    ];
    const SEARCH = [
        URI => 'search-product',
        VIEW => 'vendor-views.pos.partials._search-product'
    ];
}
