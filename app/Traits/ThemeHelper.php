<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait ThemeHelper
{
    public function get_theme_routes(): array
    {
        if (!defined('DOMAIN_POINTED_DIRECTORY')) {
            define('DOMAIN_POINTED_DIRECTORY', 'public');  // Define the constant if it's not already defined
        }

        $theme_routes = [];
        try {
            if (DOMAIN_POINTED_DIRECTORY == 'public') {
                if (theme_root_path() != 'default' && is_file(base_path('public/themes/'.theme_root_path().'/public/addon/theme_routes.php'))) {
                    $theme_routes = include(base_path('public/themes/'.theme_root_path().'/public/addon/theme_routes.php')); 
                }
            } else {
                if (theme_root_path() != 'default' && is_file(base_path('resources/themes/'.theme_root_path().'/public/addon/theme_routes.php'))) {
                    $theme_routes = include('resources/themes/'.theme_root_path().'/public/addon/theme_routes.php'); 
                }
            }
        } catch (\Exception $exception) {
            // Handle exception if needed
        }

        return $theme_routes;
    }
}
