<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $product_id
 * @property string $image
 */
class MostDemandedRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required',
            'image'      => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => translate('the_product_field_is_required'),
            'image.required' => translate('the_image_is_required'),
        ];
    }

}
