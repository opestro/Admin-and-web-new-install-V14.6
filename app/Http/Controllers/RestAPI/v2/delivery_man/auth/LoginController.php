<?php

namespace App\Http\Controllers\RestAPI\v2\delivery_man\auth;

use App\Events\DeliverymanPasswordResetEvent;
use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\PasswordReset;
use App\Utils\Helpers;
use App\Utils\SMS_module;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Gateways\Traits\SmsGateway;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        /**
         * checking if existing delivery man has a country code or not
         */

        $d_man = DeliveryMan::where(['phone' => $request->phone])->first();

        if($d_man && isset($d_man->country_code) && ($d_man->country_code != $request->country_code)){
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Invalid credential or account suspended']);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        if (isset($d_man) && $d_man['is_active'] == 1 && Hash::check($request->password, $d_man->password)) {
            $token = Str::random(50);
            $d_man->auth_token = $token;
            $d_man->save();
            return response()->json(['token' => $token], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Invalid credential or account suspended']);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }

    public function reset_password_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        /**
         * Delete previous unused reset request
         */
        PasswordReset::where(['user_type'=> 'delivery_man', 'identity'=> $request->phone])->delete();

        $delivery_man = DeliveryMan::where(['phone' => $request->phone])->first();

        if($delivery_man && isset($delivery_man->country_code) && ($delivery_man->country_code != $request->country_code)){
            return response()->json(['errors' => [
                ['code' => 'not-found', 'message' => translate('user_not_found')]
            ]], 403);
        }

        if (isset($delivery_man))
        {
            $otp = rand(1000, 9999);

            PasswordReset::insert([
                'identity' => $delivery_man->phone,
                'token' => $otp,
                'user_type' => 'delivery_man',
                'created_at' => now(),
            ]);

            $verification_by = Helpers::get_business_settings('forgot_password_verification');
            if ($verification_by == 'email') {
                $emailServices_smtp = Helpers::get_business_settings('mail_config');

                if ($emailServices_smtp['status'] == 0) {
                    $emailServices_smtp = Helpers::get_business_settings('mail_config_sendgrid');
                }
                if ($emailServices_smtp['status'] == 1) {
                    try{

                        $data = [
                            'userType' => 'delivery-man',
                            'templateName' => 'reset-password-verification',
                            'deliveryManName' => $delivery_man['f_name'],
                            'subject' => translate('OTP_Verification_for_password_reset'),
                            'title' => translate('OTP_Verification'),
                            'verificationCode' => $otp,
                        ];
                        event(new DeliverymanPasswordResetEvent(email: $delivery_man['email'],data: $data));
                    }catch(\Exception $ex)
                    {
                        return response()->json(['message' => translate('email_send_failed')], 403);
                    }

                } else {
                    return response()->json(['message' => translate('email_failed')], 200);

                }
            }elseif ($verification_by == 'phone') {

                $phone_number = $delivery_man->country_code ? '+' . $delivery_man->country_code . $delivery_man->phone : $delivery_man->phone;

                $published_status = 0;
                $payment_published_status = config('get_payment_publish_status');
                if (isset($payment_published_status[0]['is_published'])) {
                    $published_status = $payment_published_status[0]['is_published'];
                }

                if ($published_status == 1) {
                    SmsGateway::send($phone_number, $otp);
                } else {
                    SMS_module::send($phone_number, $otp);
                }
            }
            return response()->json(['message' => translate('OTP_sent_successfully._Please_check_your_email_or_phone')], 200);
        }

        return response()->json(['errors' => [
            ['code' => 'not-found', 'message' => translate('user_not_found')]
        ]], 403);
    }

    public function otp_verification_submit(Request $request){
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = PasswordReset::where(['token' => $request['otp'], 'user_type'=> 'delivery_man'])->first();

        if (!$data) {
            return response()->json(['message' => translate('Invalid_OTP')], 403);
        }

        $time_diff = $data->created_at->diffInMinutes(Carbon::now());

        if ($time_diff >2) {
            PasswordReset::where(['token' => $request['otp'], 'user_type'=> 'delivery_man'])->delete();

            return response()->json(['message' => translate('OTP_expired')], 403);
        }

        $phone = DeliveryMan::where(['phone' => $data->identity])->pluck('phone')->first();

        return response()->json(['message' => translate('OTP_verified_successfully'), 'phone'=> $phone], 200);
    }


    public function reset_password_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required|same:confirm_password|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        DeliveryMan::where(['phone' => $request['phone']])
            ->update(['password' => bcrypt(str_replace(' ', '', $request['password']))]);

        PasswordReset::where(['identity' => $request['phone'], 'user_type'=> 'delivery_man'])->delete();

        return response()->json(['message' => translate('Password_changed_successfully')], 200);

    }
}
