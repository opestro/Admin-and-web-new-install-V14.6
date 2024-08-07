<?php

namespace App\Enums\ViewPaths\Admin;

enum CustomRole
{
    const ADD = [
        URI => 'add',
        VIEW => 'admin-views.custom-role.create'
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.custom-role.edit'
    ];

    const STATUS = [
        URI => 'employee-role-status',
        VIEW => ''
    ];

}
