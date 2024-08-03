<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $id
 * @property string $url
 * @property string $image
 * @property int $status
 */
class SupportTicketRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:6000'
        ];
    }

    public function messages(): array
    {
        return [
            'image.*' => translate('The_file_must_be_an_image'),
        ];
    }

}
