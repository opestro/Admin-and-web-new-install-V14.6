<?php

namespace App\Enums\ViewPaths\Admin;

enum RefundRequest
{
    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.refund.list',
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => 'admin-views.refund.list',
    ];

    const DETAILS = [
        URI => 'details',
        VIEW => 'admin-views.refund.details',
    ];

    const UPDATE_STATUS = [
        URI => 'refund-status-update',
        VIEW => '',
    ];

}
