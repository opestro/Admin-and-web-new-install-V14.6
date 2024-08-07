<?php

namespace App\Enums\ViewPaths\Admin;

enum SocialMedia
{
    const VIEW = [
        URI => 'social-media',
        VIEW => 'admin-views.business-settings.social-media'
    ];

    const LIST = [
        URI => 'fetch',
        VIEW => ''
    ];

    const ADD = [
        URI => 'social-media-store',
        VIEW => ''
    ];

    const GET_UPDATE = [
        URI => 'social-media-edit',
        VIEW => ''
    ];

    const UPDATE = [
        URI => 'social-media-update',
        VIEW => ''
    ];

    const DELETE = [
        URI => 'social-media-delete',
        VIEW => ''
    ];

    const STATUS = [
        URI => 'social-media-status-update',
        VIEW => ''
    ];
}
