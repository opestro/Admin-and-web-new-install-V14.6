<?php

namespace App\Enums\ViewPaths\Admin;

enum Banner
{
    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.banner.view'
    ];

    const ADD = [
        URI => 'add',
        VIEW => ''
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.banner.edit',
        ROUTE => 'admin.banner.list'
    ];
}
