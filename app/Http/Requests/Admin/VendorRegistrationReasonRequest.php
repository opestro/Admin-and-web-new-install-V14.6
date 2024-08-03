<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VendorRegistrationReasonRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'priority' => ['required', 'integer'],
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => translate('the_title_field_is_required'),
            'priority.required' => translate('please_select_priority'),
        ];
    }
}
