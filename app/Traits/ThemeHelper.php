<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait ThemeHelper
{
    public function get_theme_routes(): array
    {
        $theme_routes = [];
        try {
            if (theme_root_path() != 'default' && is_file(base_path('resources/themes/'.theme_root_path().'/public/addon/theme_routes.php'))) {
                $theme_routes = include('resources/themes/'.theme_root_path().'/public/addon/theme_routes.php'); // theme_root_path()
            }
        } catch (\Exception $exception) {

        }

        return $theme_routes;
    }
}
