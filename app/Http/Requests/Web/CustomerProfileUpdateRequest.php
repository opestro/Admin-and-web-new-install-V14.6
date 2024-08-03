<?php

namespace App\Http\Requests\Web;

use App\Traits\CalculatorTrait;
use App\Traits\RecaptchaTrait;
use App\Traits\ResponseHandler;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CustomerProfileUpdateRequest extends FormRequest
{
    use RecaptchaTrait;
    use CalculatorTrait, ResponseHandler;

    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => [
                'required',
                'max:20',
                Rule::unique('users', 'phone')->ignore(auth('customer')->id(), 'id'),
            ],
            'image' => 'mimes:jpg,jpeg,png,webp,gif,bmp,tif,tiff',
        ];
    }

    public function messages(): array
    {
        return [
            'f_name.required' => translate('first_name_is_required'),
            'l_name.required' => translate('last_name_is_required'),
            'phone.required' => translate('phone_number_is_required'),
            'phone.unique' => translate('phone_number_already_has_been_taken'),
            'phone.max' => translate('The_phone_number_may_not_be_greater_than_20_characters'),
            'image.mimes' => translate('The_image_type_must_be') . '.jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff,.webp',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $numericPhoneValue = preg_replace('/[^0-9]/', '', $this['phone']);
                $numericLength = strlen($numericPhoneValue);
                if ($numericLength < 4) {
                    $validator->errors()->add(
                        'phone.min', translate('The_phone_number_must_be_at_least_4_characters')
                    );
                }

                if ($numericLength > 20) {
                    $validator->errors()->add(
                        'phone.max', translate('The_phone_number_may_not_be_greater_than_20_characters')
                    );
                }

                if ($this['password'] && !empty($this['password']) && empty($this['confirm_password'])) {
                    $validator->errors()->add(
                        'confirm_password.required', translate('confirm_password_is_required')
                    );
                } elseif ($this['confirm_password'] && !empty($this['confirm_password']) && empty($this['password'])) {
                    $validator->errors()->add(
                        'password.required', translate('password_is_required')
                    );
                } elseif ($this['password'] != $this['confirm_password']) {
                    $validator->errors()->add(
                        'password.same:confirm_password', translate('passwords_must_match_with_confirm_password')
                    );
                }

            }
        ];
    }
}
