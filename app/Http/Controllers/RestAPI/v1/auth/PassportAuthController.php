<?php

namespace App\Http\Controllers\RestAPI\v1\auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Utils\CartManager;
use App\Utils\Helpers;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PassportAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:8',
        ], [
            'f_name.required' => 'The first name field is required.',
            'l_name.required' => 'The last name field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($request->referral_code){
            $refer_user = User::where(['referral_code' => $request->referral_code])->first();
        }

        $temporary_token = Str::random(40);
        $user = User::create([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => 1,
            'password' => bcrypt($request->password),
            'temporary_token' => $temporary_token,
            'referral_code' => Helpers::generate_referer_code(),
            'referred_by' => (isset($refer_user) && $refer_user) ? $refer_user->id : null,
        ]);

        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');
        if ($phone_verification && !$user->is_phone_verified) {
            return response()->json(['temporary_token' => $temporary_token], 200);
        }
        if ($email_verification && !$user->is_email_verified) {
            return response()->json(['temporary_token' => $temporary_token], 200);
        }

        $token = $user->createToken('LaravelAuthApp')->accessToken;
        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6',
            'guest_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user_id = $request['email'];
        if (filter_var($user_id, FILTER_VALIDATE_EMAIL)) {
            $medium = 'email';
        } else {
            $count = strlen(preg_replace("/[^\d]/", "", $user_id));
            if ($count >= 9 && $count <= 15) {
                $medium = 'phone';
            } else {
                $errors = [];
                $errors[] = ['code' => 'email', 'message' => translate('credentials_doesnt_match')];
                return response()->json([
                    'errors' => $errors
                ], 403);
            }
        }

        $data = [
            $medium => $user_id,
            'password' => $request->password
        ];

        $user = User::where([$medium => $user_id])->first();
        $max_login_hit = Helpers::get_business_settings('maximum_login_hit') ?? 5;
        $temp_block_time = Helpers::get_business_settings('temporary_login_block_time') ?? 5; //minute

        if (isset($user)) {
            $user->temporary_token = Str::random(40);
            $user->save();

            $phone_verification = Helpers::get_business_settings('phone_verification');
            $email_verification = Helpers::get_business_settings('email_verification');
            if ($phone_verification && !$user->is_phone_verified) {
                return response()->json(['temporary_token' => $user->temporary_token], 200);
            }
            if ($email_verification && !$user->is_email_verified) {
                return response()->json(['temporary_token' => $user->temporary_token], 200);
            }

            if(isset($user->temp_block_time ) && Carbon::parse($user->temp_block_time)->diffInSeconds() <= $temp_block_time){
                $time = $temp_block_time - Carbon::parse($user->temp_block_time)->diffInSeconds();

                $errors = [];
                $errors[] = ['code' => 'auth-001', 'message' => translate('please_try_again_after').' '.CarbonInterval::seconds($time)->cascade()->forHumans()];
                return response()->json([
                    'errors' => $errors
                ], 401);
            }
            if (!$user->is_active) {
                $errors = [];
                $errors[] = ['code' => 'auth-001', 'message' => translate('your_account_is_suspended')];
                return response()->json([
                    'errors' => $errors
                ], 401);
            }

            if(auth()->attempt($data)){
                $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;

                $user->login_hit_count = 0;
                $user->is_temp_blocked = 0;
                $user->temp_block_time = null;
                $user->updated_at = now();
                $user->save();

                CartManager::cart_to_db($request);

                return response()->json(['token' => $token], 200);
            }else{
                //login attempt check start
                if(isset($user->temp_block_time ) && Carbon::parse($user->temp_block_time)->diffInSeconds() <= $temp_block_time){
                    $time= $temp_block_time - Carbon::parse($user->temp_block_time)->diffInSeconds();

                    $errors = [];
                    $errors[] = ['code' => 'auth-001', 'message' => translate('please_try_again_after').' '.CarbonInterval::seconds($time)->cascade()->forHumans()];
                    return response()->json([
                        'errors' => $errors
                    ], 401);

                }elseif($user->is_temp_blocked == 1 && Carbon::parse($user->temp_block_time)->diffInSeconds() >= $temp_block_time){

                    $user->login_hit_count = 0;
                    $user->is_temp_blocked = 0;
                    $user->temp_block_time = null;
                    $user->updated_at = now();
                    $user->save();

                    $errors = [];
                    $errors[] = ['code' => 'auth-001', 'message' => translate('credentials_doesnt_match')];
                    return response()->json([
                        'errors' => $errors
                    ], 401);

                }elseif($user->login_hit_count >= $max_login_hit &&  $user->is_temp_blocked == 0){
                    $user->is_temp_blocked = 1;
                    $user->temp_block_time = now();
                    $user->updated_at = now();
                    $user->save();

                    $time= $temp_block_time - Carbon::parse($user->temp_block_time)->diffInSeconds();

                    $errors = [];
                    $errors[] = ['code' => 'auth-001', 'message' => translate('too_many_attempts'). translate('please_try_again_after').' '.CarbonInterval::seconds($time)->cascade()->forHumans()];
                    return response()->json([
                        'errors' => $errors
                    ], 401);

                }else{

                    $user->login_hit_count += 1;
                    $user->save();

                    $errors = [];
                    $errors[] = ['code' => 'auth-001', 'message' => translate('credentials_doesnt_match')];
                    return response()->json([
                        'errors' => $errors
                    ], 401);
                }
                //login attempt check end
            }
        } else {
            $errors = [];
            $errors[] = ['code' => 'auth-001', 'message' => translate('credentials_doesnt_match')];
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        if(auth()->check()) {
            auth()->user()->token()->revoke();
            return response()->json(['message' => translate('logged_out_successfully')], 200);
        }
        return response()->json(['message'=>translate('logged_out_fail')], 403);
    }
}
