<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class ChattingRequest extends FormRequest
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
            'message' => 'required_without:image',
            'image.*' => 'required_without:message|mimes:jpeg,png,gif,webp,jpg,jpeg',
        ];
    }
    /**
     * @return array
     * Get the validation error message
     */
    public function messages(): array
    {
        return [
            'required_without' => translate('type_something').'!',
            'image.mimes' => translate('image_type_jpg,_jpeg,_webp_or_png'),
            'image.max' => translate('image_maximum_size_') . MAXIMUM_IMAGE_UPLOAD_SIZE,
        ];
    }
}
