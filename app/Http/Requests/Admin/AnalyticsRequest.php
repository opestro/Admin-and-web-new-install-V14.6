<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $pixel_analytics
 */
class AnalyticsRequest extends FormRequest
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
            'value' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => translate('type_is_required'),
            'value.required' => translate('id_is_required'),
        ];
    }

}
