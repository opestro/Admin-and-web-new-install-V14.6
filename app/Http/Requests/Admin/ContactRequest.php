<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile_number' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'mobile_number.required' => translate('mobile_number_is_required'),
            'subject.required' => translate('subject_is_empty'),
            'message.required' => translate('message_is_empty'),
        ];
    }

}
