<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryCountryCodeAddRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country_code' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'country_code.required' => translate('the_country_code_field_is_required'),
        ];
    }

}
