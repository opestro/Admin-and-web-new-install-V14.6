<?php

namespace App\Enums\ViewPaths\Admin;

enum Chatting
{

    const INDEX = [
        URI => 'index',
        VIEW => 'admin-views.chatting.index',
    ];
    const MESSAGE = [
        URI => 'message',
        VIEW => 'admin-views.chatting.index',
    ];

    const NEW_NOTIFICATION = [
        URI => 'new-notification',
        VIEW => '',
    ];

}
