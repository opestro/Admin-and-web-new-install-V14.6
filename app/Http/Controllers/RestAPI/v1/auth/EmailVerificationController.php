<?php

namespace App\Http\Controllers\RestAPI\v1\auth;

use App\Events\EmailVerificationEvent;
use App\Http\Controllers\Controller;
use App\Models\PhoneOrEmailVerification;
use App\User;
use App\Utils\Helpers;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmailVerificationController extends Controller
{
    public function check_email(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'temporary_token' => 'required',
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user = User::where('email', $request->email)->first();
        if($user->temporary_token != $request->temporary_token) {
            return response()->json([
                'message' => translate('temporary_token_mismatch'),
            ], 200);
        }

        $token = rand(1000, 9999);
        DB::table('phone_or_email_verifications')->insert([
            'phone_or_email' => $request['email'],
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $otp_resend_time = 0;
        $emailServices_smtp = Helpers::get_business_settings('mail_config');
        if ($emailServices_smtp['status'] == 0) {
            $emailServices_smtp = Helpers::get_business_settings('mail_config_sendgrid');
        }
        if ($emailServices_smtp['status'] == 1) {
            try{

                $data = [
                    'userName' => $user['f_name'],
                    'subject' => translate('registration_Verification_Code'),
                    'title' => translate('registration_Verification_Code'),
                    'verificationCode' => $token,
                    'userType'=>'customer' ,
                    'templateName'=> 'registration-verification',
                ];

                event(new EmailVerificationEvent(email: $user['email'],data: $data));
                $response = translate('check_your_email');
                $otp_resend_time = Helpers::get_business_settings('otp_resend_time') > 0 ? Helpers::get_business_settings('otp_resend_time') : 0;
            } catch (\Exception $exception) {
                return response()->json([
                    'message' => translate('email_is_not_configured'). translate('contact_with_the_administrator')
                ], 403);
            }
        }else{
            $response= translate('email_failed');
        }

        return response()->json([
            'message' => $response,
            'token' => 'active',
            'resend_time' => $otp_resend_time,
        ], 200);
    }

    public function resend_otp_check_email(Request $request){
        $validator = Validator::make($request->all(), [
            'temporary_token' => 'required',
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $otp_resend_time = Helpers::get_business_settings('otp_resend_time') > 0 ? Helpers::get_business_settings('otp_resend_time') : 0;
        $user = User::where(['temporary_token' => $request->temporary_token])->first();
        $token = PhoneOrEmailVerification::where('phone_or_email',$request['email'])->latest()->first();

        // Time Difference in Minutes
        $time_differance = 0;
        if($token){
            $token_time = Carbon::parse($token->created_at);
            $add_time = $token_time->addSeconds($otp_resend_time);
            $time_differance = $add_time > Carbon::now() ? Carbon::now()->diffInSeconds($add_time) : 0;
        }

        if($user && $time_differance==0){
            $generate_new_token = rand(1000, 9999);
            if($token){
                $token->token = $generate_new_token;
                $token->otp_hit_count = 0;
                $token->is_temp_blocked = 0;
                $token->temp_block_time = null;
                $token->created_at = now();
                $token->save();
            }else{
                $new_token = new PhoneOrEmailVerification();
                $new_token->phone_or_email = $user->email;
                $new_token->token = $generate_new_token;
                $new_token->created_at = now();
                $new_token->updated_at = now();
                $new_token->save();
            }

            $otp_resend_time = 0;
            $emailServices_smtp = Helpers::get_business_settings('mail_config');
            if ($emailServices_smtp['status'] == 0) {
                $emailServices_smtp = Helpers::get_business_settings('mail_config_sendgrid');
            }
            if ($emailServices_smtp['status'] == 1) {
                try{
                    $data = [
                        'userName' => $user['f_name'],
                        'subject' => translate('registration_Verification_Code'),
                        'title' => translate('registration_Verification_Code'),
                        'verificationCode' => $token,
                        'userType'=>'customer' ,
                        'templateName'=> 'registration-verification',
                    ];

                    event(new EmailVerificationEvent(email: $user['email'],data: $data));
                    $response = translate('check_your_email');
                    $otp_resend_time = Helpers::get_business_settings('otp_resend_time') > 0 ? Helpers::get_business_settings('otp_resend_time') : 0;
                } catch (\Exception $exception) {
                    return response()->json([
                        'message' => translate('email_is_not_configured'). translate('contact_with_the_administrator')
                    ], 403);
                }
            }else{
                $response= translate('email_failed');
            }

            return response()->json([
                'message' => $response,
                'token' => 'active',
                'resend_time' => $otp_resend_time,
            ], 200);

        } else {
            return response()->json([
                'message' => translate('please_try_again_after').' '.CarbonInterval::seconds($time_differance)->cascade()->forHumans(),
            ], 403);
        }

    }

    public function verify_email(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'temporary_token' => 'required',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $max_otp_hit = Helpers::get_business_settings('maximum_otp_hit') ?? 5;
        $temp_block_time = Helpers::get_business_settings('temporary_block_time') ?? 5; //minute
        $verify = PhoneOrEmailVerification::where(['phone_or_email' => $request['email'], 'token' => $request['token']])->first();

        if (isset($verify)) {
            $user = User::where(['temporary_token' => $request['temporary_token']])->first();

            if(isset($verify->temp_block_time ) && Carbon::parse($verify->temp_block_time)->diffInSeconds() <= $temp_block_time){
                $time = $temp_block_time - Carbon::parse($verify->temp_block_time)->diffInSeconds();

                return response()->json(['errors' => [
                    ['message' => translate('please_try_again_after').' '.CarbonInterval::seconds($time)->cascade()->forHumans()]
                ]], 403);
            }

            $user->email = $request['email'];
            $user->is_email_verified = 1;
            $user->save();
            $verify->delete();

            $token = $user->createToken('LaravelAuthApp')->accessToken;
            return response()->json([
                'message' => translate('otp_verified'),
                'token' => $token
            ], 200);
        }else{
            $verification = PhoneOrEmailVerification::where(['phone_or_email' => $request['email']])->first();

            if($verification){
                if(isset($verification->temp_block_time) && Carbon::parse($verification->temp_block_time)->diffInSeconds() <= $temp_block_time){
                    $time= $temp_block_time - Carbon::parse($verification->temp_block_time)->diffInSeconds();

                    $message = translate('please_try_again_after').' '.CarbonInterval::seconds($time)->cascade()->forHumans();

                }elseif($verification->is_temp_blocked == 1 && isset($verification->created_at) && Carbon::parse($verification->created_at)->diffInSeconds() >= $temp_block_time){
                    $verification->otp_hit_count = 1;
                    $verification->is_temp_blocked = 0;
                    $verification->temp_block_time = null;
                    $verification->updated_at = now();
                    $verification->save();

                    $message = translate('otp_not_found');

                }elseif($verification->otp_hit_count >= $max_otp_hit && $verification->is_temp_blocked == 0){
                    $verification->is_temp_blocked = 1;
                    $verification->temp_block_time = now();
                    $verification->updated_at = now();
                    $verification->save();

                    $time= $temp_block_time - Carbon::parse($verification->temp_block_time)->diffInSeconds();
                    $message = translate('too_many_attempts'). translate('please_try_again_after').' '.CarbonInterval::seconds($time)->cascade()->forHumans();

                }else{
                    $verification->otp_hit_count += 1;
                    $verification->save();

                    $message = translate('otp_not_found');
                }
            }else{
                $message = translate('otp_not_found');
            }
        }

        return response()->json(['errors' => [
            ['message' => $message]
        ]], 403);
    }
}
