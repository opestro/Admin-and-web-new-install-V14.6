<?php

namespace App\Enums\ViewPaths\Vendor;

enum Chatting
{
    const INDEX = [
        URI => 'index',
        VIEW => 'vendor-views.chatting.index',
    ];
    const MESSAGE = [
        URI => 'message',
        VIEW => 'vendor-views.chatting.index',
    ];

    const NEW_NOTIFICATION = [
        URI => 'new-notification',
        VIEW => '',
    ];
}
