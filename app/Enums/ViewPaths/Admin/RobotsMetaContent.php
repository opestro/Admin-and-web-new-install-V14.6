<?php

namespace App\Enums\ViewPaths\Admin;

enum RobotsMetaContent
{

    const ROBOTS_META_CONTENT = [
        URI => '',
        VIEW => 'admin-views.business-settings.seo-settings.robots-meta-content'
    ];

    const ADD_PAGE = [
        URI => 'add-page',
        VIEW => ''
    ];

    const DELETE_PAGE = [
        URI => 'delete-page',
        VIEW => ''
    ];

    const PAGE_CONTENT_VIEW = [
        URI => 'page-content-view',
        VIEW => 'admin-views.business-settings.seo-settings.robots-meta-content-view'
    ];

    const PAGE_CONTENT_UPDATE = [
        URI => 'page-content-update',
        VIEW => ''
    ];

}
