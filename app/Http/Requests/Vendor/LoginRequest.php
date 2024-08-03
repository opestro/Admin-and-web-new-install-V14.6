<?php

namespace App\Http\Requests\Vendor;
use App\Traits\ResponseHandler;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    use ResponseHandler;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules():array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }
    /**
     * @return array
     * Get the validation error message
     */
    public function messages(): array
    {
        return [
            'email.required' => translate('The_first_name_field_is_required'),
            'password.required' => translate('The_last_name_field_is_required'),
            'password.min' => translate('The_password_must_be_at_least :min_characters', ['min' => 8]),
        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $this->errorProcessor($validator)]));
    }
}
