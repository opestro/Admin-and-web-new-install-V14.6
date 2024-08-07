<?php

namespace App\Enums\ViewPaths\Admin;

enum Attribute
{
    const LIST = [
        URI => 'view',
        VIEW => 'admin-views.attribute.view'
    ];

    const STORE = [
        URI => 'store',
        VIEW => ''
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.attribute.edit'
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];
}
