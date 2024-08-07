<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BusinessSettingRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pagination_limit' => 'numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'pagination_limit.numeric' => translate('the_pagination_limit_must_be_numeric'),
        ];
    }

}
