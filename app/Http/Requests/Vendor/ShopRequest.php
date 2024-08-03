<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules():array
    {
        return [
            'banner' => 'mimes:png,jpg,webp,jpeg,gif|max:2048',
            'image' => 'mimes:png,jpg,webp,jpeg,gif|max:2048',
            'bottomBanner' => 'mimes:png,jpg,webp,jpeg,gif|max:2048',
            'offerBanner' => 'mimes:png,jpg,webp,jpeg,gif|max:2048',
        ];
    }
    public function messages():array
    {
        return [
            'banner.mimes' => translate('banner_image_type_jpg,_jpeg,_webp_or_png'),
            'banner.max' => translate('banner_maximum_size_') . MAXIMUM_IMAGE_UPLOAD_SIZE,
            'image.mimes' => translate('image_type_jpg,_jpeg,_webp_or_png'),
            'image.max' => translate('image_maximum_size_') . MAXIMUM_IMAGE_UPLOAD_SIZE,
            'bottomBanner.mimes' => translate('bottom_banner_type_jpg,_jpeg,_webp_or_png'),
            'bottomBanner.max' => translate('bottom_banner_maximum_size_') . MAXIMUM_IMAGE_UPLOAD_SIZE,
            'offerBanner.mimes' => translate('offer_banner_type_jpg,_jpeg,_webp_or_png'),
            'offerBanner.max' => translate('offer_banner_maximum_size_') . MAXIMUM_IMAGE_UPLOAD_SIZE,
        ];
    }
}
