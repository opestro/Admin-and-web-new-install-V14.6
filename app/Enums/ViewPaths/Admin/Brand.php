<?php

namespace App\Enums\ViewPaths\Admin;

enum Brand
{
    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.brand.list'
    ];
    const ADD = [
        URI => 'add-new',
        VIEW => 'admin-views.brand.add-new'
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.brand.edit'
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const STATUS = [
        URI => 'status-update',
        VIEW => ''
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];
}
