<?php

namespace App\Http\Requests\Admin;

use App\Traits\ResponseHandler;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

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
class DeliveryManUpdateRequest extends FormRequest
{
    use ResponseHandler;
    protected $stopOnFirstFailure = false;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('delivery_men', 'email')->ignore($this->route('id')),
            ],
            'phone' => 'required|max:20|min:4',
            'country_code' => 'required',
        ];
        if ($this['password']) {
            $rules['password'] = 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)(?!.*\s).{8,}$/|same:confirm_password';
        }
        return $rules;
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
            'country_code.required' => translate('The_country_code_field_is_required'),
            'password.regex' => translate('The_password_must_be_at_least_8_characters_long_and_contain_at_least_one_uppercase_letter').','.translate('_one_lowercase_letter').','.translate('_one_digit_').','.translate('_one_special_character').','.translate('_and_no_spaces').'.',
        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $this->errorProcessor($validator)]));
    }
}
