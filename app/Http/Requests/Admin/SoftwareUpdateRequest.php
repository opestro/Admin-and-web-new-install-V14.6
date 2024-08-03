<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $pixel_analytics
 */
class SoftwareUpdateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'update_file' => 'required|mimes:zip',
            'username' => 'required',
            'purchase_key' => 'required|uuid'
        ];
    }

    public function messages(): array
    {
        return [
            'update_file.required' => translate('update_file_is_required'),
            'update_file.mimes' => translate('update_file_must_be_a_zip_file'),
            'username.required' => translate('username_is_required'),
            'purchase_key.required' => translate('purchase_key_is_required'),
            'purchase_key.uuid' => translate('purchase_key_must_be_a_valid_uuid'),
        ];
    }

}
