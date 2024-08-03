<?php

namespace App\Enums\ViewPaths\Admin;

enum AddonSetup
{
    const VIEW = [
        URI => '',
        VIEW => 'admin-views.addons.index'
    ];

    const PUBLISH = [
        URI => 'publish',
        VIEW => ''
    ];

    const ACTIVATION = [
        URI => 'activation',
        VIEW => ''
    ];

    const UPLOAD = [
        URI => 'upload',
        VIEW => 'admin-views.attribute.edit'
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const ACTIVE_MODAL = [
        URI => '',
        VIEW => 'admin-views.addons.partials.activation-modal-data'
    ];
}
