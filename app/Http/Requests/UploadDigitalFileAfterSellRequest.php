<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadDigitalFileAfterSellRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'digital_file_after_sell' => 'required|mimes:jpg,jpeg,png,gif,zip,pdf'
        ];
    }

    public function messages(): array
    {
        return [
            'digital_file_after_sell.required' => 'Digital file upload after sell is required',
            'digital_file_after_sell.mimes' => 'Digital file upload after sell upload must be a file of type: pdf, zip, jpg, jpeg, png, gif.',
        ];
    }
}
