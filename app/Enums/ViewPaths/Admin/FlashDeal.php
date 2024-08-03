<?php

namespace App\Enums\ViewPaths\Admin;

enum FlashDeal
{

    const LIST = [
        URI => 'flash',
        VIEW => 'admin-views.deal.flash-index'
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.deal.flash-update'
    ];

    const STATUS = [
        URI => 'status-update',
        VIEW => ''
    ];

    const DELETE = [
        URI => 'delete-product',
        VIEW => ''
    ];

    const ADD_PRODUCT = [
        URI => 'add-product',
        VIEW => 'admin-views.deal.add-product',
    ];

    const SEARCH = [
        URI => 'search-product',
        VIEW => 'admin-views.partials._search-product'
    ];

}
