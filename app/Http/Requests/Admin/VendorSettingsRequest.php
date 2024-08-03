<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $commission
 */
class VendorSettingsRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'commission' => 'required|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'commission.required' => translate('the_commission_field_is_required'),
            'commission.min' => translate('the_commission_value_field_must_be_at_least_min'),
        ];
    }

}
