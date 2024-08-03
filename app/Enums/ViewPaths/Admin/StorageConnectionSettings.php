<?php

namespace App\Enums\ViewPaths\Admin;

enum StorageConnectionSettings
{
    const INDEX = [
        URI => 'index',
        VIEW => 'admin-views.business-settings.storage-connection-settings.index'
    ];

    const STORAGE_TYPE = [
        URI => 'update-storage-type',
        VIEW => ''
    ];

    const S3_STORAGE_CREDENTIAL = [
        URI => 's3-credential',
        VIEW => ''
    ];
}
