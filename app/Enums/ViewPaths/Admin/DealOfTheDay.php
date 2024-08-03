<?php

namespace App\Enums\ViewPaths\Admin;

enum DealOfTheDay
{

    const LIST = [
        URI => 'day',
        VIEW => 'admin-views.deal.day-index'
    ];

    const STATUS = [
        URI => 'day-status-update',
        VIEW => ''
    ];

    const UPDATE = [
        URI => 'day-update',
        VIEW => 'admin-views.deal.day-update'
    ];

    const DELETE = [
        URI => 'day-delete',
        VIEW => ''
    ];

}
