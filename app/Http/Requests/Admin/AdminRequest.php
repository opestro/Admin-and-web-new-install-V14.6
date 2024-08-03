<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules():array
    {
        return [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required|max:20|min:4'
        ];
    }

    /**
     * @return array
     */
    public function messages():array
    {
        return [
            'name.required' => translate('name_is_required').'!',
            'email.required' =>translate('email_is_required').'!',
            'phone.required' =>translate('phone_number_is_required').'!',
            'phone.max' => translate('please_ensure_your_phone_number_is_valid_and_does_not_exceed_20_characters'),
            'phone.min' => translate('phone_number_with_a_minimum_length_requirement_of_4_characters'),
        ];
    }
}
