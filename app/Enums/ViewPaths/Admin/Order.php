<?php

namespace App\Enums\ViewPaths\Admin;

enum Order
{
    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.order.list'
    ];

    const EXPORT_EXCEL = [
        URI => 'export-excel',
        VIEW => ''
    ];

    const GENERATE_INVOICE = [
        URI => 'generate-invoice',
        VIEW => 'admin-views.order.invoice'
    ];

    const VIEW = [
        URI => 'details',
        VIEW => 'admin-views.order.order-details'
    ];

    const VIEW_POS = [
        URI => '',
        VIEW => 'admin-views.pos.order.order-details'
    ];

    const UPDATE_ADDRESS = [
        URI => 'address-update',
        VIEW => ''
    ];

    const UPDATE_DELIVERY_INFO = [
        URI => 'update-deliver-info',
        VIEW => ''
    ];

    const ADD_DELIVERY_MAN = [
        URI => 'add-delivery-man',
        VIEW => ''
    ];

    const UPDATE_AMOUNT_DATE = [
        URI => 'amount-date-update',
        VIEW => ''
    ];

    const CUSTOMERS = [
        URI => 'customers',
        VIEW => ''
    ];

    const PAYMENT_STATUS = [
        URI => 'payment-status',
        VIEW => ''
    ];

    const IN_HOUSE_ORDER_FILTER = [
        URI => 'inhouse-order-filter',
        VIEW => ''
    ];

    const DIGITAL_FILE_UPLOAD_AFTER_SELL = [
        URI => 'digital-file-upload-after-sell',
        VIEW => ''
    ];

    const UPDATE_STATUS = [
        URI => 'status',
        VIEW => ''
    ];
    const GET_DATA = [
        URI => 'get-order-data',
        VIEW => ''
    ];
}
