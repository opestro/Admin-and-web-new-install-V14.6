<?php

namespace App\Services;

use App\Traits\FileManagerTrait;

class BannerService
{
    use FileManagerTrait;

    public function getProcessedData(object $request, string $image = null): array
    {
        if ($image) {
            $imageName = $request->file('image') ? $this->update(dir:'banner/', oldImage:$image, format: 'webp', image: $request->file('image')) : $image;
        }else {
            $imageName = $this->upload(dir:'banner/', format: 'webp', image: $request->file('image'));
        }

        return [
            'banner_type' => $request['banner_type'],
            'resource_type' => $request['resource_type'],
            'resource_id' => $request[$request->resource_type . '_id'],
            'theme' => theme_root_path(),
            'title' => $request['title'],
            'sub_title' => $request['sub_title'],
            'button_text' => $request['button_text'],
            'background_color' => $request['background_color'],
            'url' => $request['url'],
            'photo' => $imageName,
        ];
    }

    public function getBannerTypes(): array
    {
        $bannerTypes = [];
        if (theme_root_path() == 'default') {
            $bannerTypes = [
                "Main Banner" => translate('main_Banner'),
                "Popup Banner" => translate('popup_Banner'),
                "Footer Banner" => translate('footer_Banner'),
                "Main Section Banner" => translate('main_Section_Banner')
            ];

        }elseif (theme_root_path() == 'theme_aster') {
            $bannerTypes = [
                "Main Banner" => translate('main_Banner'),
                "Popup Banner" => translate('popup_Banner'),
                "Footer Banner" => translate('footer_Banner'),
                "Main Section Banner" => translate('main_Section_Banner'),
                "Header Banner" => translate('header_Banner'),
                "Sidebar Banner" => translate('sidebar_Banner'),
                "Top Side Banner" => translate('top_Side_Banner'),
            ];
        }elseif (theme_root_path() == 'theme_fashion') {
            $bannerTypes = [
                "Main Banner" => translate('main_Banner'),
                "Popup Banner" => translate('popup_Banner'),
                "Promo Banner Left" => translate('promo_banner_left'),
                "Promo Banner Middle Top" => translate('promo_banner_middle_top'),
                "Promo Banner Middle Bottom" => translate('promo_banner_middle_bottom'),
                "Promo Banner Right" => translate('promo_banner_right'),
                "Promo Banner Bottom" => translate('promo_banner_bottom'),
            ];
        }

        return $bannerTypes;
    }

}
