<?php

namespace App\Enums\ViewPaths\Admin;

enum Review
{
    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.reviews.list'
    ];

    const STATUS = [
        URI => 'status/{id}/{status}',
        VIEW => ''
    ];

    const SEARCH = [
        URI => 'customer-list-search',
        VIEW => ''
    ];

    const SEARCH_PRODUCT = [
        URI => 'search-product',
        VIEW => 'admin-views.partials._search-product'
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];
}
