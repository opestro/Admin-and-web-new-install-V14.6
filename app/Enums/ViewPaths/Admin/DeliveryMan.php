<?php

namespace App\Enums\ViewPaths\Admin;

enum DeliveryMan
{

    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.delivery-man.list'
    ];

    const ADD = [
        URI => 'add',
        VIEW => 'admin-views.delivery-man.index'
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.delivery-man.edit'
    ];

    const STATUS = [
        URI => 'status-update',
        VIEW => ''
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

    const EARNING_STATEMENT_OVERVIEW = [
        URI => 'earning-statement-overview',
        VIEW => 'admin-views.delivery-man.earning-statement.overview'
    ];

    const EARNING_OVERVIEW = [
        URI => 'order-wise-earning',
        VIEW => 'admin-views.delivery-man.earning-statement.earning'
    ];
    const ORDER_WISE_EARNING_LIST_BY_FILTER = [
        URI => 'order-list-by-filer',
        VIEW => 'admin-views.delivery-man.earning-statement._table'
    ];

    const ORDER_HISTORY_LOG = [
        URI => 'order-history-log',
        VIEW => 'admin-views.delivery-man.earning-statement.active-log'
    ];

    const ORDER_HISTORY_LOG_EXPORT = [
        URI => 'order-history-log-export',
        VIEW => ''
    ];

    const RATING = [
        URI => 'rating',
        VIEW => 'admin-views.delivery-man.rating'
    ];

    const ORDER_HISTORY = [
        URI => 'ajax-order-status-history',
        VIEW => 'admin-views.delivery-man.earning-statement._order-status-history'
    ];

}
