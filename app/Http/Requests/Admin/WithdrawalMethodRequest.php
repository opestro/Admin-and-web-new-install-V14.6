<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Traits\ResponseHandler;

class WithdrawalMethodRequest extends Request
{
    use ResponseHandler;
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'method_name' => 'required',
            'field_type' => 'required|array',
            'field_name' => 'required|array',
            'placeholder_text' => 'required|array',
            'is_required' => '',
            'is_default' => 'in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'method_name.required' => translate('The method name field is required.'),
            'field_type.required' => translate('The field type field is required.'),
            'field_type.array' => translate('The field type must be an array.'),
            'field_name.required' => translate('The field name field is required.'),
            'field_name.array' => translate('The field name must be an array.'),
            'placeholder_text.required' => translate('The placeholder text field is required.'),
            'placeholder_text.array' => translate('The placeholder text must be an array.'),
            'is_required.boolean' => translate('The is required field must be a boolean value.'),
            'is_default.in' => translate('The is default field must be either 0 or 1.'),
        ];
    }
}
