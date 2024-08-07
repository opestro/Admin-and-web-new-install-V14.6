<?php

namespace App\Enums\ViewPaths\Admin;

enum Contact
{

    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.contacts.list'
    ];

    const VIEW = [
        URI => 'view',
        VIEW => 'admin-views.contacts.view'
    ];
    const FILTER = [
        URI => 'filer',
        VIEW => 'admin-views.contacts._table'
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => ''
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const ADD = [
        URI => 'store',
        VIEW => ''
    ];
    const SEND_MAIL = [
        URI => 'send-mail',
        VIEW => ''
    ];

}
