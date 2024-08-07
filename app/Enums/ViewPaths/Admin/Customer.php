<?php

namespace App\Enums\ViewPaths\Admin;

enum Customer
{
    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.customer.list'
    ];

    const VIEW = [
        URI => 'view',
        VIEW => 'admin-views.customer.customer-view'
    ];
    const ORDER_LIST_EXPORT = [
        URI => 'order-list-export',
    ];


    const UPDATE = [
        URI => 'status-update',
        VIEW => 'admin-views.category.category-edit'
    ];

    const DELETE = [
        URI => 'delete/{id}',
        VIEW => ''
    ];

    const SUBSCRIBER_LIST = [
        URI => 'subscriber-list',
        VIEW => 'admin-views.customer.subscriber-list'
    ];

    const SUBSCRIBER_EXPORT = [
        URI => 'subscriber-list/export',
        VIEW => ''
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

    const SEARCH = [
        URI => 'customer-list-search',
        VIEW => ''
    ];
    const SEARCH_WITHOUT_ALL_CUSTOMER = [
        URI => 'customer-list-without-all-customer',
        VIEW => ''
    ];

    const SETTINGS = [
        URI => 'customer-settings',
        VIEW => 'admin-views.customer.customer-settings'
    ];

    const LOYALTY_REPORT = [
        URI => 'report',
        VIEW => 'admin-views.customer.loyalty.report'
    ];

    const LOYALTY_EXPORT = [
        URI => 'export',
        VIEW => ''
    ];
    const ADD = [
        URI => 'add',
        VIEW => ''
    ];

}
