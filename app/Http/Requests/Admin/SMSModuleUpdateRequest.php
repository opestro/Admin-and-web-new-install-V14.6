<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * @property string $gateway
 */
class SMSModuleUpdateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gateway' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'gateway.required' => translate('the_gateway_field_is_required'),
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                collect(['status'])->each(fn($item, $key) => $this[$item] = $this->has($item) ? (int)$this[$item] : 0);

                $validation = [
                    'gateway' => 'required|in:releans,twilio,nexmo,2factor,msg91,hubtel,paradox,signal_wire,019_sms,viatech,global_sms,akandit_sms,sms_to,alphanet_sms',
                    'mode' => 'required|in:live,test'
                ];
                $additionalData = [];
                if ($this['gateway'] == 'releans') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'api_key' => 'required',
                        'from' => 'required',
                        'otp_template' => 'required'
                    ];
                } elseif ($this['gateway'] == 'twilio') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'sid' => 'required',
                        'messaging_service_sid' => 'required',
                        'token' => 'required',
                        'from' => 'required',
                        'otp_template' => 'required'
                    ];
                } elseif ($this['gateway'] == 'nexmo') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'api_key' => 'required',
                        'api_secret' => 'required',
                        'token' => 'required',
                        'from' => 'required',
                        'otp_template' => 'required'
                    ];
                } elseif ($this['gateway'] == '2factor') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'api_key' => 'required'
                    ];
                } elseif ($this['gateway'] == 'msg91') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'template_id' => 'required',
                        'auth_key' => 'required',
                    ];
                } elseif ($this['gateway'] == 'hubtel') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'sender_id' => 'required',
                        'client_id' => 'required',
                        'client_secret' => 'required',
                        'otp_template' => 'required',
                    ];
                } elseif ($this['gateway'] == 'paradox') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'api_key' => 'required',
                        'sender_id' => 'required',
                    ];
                } elseif ($this['gateway'] == 'signal_wire') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'project_id' => 'required',
                        'token' => 'required',
                        'space_url' => 'required',
                        'from' => 'required',
                        'otp_template' => 'required',
                    ];
                } elseif ($this['gateway'] == '019_sms') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'password' => 'required',
                        'username' => 'required',
                        'username_for_token' => 'required',
                        'sender' => 'required',
                        'otp_template' => 'required',
                    ];
                } elseif ($this['gateway'] == 'viatech') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'api_url' => 'required',
                        'api_key' => 'required',
                        'sender_id' => 'required',
                        'otp_template' => 'required',
                    ];
                } elseif ($this['gateway'] == 'global_sms') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'user_name' => 'required',
                        'password' => 'required',
                        'from' => 'required',
                        'otp_template' => 'required',
                    ];
                } elseif ($this['gateway'] == 'akandit_sms') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'username' => 'required',
                        'password' => 'required',
                        'otp_template' => 'required',
                    ];
                } elseif ($this['gateway'] == 'sms_to') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'api_key' => 'required',
                        'sender_id' => 'required',
                        'otp_template' => 'required',
                    ];
                } elseif ($this['gateway'] == 'alphanet_sms') {
                    $additionalData = [
                        'status' => 'required|in:1,0',
                        'api_key' => 'required',
                        'otp_template' => 'required',
                    ];
                }
                $this->validate(array_merge($validation, $additionalData));
            }
        ];
    }
}
