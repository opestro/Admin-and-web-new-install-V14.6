<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $value
 */
class PrivacyPolicyRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'value.required' => translate('the_value_field_is_required'),
        ];
    }

}
