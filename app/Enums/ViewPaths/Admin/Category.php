<?php

namespace App\Enums\ViewPaths\Admin;

enum Category
{
    const LIST = [
        URI => 'view',
        VIEW => 'admin-views.category.view'
    ];
    const ADD = [
        URI => 'add-new',
        VIEW => 'admin-views.brand.add-new'
    ];
    const UPDATE = [
        URI => 'update/{id}',
        VIEW => 'admin-views.category.category-edit'
    ];
    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];
    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];
    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

}
