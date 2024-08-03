<?php

namespace App\Http\Requests\Vendor;

use App\Enums\SessionKey;
use App\Traits\CalculatorTrait;
use App\Traits\RecaptchaTrait;
use App\Traits\ResponseHandler;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Validator;

class VendorAddRequest extends FormRequest
{
    use RecaptchaTrait;
    use CalculatorTrait, ResponseHandler;

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => 'required|unique:sellers|max:20',
            'email' => 'required|unique:sellers',
            'image' => 'required|mimes:jpg,jpeg,png,webp,gif,bmp,tif,tiff',
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)(?!.*\s).{8,}$/|same:confirm_password',
            'shop_name' => 'required',
            'shop_address' => 'required',
            'logo' => 'required|mimes: jpg,jpeg,png,webp,gif,bmp,tif,tiff',
            'banner' => 'required|mimes: jpg,jpeg,png,webp,gif,bmp,tif,tiff',
            'bottom_banner' => 'mimes: jpg,jpeg,png,webp,gif,bmp,tif,tiff',
        ];
    }

    public function messages(): array
    {
        return [
            'f_name.required' => translate('The_first_name_field_is_required'),
            'l_name.required' => translate('The_last_name_field_is_required'),
            'phone.required' => translate('The_phone_field_is_required'),
            'phone.unique' => translate('The_phone_number_has_already_been_taken'),
            'phone.max' => translate('please_ensure_your_phone_number_is_valid_and_does_not_exceed_20_characters'),
            'email.required' => translate('The_email_field_is_required'),
            'email.unique' => translate('The_email_has_already_been_taken'),
            'image.required' => translate('The_image_field_is_required'),
            'image.mimes' => translate('The_image_type_must_be') . '.jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff,.webp',
            'password.required' => translate('The_password_field_is_required'),
            'password.same' => translate('The_password_and_confirm_password_must_match'),
            'password.regex' => translate('The_password_must_be_at_least_8_characters_long_and_contain_at_least_one_uppercase_letter') . ',' . translate('_one_lowercase_letter') . ',' . translate('_one_digit_') . ',' . translate('_one_special_character') . ',' . translate('_and_no_spaces') . '.',
            'shop_name.required' => translate('The_shop_name_field_is_required'),
            'shop_address.required' => translate('The_shop_address_field_is_required'),
            'logo.mimes' => translate('The_logo_type_must_be') . '.jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff,.webp',
            'banner.mimes' => translate('The_banner_type_must_be') . '.jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff,.webp',
            'bottom_banner.mimes' => translate('The_bottom_banner_type_must_be') . '.jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff,.webp',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $recaptcha = getWebConfig(name: 'recaptcha');
                if (isset($recaptcha) && $recaptcha['status'] == 1) {
                    if (!$this['g-recaptcha-response'] || !$this->isGoogleRecaptchaValid($this['g-recaptcha-response'])) {
                        $validator->errors()->add(
                            'recaptcha', translate('ReCAPTCHA_Failed') . '!'
                        );
                    }
                } else if ($recaptcha['status'] != 1 && strtolower($this['default_recaptcha_id_seller_regi']) != strtolower(Session(SessionKey::VENDOR_RECAPTCHA_KEY))) {
                    $validator->errors()->add(
                        'g-recaptcha-response', translate('ReCAPTCHA_Failed') . '!'
                    );
                } else if ($recaptcha['status'] != 1 && strtolower($this['default_recaptcha_id_seller_regi']) == strtolower(Session(SessionKey::VENDOR_RECAPTCHA_KEY))) {
                    Session::forget(SessionKey::VENDOR_RECAPTCHA_KEY);
                }

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
            }
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $this->errorProcessor($validator)]));
    }
}
