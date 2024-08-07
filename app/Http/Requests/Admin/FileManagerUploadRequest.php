<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @property-read string $images
 * @property-read string $file
 * @property-read string $path
 */
class FileManagerUploadRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => 'required_without:file',
            'file' => 'required_without:images',
            'path' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'images.required_without' => translate('either_images_or_file_required'),
            'file.required_without' => translate('either_images_or_file_required'),
            'path.required' => translate('the_path_is_required'),
        ];
    }

}
