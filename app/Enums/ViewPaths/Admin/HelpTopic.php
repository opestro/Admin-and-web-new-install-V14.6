<?php

namespace App\Enums\ViewPaths\Admin;

enum HelpTopic
{

    const LIST = [
        URI => 'index',
        VIEW => 'admin-views.help-topics.list',
    ];

    const STATUS = [
        URI => 'status',
        VIEW => '',
    ];

    const ADD = [
        URI => 'add-new',
        VIEW => '',
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => '',
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => '',
    ];
}
