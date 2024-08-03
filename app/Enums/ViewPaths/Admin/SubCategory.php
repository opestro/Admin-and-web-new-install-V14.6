<?php

namespace App\Enums\ViewPaths\Admin;

enum SubCategory
{
    const LIST = [
        URI => 'view',
        VIEW => 'admin-views.category.sub-category-view'
    ];
    const ADD = [
        URI => 'store',
        VIEW => ''
    ];
    const UPDATE = [
        URI => 'update',
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
