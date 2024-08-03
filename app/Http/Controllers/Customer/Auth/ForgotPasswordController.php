<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Events\PasswordResetEvent;
use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\User;
use App\Utils\Helpers;
use App\Utils\SMS_module;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Gateways\Traits\SmsGateway;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:customer', ['except' => ['logout']]);
    }

    public function reset_password()
    {
        $verification_by=Helpers::get_business_settings('forgot_password_verification');

        return view(VIEW_FILE_NAMES['recover_password'], compact('verification_by'));
    }

    public function reset_password_request(Request $request)
    {
        $request->validate([
            'identity' => 'required',
        ]);

        session()->put('forgot_password_identity', $request['identity']);
        $verification_by = Helpers::get_business_settings('forgot_password_verification');
        $otp_interval_time = Helpers::get_business_settings('otp_resend_time') ?? 1; //minute

        $password_verification_data = PasswordReset::where(['user_type'=>'customer'])->where('identity', 'like', "%{$request['identity']}%")->latest()->first();
        if ($verification_by == 'email') {
            $customer = User::Where(['email' => $request['identity']])->first();
            if (isset($customer)) {
                if(isset($password_verification_data) &&  Carbon::parse($password_verification_data->created_at)->diffInSeconds() < $otp_interval_time){
                    $time= $otp_interval_time - Carbon::parse($password_verification_data->created_at)->diffInSeconds();

                    Toastr::error(translate('please_try_again_after_') .  CarbonInterval::seconds($time)->cascade()->forHumans());
                }else{
                    try{
                        $token = Str::random(120);
                        $reset_data = PasswordReset::where(['identity' => $customer['email']])->latest()->first();
                        if($reset_data){
                            $reset_data->token = $token;
                            $reset_data->created_at = now();
                            $reset_data->updated_at = now();
                            $reset_data->save();
                        }else{
                            $reset_data = new PasswordReset();
                            $reset_data->identity = $customer['email'];
                            $reset_data->token = $token;
                            $reset_data->user_type = 'customer';
                            $reset_data->created_at = now();
                            $reset_data->updated_at = now();
                            $reset_data->save();
                        }
                        $resetUrl = url('/') . '/customer/auth/reset-password?token=' . $token;
                        $data = [
                            'userType' => 'customer',
                            'templateName' => 'forgot-password',
                            'userName' => $customer['f_name'],
                            'subject' => translate('password_reset'),
                            'title' => translate('password_reset'),
                            'passwordResetURL' => $resetUrl,
                        ];
                        event(new PasswordResetEvent(email: $customer['email'],data: $data));
                        Toastr::success(translate('Check_your_email').' '.translate('Password_reset_url_sent'));
                    } catch (\Exception $exception) {
                        Toastr::error(translate('email_is_not_configured').'. '.translate('contact_with_the_administrator'));
                    }
                }

                return back();
            }
        } elseif ($verification_by == 'phone') {
            $customer = User::where('phone', 'like', "%{$request['identity']}%")->first();
            if (isset($customer)) {
                if(isset($password_verification_data) &&  Carbon::parse($password_verification_data->created_at)->diffInSeconds() < $otp_interval_time){
                    $time= $otp_interval_time - Carbon::parse($password_verification_data->created_at)->diffInSeconds();

                    Toastr::error(translate('please_try_again_after_'). CarbonInterval::seconds($time)->cascade()->forHumans());
                    return back();
                }else {
                    $token = rand(1000, 9999);
                    $reset_data = PasswordReset::where(['identity' => $customer['phone']])->latest()->first();
                    if($reset_data){
                        $reset_data->token = $token;
                        $reset_data->created_at = now();
                        $reset_data->updated_at = now();
                        $reset_data->save();
                    }else{
                        $reset_data = new PasswordReset();
                        $reset_data->identity = $customer['phone'];
                        $reset_data->token = $token;
                        $reset_data->user_type = 'customer';
                        $reset_data->created_at = now();
                        $reset_data->updated_at = now();
                        $reset_data->save();
                    }

                    $published_status = 0;
                    $payment_published_status = config('get_payment_publish_status');
                    if (isset($payment_published_status[0]['is_published'])) {
                        $published_status = $payment_published_status[0]['is_published'];
                    }

                    $response = '';
                    if($published_status == 1){
                        $response = SmsGateway::send($customer->phone, $token);
                    }else{
                        $response = SMS_module::send($customer->phone, $token);
                    }

                    if ($response == "not_found") {
                        Toastr::error(translate('SMS_configuration_missing'));
                        return back();
                    }

                    Toastr::success(translate('Check_your_phone').translate('Password_reset_OTP_sent'));
                    return redirect()->route('customer.auth.otp-verification', ['identity'=>$customer->phone]);
                }
            }
        }

        Toastr::error(translate('No_such_user_found'));
        return back();
    }

    public function ajax_resend_otp(Request $request){
        $customer = User::where('phone', 'like', '%'.$request['identity'].'%')->first();
        if ($customer) {
            $token_info = PasswordReset::where(['user_type'=>'customer', 'identity'=> $customer->phone])->first();
            $otp_interval_time = Helpers::get_business_settings('otp_resend_time') ?? 1; //minute
            if(isset($token_info) &&  Carbon::parse($token_info->created_at)->diffInSeconds() < $otp_interval_time){
                $time= $otp_interval_time - Carbon::parse($token_info->created_at)->diffInSeconds();

                return response()->json([
                    'status'=>0,
                    'message'=> translate('please_try_again_after_'). CarbonInterval::seconds($time)->cascade()->forHumans()
                ]);
            }else {
                $token = rand(1000, 9999);
                $token_info->identity = $customer['phone'];
                $token_info->token = $token;
                $token_info->otp_hit_count = 0;
                $token_info->is_temp_blocked = 0;
                $token_info->temp_block_time = null;
                $token_info->created_at = now();
                $token_info->save();

                $published_status = 0;
                $payment_published_status = config('get_payment_publish_status');
                if (isset($payment_published_status[0]['is_published'])) {
                    $published_status = $payment_published_status[0]['is_published'];
                }

                $response = '';
                if($published_status == 1){
                    $response = SmsGateway::send($customer->phone, $token);
                }else{
                    $response = SMS_module::send($customer->phone, $token);
                }

                if ($response == "not_found") {
                    return response()->json([
                        'status'=>0,
                        'message'=>translate('SMS_configuration_missing')
                    ]);
                }else{
                    return response()->json([
                        'status' => 1,
                        'new_time' => $otp_interval_time,
                        'message'=>translate('OTP_sent_successfully')
                    ]);
                }
            }
        }else{
            return response()->json([
                'status'=>0,
                'message'=>translate('invalid_user')
            ]);
        }
    }

    public function otp_verification(Request $request)
    {
        $token_info = PasswordReset::where('identity',$request['identity'])->latest()->first();
        if(!$token_info){
            return redirect()->route('customer.auth.recover-password');
        }

        $otp_resend_time = Helpers::get_business_settings('otp_resend_time') > 0 ? Helpers::get_business_settings('otp_resend_time') : 0;
        $token_time = Carbon::parse($token_info->created_at);
        $convert_time = $token_time->addSeconds($otp_resend_time);
        $time_count = $convert_time > Carbon::now() ? Carbon::now()->diffInSeconds($convert_time) : 0;

        return view(VIEW_FILE_NAMES['otp_verification'], compact('time_count'));
    }

    public function otp_verification_submit(Request $request)
    {
        $max_otp_hit = Helpers::get_business_settings('maximum_otp_hit') ?? 5;
        $temp_block_time = Helpers::get_business_settings('temporary_block_time') ?? 5; // minute
        $id = theme_root_path() == 'default' ? session('forgot_password_identity') : $request['identity'];

        $password_reset_token = PasswordReset::where(['token' => $request['otp'], 'user_type' => 'customer'])
            ->where('identity', 'like', "%{$id}%")
            ->latest()
            ->first();

        if (isset($password_reset_token)) {
            if (isset($password_reset_token->temp_block_time) && Carbon::parse($password_reset_token->temp_block_time)->diffInSeconds() <= $temp_block_time) {
                $time = $temp_block_time - Carbon::parse($password_reset_token->temp_block_time)->diffInSeconds();

                Toastr::error(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
                return redirect()->back();
            }

            $token = $request['otp'];
            return redirect()->route('customer.auth.reset-password', ['token' => $token]);

        } else {
            $password_reset = PasswordReset::where(['user_type' => 'customer'])
                ->where('identity', 'like', "%{$id}%")
                ->latest()
                ->first();

            if ($password_reset) {
                if (isset($password_reset->temp_block_time) && Carbon::parse($password_reset->temp_block_time)->diffInSeconds() <= $temp_block_time) {
                    $time = $temp_block_time - Carbon::parse($password_reset->temp_block_time)->diffInSeconds();

                    Toastr::error(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
                } elseif ($password_reset->is_temp_blocked == 1 && Carbon::parse($password_reset->created_at)->diffInSeconds() >= $temp_block_time) {
                    $password_reset->otp_hit_count = 1;
                    $password_reset->is_temp_blocked = 0;
                    $password_reset->temp_block_time = null;
                    $password_reset->updated_at = now();
                    $password_reset->save();

                    Toastr::error(translate('invalid_otp'));

                } elseif ($password_reset->otp_hit_count >= $max_otp_hit && $password_reset->is_temp_blocked == 0) {
                    $password_reset->is_temp_blocked = 1;
                    $password_reset->temp_block_time = now();
                    $password_reset->updated_at = now();
                    $password_reset->save();

                    $time = $temp_block_time - Carbon::parse($password_reset->temp_block_time)->diffInSeconds();

                    Toastr::error(translate('Too_many_attempts. please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
                } else {
                    $password_reset->otp_hit_count += 1;
                    $password_reset->save();

                    Toastr::error(translate('invalid_OTP'));
                }
            } else {
                Toastr::error(translate('invalid_OTP'));
            }

            return redirect()->back();
        }
    }

    public function reset_password_index(Request $request)
    {
        $data = DB::table('password_resets')->where('user_type','customer')->where(['token' => $request['token']])->first();
        if (isset($data)) {
            $token = $request['token'];
            return view(VIEW_FILE_NAMES['reset_password'], compact('token'));
        }
        Toastr::error(translate('Invalid_credentials'));
        return back();
    }

    public function reset_password_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|same:confirm_password',
        ]);

        $token = $request['reset_token'];
        if ($validator->fails()) {
            Toastr::error(translate('password_mismatch'));
            return view(VIEW_FILE_NAMES['reset_password'], compact('token'));
        }

        $id = session('forgot_password_identity');
        $data = DB::table('password_resets')
            ->where('user_type','customer')
            ->where('identity', 'like', "%{$id}%")
            ->where(['token' => $request['reset_token']])->first();

        if (isset($data)) {
            User::where('email', 'like', "%{$data->identity}%")
                ->orWhere('phone', 'like', "%{$data->identity}%")
                ->update([
                    'password' => bcrypt(str_replace(' ', '', $request['password']))
                ]);
            Toastr::success(translate('Password_reset_successfully'));
            DB::table('password_resets')->where('user_type','customer')->where(['token' => $request['reset_token']])->delete();
            return redirect('/');
        }
        Toastr::error(translate('Invalid_data'));
        return back();
    }
}
