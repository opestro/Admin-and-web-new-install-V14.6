<?php

if (!function_exists('theme_asset')) {
    function theme_asset($path = null): string
    {
        $themeName = theme_root_path();
        if($themeName == 'default'){
            return dynamicAsset(path: $path);
        }else{
            if (DOMAIN_POINTED_DIRECTORY == 'public') {
                return dynamicAsset(path: 'public/themes/'.$themeName.'/public/'.$path);
            }else{
                return dynamicAsset(path: 'resources/themes/'.$themeName.'/public/'.$path);
            }
        }
    }
}

if (!function_exists('theme_root_path')) {
    function theme_root_path(): string
    {
        return env('WEB_THEME') == null ? 'default' : env('WEB_THEME');
    }
}


