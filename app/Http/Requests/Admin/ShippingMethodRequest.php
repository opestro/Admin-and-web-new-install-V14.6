<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ShippingMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules():array
    {
        return [
            'title' => 'required|max:200',
            'duration' => 'required',
            'cost' => 'numeric'
        ];
    }
    /**
     * @return array
     * Get the validation error message
     */
    public function messages(): array
    {
        return [
            'title.required'=>translate('the_title_field_is_required'),
            'duration.required'=>translate('the_duration_field_is_required'),
            'cost.numeric'=>translate('the_cost_must_be_a_number')
        ];
    }
}
