<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $type
 * @property string $image
 */
class AllPagesBannerAddRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required',
            'image' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => translate('banner_type_is_required'),
            'image.required' => translate('category_image_is_required'),
        ];
    }

}
