<?php

namespace App\Http\Requests\Admin;

use App\Enums\GlobalConstant;
use Illuminate\Foundation\Http\FormRequest;
/**
 * @property string $image
 */
class ChattingRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'required_without_all:file,image',
            'image.*' => 'image|max:2048|mimes:'.str_replace('.', '', implode(',', GlobalConstant::IMAGE_EXTENSION)),
            'file.*' => 'file|max:2048|mimes:'.str_replace('.', '', implode(',', GlobalConstant::DOCUMENT_EXTENSION)),
        ];

    }

    public function messages(): array
    {
        return [
            'required_without_all' => translate('type_something').'!',
            'image.mimes' => translate('the_image_format_is_not_supported').' '.translate('supported_format_are').' '.str_replace('.', '', implode(',', GlobalConstant::IMAGE_EXTENSION)),
            'image.max' => translate('image_maximum_size_') . MAXIMUM_IMAGE_UPLOAD_SIZE,
            'file.mimes' => translate('the_file_format_is_not_supported').' '.translate('supported_format_are').' '.str_replace('.', '', implode(',', GlobalConstant::DOCUMENT_EXTENSION)),
            'file.max' => translate('file_maximum_size_') . MAXIMUM_IMAGE_UPLOAD_SIZE,
        ];
    }

}
