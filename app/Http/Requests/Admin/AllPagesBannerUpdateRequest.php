<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $type
 * @property string $image
 */
class AllPagesBannerUpdateRequest extends FormRequest
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
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => translate('banner_type_is_required'),
        ];
    }

}
