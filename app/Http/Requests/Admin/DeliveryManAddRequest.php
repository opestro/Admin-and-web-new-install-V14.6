<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ResponseHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;

/**
 * Class DeliveryManAddRequest
 *
 * @property int $id
 * @property string $f_name
 * @property string $l_name
 * @property string $phone
 * @property string $email
 * @property string $country_code
 * @property string $password
 * @property string $confirm_password
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 */
class DeliveryManAddRequest extends FormRequest
{
    use ResponseHandler;
    protected $stopOnFirstFailure = false;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => 'required|max:20|min:4|unique:delivery_men',
            'email' => 'required|unique:delivery_men',
            'country_code' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png,webp,gif,bmp,tif,tiff',
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)(?!.*\s).{8,}$/|same:confirm_password',
        ];
    }

    public function messages(): array
    {
        return [
            'f_name.required' => translate('The_first_name_field_is_required'),
            'l_name.required' => translate('The_last_name_field_is_required'),
            'phone.required' => translate('The_phone_field_is_required'),
            'phone.max' => translate('please_ensure_your_phone_number_is_valid_and_does_not_exceed_20_characters'),
            'phone.min' => translate('phone_number_with_a_minimum_length_requirement_of_4_characters'),
            'email.required' => translate('The_email_field_is_required'),
            'email.unique' => translate('The_email_has_already_been_taken'),
            'phone.unique' => translate('The_phone_number_already_been_taken'),
            'country_code.required' => translate('The_country_code_field_is_required'),
            'image.required' => translate('The_image_field_is_required'),
            'image.mimes' => translate('The_image_type_must_be').'.jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff,.webp',
            'password.required' => translate('The_password_field_is_required'),
            'password.same' => translate('The_password_and_confirm_password_must_match'),
            'password.regex' => translate('The_password_must_be_at_least_8_characters_long_and_contain_at_least_one_uppercase_letter').','.translate('_one_lowercase_letter').','.translate('_one_digit_').','.translate('_one_special_character').','.translate('_and_no_spaces').'.',
            'password.min' => translate('The_password_must_be_at_least :min_characters', ['min' => 8]),
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $this->errorProcessor($validator)]));
    }
}
