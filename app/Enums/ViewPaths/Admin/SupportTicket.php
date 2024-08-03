<?php

namespace App\Enums\ViewPaths\Admin;

enum SupportTicket
{
    const LIST = [
        URI => 'view',
        VIEW => 'admin-views.support-ticket.view'
    ];

    const VIEW = [
        URI => 'single-ticket',
        VIEW => 'admin-views.support-ticket.singleView'
    ];

    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];

}
