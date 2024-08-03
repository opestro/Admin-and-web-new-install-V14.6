<?php

namespace App\Enums\ViewPaths\Admin;

enum SubSubCategory
{
    const LIST = [
        URI => 'view',
        VIEW => 'admin-views.category.sub-sub-category-view'
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

    const GET_SUB_CATEGORY = [
        URI => 'get-sub-category',
        VIEW => ''
    ];
    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];
}
