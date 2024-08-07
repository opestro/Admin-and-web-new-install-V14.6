<?php

namespace App\Services;

class SocialMediaService
{
    public function getIcon(object $request): string
    {
        if ($request['name'] == 'google-plus') {
            $icon = 'fa fa-google-plus-square';
        }else if ($request['name'] == 'facebook') {
            $icon = 'fa fa-facebook';
        }else if ($request['name'] == 'twitter') {
            $icon = 'fa fa-twitter';
        }else if ($request['name'] == 'pinterest') {
            $icon = 'fa fa-pinterest';
        }else if ($request['name'] == 'instagram') {
            $icon = 'fa fa-instagram';
        }else if ($request['name'] == 'linkedin') {
            $icon = 'fa fa-linkedin';
        }else{
            $icon = '';
        }

        return $icon;
    }

}
