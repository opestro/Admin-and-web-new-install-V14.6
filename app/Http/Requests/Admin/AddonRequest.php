<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AddonRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file_upload' => 'required|mimes:zip'
        ];
    }

    public function messages(): array
    {
        return [
            'file_upload.required' => translate('the_file_is_required'),
            'file_upload.mimes' => translate('the_file_must_be_a_zip_file'),
        ];
    }

}
