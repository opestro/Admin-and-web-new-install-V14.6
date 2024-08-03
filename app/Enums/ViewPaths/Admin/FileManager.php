<?php

namespace App\Enums\ViewPaths\Admin;

enum FileManager
{
    const VIEW = [
        URI => 'index',
        VIEW => 'admin-views.file-manager.index',
    ];

    const DOWNLOAD = [
        URI => 'download',
        VIEW => '',
    ];

    const IMAGE_UPLOAD = [
        URI => 'image-upload',
        VIEW => '',
    ];

}
