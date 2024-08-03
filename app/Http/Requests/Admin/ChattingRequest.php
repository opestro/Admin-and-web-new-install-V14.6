<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $image
 */
class ChattingRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'required_without:image',
            'image.*' => 'required_without:message|mimes:jpeg,png,gif,webp,jpg,jpeg',
        ];
    }

    public function messages(): array
    {
        return [
            'required_without' => translate('type_something').'!',
            'image.mimes' => translate('image_type_jpg,_jpeg,_webp_or_png'),
            'image.max' => translate('image_maximum_size_') . MAXIMUM_IMAGE_UPLOAD_SIZE,
        ];
    }

}
