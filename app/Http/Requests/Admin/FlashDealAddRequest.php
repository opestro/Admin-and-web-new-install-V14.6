<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FlashDealAddRequest extends FormRequest
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
        $rules =  [
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ];
        if ($this['deal_type'] == 'flash_deal') {
            $rules['image'] = 'required';
        }
        return $rules;
    }
    public function messages(): array
    {
        return [
            'title.required' => translate('title_field_is_required'),
            'start_date.required' => translate('start_date_field_is_required'),
            'end_date.required' => translate('end_date_field_is_required'),
            'image.required' => translate('image_field_is_required'),
        ];
    }
}
