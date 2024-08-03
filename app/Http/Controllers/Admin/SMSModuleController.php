<?php

namespace App\Http\Controllers\Admin;

use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\Processor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Brian2694\Toastr\Facades\Toastr;

class SMSModuleController extends Controller
{
    use Processor;

    private $config_values;
    private $merchant_key;
    private $config_mode;
    private Setting $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    public function sms_index()
    {
        $payment_published_status = config('get_payment_publish_status') ?? 0;
        $payment_gateway_published_status = isset($payment_published_status[0]['is_published']) ? $payment_published_status[0]['is_published'] : 0;

        $sms_gateways = Setting::whereIn('settings_type', ['sms_config'])->whereIn('key_name', Helpers::default_sms_gateways())->get();

        $sms_gateways = $sms_gateways->sortBy(function ($item) {
            return count($item['live_values']);
        })->values()->all();

        return view('admin-views.business-settings.sms-index', compact('sms_gateways','payment_gateway_published_status'));
    }

    public function sms_update(Request $request, $module)
    {
        if ($module == 'twilio_sms') {
            DB::table('business_settings')->updateOrInsert(['type' => 'twilio_sms'], [
                'type' => 'twilio_sms',
                'value' => json_encode([
                    'status' => $request['status']??0,
                    'sid' => $request['sid'],
                    'messaging_service_sid' => $request['messaging_service_sid'],
                    'token' => $request['token'],
                    'from' => $request['from'],
                    'otp_template' => $request['otp_template'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($module == 'nexmo_sms') {
            DB::table('business_settings')->updateOrInsert(['type' => 'nexmo_sms'], [
                'type' => 'nexmo_sms',
                'value' => json_encode([
                    'status' => $request['status']??0,
                    'api_key' => $request['api_key'],
                    'api_secret' => $request['api_secret'],
                    'signature_secret' => '',
                    'private_key' => '',
                    'application_id' => '',
                    'from' => $request['from'],
                    'otp_template' => $request['otp_template']
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($module == '2factor_sms') {
            DB::table('business_settings')->updateOrInsert(['type' => '2factor_sms'], [
                'type' => '2factor_sms',
                'value' => json_encode([
                    'status' => $request['status']??0,
                    'api_key' => $request['api_key'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($module == 'msg91_sms') {
            DB::table('business_settings')->updateOrInsert(['type' => 'msg91_sms'], [
                'type' => 'msg91_sms',
                'value' => json_encode([
                    'status' => $request['status'],
                    'template_id' => $request['template_id'],
                    'authkey' => $request['authkey'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($module == 'releans_sms') {
            DB::table('business_settings')->updateOrInsert(['type' => 'releans_sms'], [
                'type' => 'releans_sms',
                'value' => json_encode([
                    'status' => $request['status']??0,
                    'api_key' => $request['api_key'],
                    'from' => $request['from'],
                    'otp_template' => $request['otp_template']
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($request['status'] == 1) {
            $config = Helpers::get_business_settings('twilio_sms');
            if (isset($config) && $module != 'twilio_sms') {
                DB::table('business_settings')->updateOrInsert(['type' => 'twilio_sms'], [
                    'type' => 'twilio_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'sid' => $config['sid'],
                        'token' => $config['token'],
                        'from' => $config['from'],
                        'otp_template' => $config['otp_template'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $config = Helpers::get_business_settings('nexmo_sms');
            if (isset($config) && $module != 'nexmo_sms') {
                DB::table('business_settings')->updateOrInsert(['type' => 'nexmo_sms'], [
                    'type' => 'nexmo_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'api_key' => $config['api_key'],
                        'api_secret' => $config['api_secret'],
                        'signature_secret' => '',
                        'private_key' => '',
                        'application_id' => '',
                        'from' => $config['from'],
                        'otp_template' => $config['otp_template']
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $config = Helpers::get_business_settings('2factor_sms');
            if (isset($config) && $module != '2factor_sms') {
                DB::table('business_settings')->updateOrInsert(['type' => '2factor_sms'], [
                    'type' => '2factor_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'api_key' => $config['api_key'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $config = Helpers::get_business_settings('msg91_sms');
            if (isset($config) && $module != 'msg91_sms') {
                DB::table('business_settings')->updateOrInsert(['type' => 'msg91_sms'], [
                    'type' => 'msg91_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'template_id' => $config['template_id'],
                        'authkey' => $config['authkey'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $config = Helpers::get_business_settings('releans_sms');
            if (isset($config) && $module != 'releans_sms') {
                DB::table('business_settings')->updateOrInsert(['type' => 'releans_sms'], [
                    'type' => 'releans_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'api_key' => $request['api_key'],
                        'from' => $request['from'],
                        'otp_template' => $request['otp_template']
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return back();
    }

    public function sms_config_set(Request $request): RedirectResponse
    {
        collect(['status'])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);

        $validation = [
            'gateway' => 'required|in:releans,twilio,nexmo,2factor,msg91,hubtel,paradox,signal_wire,019_sms,viatech,global_sms,akandit_sms,sms_to,alphanet_sms',
            'mode' => 'required|in:live,test'
        ];
        $additional_data = [];
        if ($request['gateway'] == 'releans') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'from' => 'required',
                'otp_template' => 'required'
            ];
        } elseif ($request['gateway'] == 'twilio') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'sid' => 'required',
                'messaging_service_sid' => 'required',
                'token' => 'required',
                'from' => 'required',
                'otp_template' => 'required'
            ];
        } elseif ($request['gateway'] == 'nexmo') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'api_secret' => 'required',
                'token' => 'required',
                'from' => 'required',
                'otp_template' => 'required'
            ];
        } elseif ($request['gateway'] == '2factor') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required'
            ];
        } elseif ($request['gateway'] == 'msg91') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'template_id' => 'required',
                'auth_key' => 'required',
            ];
        } elseif ($request['gateway'] == 'hubtel') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'sender_id' => 'required',
                'client_id' => 'required',
                'client_secret' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == 'paradox') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'sender_id' => 'required',
            ];
        } elseif ($request['gateway'] == 'signal_wire') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'project_id' => 'required',
                'token' => 'required',
                'space_url' => 'required',
                'from' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == '019_sms') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'password' => 'required',
                'username' => 'required',
                'username_for_token' => 'required',
                'sender' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == 'viatech') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_url' => 'required',
                'api_key' => 'required',
                'sender_id' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == 'global_sms') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'user_name' => 'required',
                'password' => 'required',
                'from' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == 'akandit_sms') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'username' => 'required',
                'password' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == 'sms_to') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'sender_id' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == 'alphanet_sms') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'otp_template' => 'required',
            ];
        }

        $validation = $request->validate(array_merge($validation, $additional_data));

        $this->setting->updateOrCreate(['key_name' => $request['gateway'], 'settings_type' => 'sms_config'], [
            'key_name' => $request['gateway'],
            'live_values' => $validation,
            'test_values' => $validation,
            'settings_type' => 'sms_config',
            'mode' => $request['mode'],
            'is_active' => $request['status'],
        ]);

        if ($request['status'] == 1) {
            foreach (['releans','twilio','nexmo','2factor','msg91','hubtel','paradox','signal_wire','019_sms','viatech','global_sms','akandit_sms','sms_to','alphanet_sms'] as $gateway) {
                if ($request['gateway'] != $gateway) {
                    $keep = $this->setting->where(['key_name' => $gateway, 'settings_type' => 'sms_config'])->first();
                    if (isset($keep)) {
                        $hold = $keep->live_values;
                        $hold['status'] = 0;
                        $this->setting->where(['key_name' => $gateway, 'settings_type' => 'sms_config'])->update([
                            'live_values' => $hold,
                            'test_values' => $hold,
                            'is_active' => 0,
                        ]);
                    }
                }
            }
        }

        Toastr::success(GATEWAYS_DEFAULT_UPDATE_200['message']);
        return back();
    }
}
