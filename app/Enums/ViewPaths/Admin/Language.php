<?php

namespace App\Enums\ViewPaths\Admin;

enum Language
{
    const LIST = [
        URI => '/',
        VIEW => 'admin-views.business-settings.language.index'
    ];

    const ADD = [
        URI => 'add',
        VIEW => ''
    ];

    const TRANSLATE_VIEW = [
        URI => 'translate',
        VIEW => 'admin-views.business-settings.language.translate',
    ];

    const STATUS = [
        URI => 'update-status',
        VIEW => '',
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => '',
    ];

    const DEFAULT_STATUS = [
        URI => 'update-default-status',
        VIEW => ''
    ];

    const TRANSLATE_LIST = [
        URI => 'translate-list',
        VIEW => ''
    ];

    const TRANSLATE_ADD = [
        URI => 'translate-submit',
        VIEW => ''
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const TRANSLATE_REMOVE = [
        URI => 'remove-key',
        VIEW => ''
    ];

    const TRANSLATE_AUTO = [
        URI => 'auto-translate',
        VIEW => ''
    ];

}
