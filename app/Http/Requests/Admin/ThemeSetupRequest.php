<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $theme_upload
 */
class ThemeSetupRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'theme_upload' => 'required|mimes:zip'
        ];
    }

    public function messages(): array
    {
        return [
            'theme_upload.required' => translate('The_theme_upload_is_required.'),
            'theme_upload.mimes' => translate('The_theme_must_be_a_ZIP_file.'),
        ];
    }

}
