<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $type
 * @property string $url
 */
class LoginSetupRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required',
            'url' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => translate('The_type_is_required'),
            'url.required' => translate('The_url_is_required'),
        ];
    }

}
