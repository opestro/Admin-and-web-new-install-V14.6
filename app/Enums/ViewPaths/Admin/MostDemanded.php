<?php

namespace App\Enums\ViewPaths\Admin;

enum MostDemanded
{
    const LIST = [
        URI => '/',
        VIEW => 'admin-views.theme-features.most-demanded.view'
    ];

    const ADD = [
        URI => 'store',
        VIEW => ''
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.theme-features.most-demanded.edit'
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];

}
