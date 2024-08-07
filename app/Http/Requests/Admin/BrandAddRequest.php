<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @property int $id
 * @property string $name
 * @property string $image
 * @property int $status
 */
class BrandAddRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'image' => 'required|image',
            'name.0' => 'required|unique:brands,name'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => translate('the_name_field_is_required'),
            'name.0.required' => translate('the_name_field_is_required'),
            'name.0.unique' => translate('The_brand_has_already_been_taken'),
            'image.required' => translate('the_image_is_required'),
        ];
    }

}
