<?php

return [
    'name' => 'Aster',
    'route' => '',
    'url' => 'javascript:',
    'icon' => '<i class="fa-solid fa-screwdriver-wrench"></i>',
    'index' => 0,
    'path'=> 'theme_route',
    'route_list' => [
        [
            'name' => 'Promotional_Banners',
            'route' => 'admin.banner.list',
            'module_permission' => 'promotion_management',
            'url' => url('/') . '/admin/banner/list',
            'icon' => '<i class="tio-photo-square-outlined nav-icon"></i>',
            'path'=>'admin/banner/list',
            'route_list' => []
        ],
        [
            'name' => 'All_Pages_Banner',
            'route' => 'admin.business-settings.all-pages-banner',
            'module_permission' => 'promotion_management',
            'url' => url('/') . '/admin/business-settings/all-pages-banner',
            'icon' => '<i class="tio-shop nav-icon"></i>',
            'path'=>'admin/business-settings/all-pages-banner',
            'route_list' => []
        ],
        [
            'name' => 'In-House_Store_Banner',
            'route' => 'admin.product-settings.inhouse-shop',
            'module_permission' => 'system_settings',
            'url' => url('/') . '/admin/product-settings/inhouse-shop',
            'icon' => '<i class="tio-shop nav-icon"></i>',
            'path'=>'admin/product-settings/inhouse-shop',
            'route_list' => []
        ],
    ]
];
