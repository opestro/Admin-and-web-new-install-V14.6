<?php

namespace App\Enums\ViewPaths\Admin;

enum SiteMap
{
    const SITEMAP = [
        URI => 'sitemap',
        VIEW => 'admin-views.business-settings.seo-settings.sitemap'
    ];

    const GENERATE_AND_DOWNLOAD = [
        URI => 'sitemap-generate-download',
        VIEW => ''
    ];

    const GENERATE_AND_UPLOAD = [
        URI => 'sitemap-generate-upload',
        VIEW => ''
    ];

    const UPLOAD = [
        URI => 'sitemap-manual-upload',
        VIEW => ''
    ];

    const DOWNLOAD = [
        URI => 'sitemap-download',
        VIEW => ''
    ];

    const DELETE = [
        URI => 'sitemap-delete',
        VIEW => ''
    ];

}
