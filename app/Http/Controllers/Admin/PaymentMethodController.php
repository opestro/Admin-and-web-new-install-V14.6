<?php

namespace App\Http\Controllers\Admin;

use App\Utils\Helpers;
use App\Enums\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\BusinessSetting;
use App\Traits\Processor;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PaymentMethodController extends Controller
{
    use Processor;

    public function index()
    {

        $payment_published_status = config('get_payment_publish_status') ?? 0;
        $payment_gateway_published_status = isset($payment_published_status[0]['is_published']) ? $payment_published_status[0]['is_published'] : 0;

        $payment_gateways = Setting::whereIn('settings_type', ['payment_config'])->whereIn('key_name', GlobalConstant::DEFAULT_PAYMENT_GATEWAYS)->get();

        $payment_gateways = $payment_gateways->sortBy(function ($item) {
            return count($item['live_values']);
        })->values()->all();

        $routes = config('addon_admin_routes');
        $desiredName = 'payment_setup';
        $payment_url = '';

        foreach ($routes as $routeArray) {
            foreach ($routeArray as $route) {
                if ($route['name'] === $desiredName) {
                    $payment_url = $route['url'];
                    break 2;
                }
            }
        }

        return view('admin-views.business-settings.payment-method.index',
            compact('payment_gateways', 'payment_gateway_published_status','payment_url'));
    }

    public function update(Request $request)
    {

        BusinessSetting::updateOrInsert(['type' => 'cash_on_delivery'], [
            'value' => json_encode(['status' => $request['cash_on_delivery'] ?? 0]),
            'updated_at' => now()
        ]);

        BusinessSetting::updateOrInsert(['type' => 'digital_payment'], [
            'value' => json_encode(['status' => $request['digital_payment'] ?? 0]),
            'updated_at' => now()
        ]);

        BusinessSetting::updateOrInsert(['type' => 'offline_payment'], [
            'value' => json_encode(['status' => $request['offline_payment'] ?? 0]),
            'updated_at' => now()
        ]);

        Toastr::success(translate('successfully_updated'));
        return back();
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function payment_config_set(Request $request)
    {
        collect(['status'])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);
        $validation = [
            'gateway' => 'required|in:ssl_commerz,sixcash,worldpay,payfast,swish,esewa,maxicash,hubtel,viva_wallet,tap,thawani,moncash,pvit,ccavenue,foloosi,iyzi_pay,xendit,fatoorah,hyper_pay,amazon_pay,paypal,stripe,razor_pay,senang_pay,paytabs,paystack,paymob_accept,paytm,flutterwave,liqpay,bkash,mercadopago,cash_after_service,digital_payment,momo',
            'mode' => 'required|in:live,test'
        ];

        $additional_data = [];

        if ($request['gateway'] == 'ssl_commerz') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'store_id' => 'required',
                'store_password' => 'required'
            ];
        } elseif ($request['gateway'] == 'paypal') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'client_id' => 'required',
                'client_secret' => 'required'
            ];
        } elseif ($request['gateway'] == 'stripe') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'published_key' => 'required',
            ];
        } elseif ($request['gateway'] == 'razor_pay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'api_secret' => 'required'
            ];
        } elseif ($request['gateway'] == 'senang_pay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'callback_url' => 'required',
                'secret_key' => 'required',
                'merchant_id' => 'required'
            ];
        } elseif ($request['gateway'] == 'paytabs') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'profile_id' => 'required',
                'server_key' => 'required',
                'base_url' => 'required'
            ];
        } elseif ($request['gateway'] == 'paystack') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'public_key' => 'required',
                'secret_key' => 'required',
                'merchant_email' => 'required'
            ];
        } elseif ($request['gateway'] == 'paymob_accept') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'callback_url' => 'required',
                'api_key' => 'required',
                'iframe_id' => 'required',
                'integration_id' => 'required',
                'hmac' => 'required'
            ];
        } elseif ($request['gateway'] == 'mercadopago') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'access_token' => 'required',
                'public_key' => 'required'
            ];
        } elseif ($request['gateway'] == 'liqpay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'private_key' => 'required',
                'public_key' => 'required'
            ];
        } elseif ($request['gateway'] == 'flutterwave') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'secret_key' => 'required',
                'public_key' => 'required',
                'hash' => 'required'
            ];
        } elseif ($request['gateway'] == 'paytm') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'merchant_key' => 'required',
                'merchant_id' => 'required',
                'merchant_website_link' => 'required'
            ];
        } elseif ($request['gateway'] == 'bkash') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'app_key' => 'required',
                'app_secret' => 'required',
                'username' => 'required',
                'password' => 'required',
            ];
        } elseif ($request['gateway'] == 'cash_after_service') {
            $additional_data = [
                'status' => 'required|in:1,0'
            ];
        } elseif ($request['gateway'] == 'digital_payment') {
            $additional_data = [
                'status' => 'required|in:1,0'
            ];
        } elseif ($request['gateway'] == 'momo') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'api_user' => 'required',
                'subscription_key' => 'required',
            ];
        } elseif ($request['gateway'] == 'hyper_pay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'entity_id' => 'required',
                'access_code' => 'required',
            ];
        } elseif ($request['gateway'] == 'amazon_pay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'pass_phrase' => 'required',
                'access_code' => 'required',
                'merchant_identifier' => 'required',
            ];
        } elseif ($request['gateway'] == 'sixcash') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'public_key' => 'required',
                'secret_key' => 'required',
                'merchant_number' => 'required',
                'base_url' => 'required',
            ];
        } elseif ($request['gateway'] == 'worldpay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'OrgUnitId' => 'required',
                'jwt_issuer' => 'required',
                'mac' => 'required',
                'merchantCode' => 'required',
                'xml_password' => 'required',
            ];
        } elseif ($request['gateway'] == 'payfast') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'merchant_id' => 'required',
                'secured_key' => 'required',
            ];
        } elseif ($request['gateway'] == 'swish') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'number' => 'required',
            ];
        } elseif ($request['gateway'] == 'esewa') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'merchantCode' => 'required',
            ];
        } elseif ($request['gateway'] == 'maxicash') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'merchantId' => 'required',
                'merchantPassword' => 'required',
            ];
        } elseif ($request['gateway'] == 'hubtel') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'account_number' => 'required',
                'api_id' => 'required',
                'api_key' => 'required',
            ];
        } elseif ($request['gateway'] == 'viva_wallet') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'client_id' => 'required',
                'client_secret' => 'required',
                'source_code' => 'required',
            ];
        } elseif ($request['gateway'] == 'tap') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'secret_key' => 'required',
            ];
        } elseif ($request['gateway'] == 'thawani') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'public_key' => 'required',
                'private_key' => 'required',
            ];
        } elseif ($request['gateway'] == 'moncash') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'client_id' => 'required',
                'secret_key' => 'required',
            ];
        } elseif ($request['gateway'] == 'pvit') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'mc_tel_merchant' => 'required',
                'access_token' => 'required',
                'mc_merchant_code' => 'required',
            ];
        } elseif ($request['gateway'] == 'ccavenue') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'merchant_id' => 'required',
                'working_key' => 'required',
                'access_code' => 'required',
            ];
        } elseif ($request['gateway'] == 'foloosi') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'merchant_key' => 'required',
            ];
        } elseif ($request['gateway'] == 'iyzi_pay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'secret_key' => 'required',
                'base_url' => 'required',
            ];
        } elseif ($request['gateway'] == 'xendit') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
            ];
        } elseif ($request['gateway'] == 'fatoorah') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
            ];
        }

        $request->validate(array_merge($validation, $additional_data));

        $settings = Setting::where('key_name', $request['gateway'])->where('settings_type', 'payment_config')->first();

        $additional_data_image = $settings['additional_data'] != null ? json_decode($settings['additional_data']) : null;

        if( !$additional_data_image || !isset($additional_data_image->gateway_image) || (isset($additional_data_image->gateway_image) && $additional_data_image->gateway_image == '') || (isset($additional_data_image->gateway_image) && !file_exists(base_path("storage/app/public/payment_modules/gateway_image/".$additional_data_image->gateway_image)))){
            $request->validate([
                'gateway_image' => 'required',
            ]);
        }
        $request->validate(['gateway_title' => 'required']);

        if ($request->has('gateway_image')) {
            $gateway_image = $this->file_uploader('payment_modules/gateway_image/', 'png', $request['gateway_image'], $additional_data_image != null ? $additional_data_image->gateway_image : '');
        } else {
            $gateway_image = $additional_data_image != null ? $additional_data_image->gateway_image : '';
        }

        $payment_additional_data = [
            'gateway_title' => $request['gateway_title'],
            'gateway_image' => $gateway_image,
        ];

        $validator = Validator::make($request->all(), array_merge($validation, $additional_data));

        Setting::updateOrCreate(['key_name' => $request['gateway'], 'settings_type' => 'payment_config'], [
            'key_name' => $request['gateway'],
            'live_values' => $validator->validate(),
            'test_values' => $validator->validate(),
            'settings_type' => 'payment_config',
            'mode' => $request['mode'],
            'is_active' => $request['status'] ?? 0,
            'additional_data' => json_encode($payment_additional_data),
        ]);

        Toastr::success(GATEWAYS_DEFAULT_UPDATE_200['message']);
        return back();
    }
}
