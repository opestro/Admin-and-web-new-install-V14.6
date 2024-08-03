<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class EmergencyContactRequest extends FormRequest
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
            'name' => 'required',
            'country_code' => 'required',
            'phone' => 'required|max:20|min:4'
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => translate('name_is_required'),
            'country_code.required' => translate('country_code_is_required'),
            'phone.required' => translate('phone_is_required'),
            'phone.max' => translate('please_ensure_your_phone_number_is_valid_and_does_not_exceed_20_characters'),
            'phone.min' => translate('phone_number_with_a_minimum_length_requirement_of_4_characters'),
        ];
    }
}
