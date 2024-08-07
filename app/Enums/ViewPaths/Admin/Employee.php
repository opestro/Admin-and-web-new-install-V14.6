<?php

namespace App\Enums\ViewPaths\Admin;

enum Employee
{
    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.employee.list'
    ];

    const ADD = [
        URI => 'add',
        VIEW => 'admin-views.employee.add-new'
    ];

    const VIEW = [
        URI => 'view',
        VIEW => 'admin-views.employee.view'
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.employee.edit'
    ];

    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];

}
