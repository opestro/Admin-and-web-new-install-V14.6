<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $about_us
 */
class AboutUsRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'about_us' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'about_us.required' => translate('the_value_field_is_required'),
        ];
    }

}
