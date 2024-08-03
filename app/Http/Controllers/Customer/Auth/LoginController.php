<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\ProductCompare;
use App\Models\Wishlist;
use App\User;
use App\Utils\CartManager;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public $company_name;

    public function __construct()
    {
        $this->middleware('guest:customer', ['except' => ['logout']]);
    }

    public function captcha(Request $request, $tmp)
    {

        $phrase = new PhraseBuilder;
        $code = $phrase->build(4);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        $builder->build($width = 100, $height = 40, $font = null);
        $phrase = $builder->getPhrase();

        if(Session::has($request->captcha_session_id)) {
            Session::forget($request->captcha_session_id);
        }
        Session::put($request->captcha_session_id, $phrase);
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }

    public function login()
    {
        session()->put('keep_return_url', url()->previous());

        if(theme_root_path() == 'default'){
            return view('web-views.customer-views.auth.login');
        }else{
            return redirect()->route('home');
        }
    }

    public function submit(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'password' => 'required'
        ]);

        //recaptcha validation start
        $recaptcha = Helpers::get_business_settings('recaptcha');
        if (isset($recaptcha) && $recaptcha['status'] == 1) {
            try {
                $request->validate([
                    'g-recaptcha-response' => [
                        function ($attribute, $value, $fail) {
                            $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
                            $response = $value;
                            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                            $response = \file_get_contents($url);
                            $response = json_decode($response);
                            if (!$response->success) {
                                $fail(translate('ReCAPTCHA Failed'));
                            }
                        },
                    ],
                ]);
            } catch (\Exception $exception) {}
        } else {
            if (strtolower($request['default_recaptcha_id_customer_login']) != strtolower(Session('default_recaptcha_id_customer_login'))) {
                if($request->ajax()) {
                    return response()->json([
                        'status'=>'error',
                        'message'=>translate('Captcha_Failed.'),
                        'redirect_url'=>''
                    ]);
                }else {
                    Session::forget('default_recaptcha_id_customer_login');
                    Toastr::error(translate('captcha_failed'));
                    return back();
                }
            }
        }
        //recaptcha validation end

        $user = User::where(['phone' => $request->user_id])->orWhere(['email' => $request->user_id])->first();
        $remember = ($request['remember']) ? true : false;

        //login attempt check start
        $max_login_hit = Helpers::get_business_settings('maximum_login_hit') ?? 5;
        $temp_block_time = Helpers::get_business_settings('temporary_login_block_time') ?? 5; //seconds
        if (isset($user) == false) {
            if($request->ajax()) {
                return response()->json([
                    'status'=>'error',
                    'message'=>translate('credentials_doesnt_match'),
                    'redirect_url'=>''
                ]);
            }else{
                Toastr::error(translate('credentials_doesnt_match'));
                return back()->withInput();
            }
        }
        //login attempt check end

        //phone or email verification check start
        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');
        if ($phone_verification && !$user->is_phone_verified) {
            if($request->ajax()) {
                return response()->json([
                    'status'=>'error',
                    'message'=>translate('account_phone_not_verified'),
                    'redirect_url'=>route('customer.auth.check', [$user->id]),
                ]);
            }else{
                return redirect(route('customer.auth.check', [$user->id]));
            }
        }
        if ($email_verification && !$user->is_email_verified) {
            if($request->ajax()) {
                return response()->json([
                    'status'=>'error',
                    'message'=>translate('account_email_not_verified'),
                    'redirect_url'=>route('customer.auth.check', [$user->id]),
                ]);
            }else{
                return redirect(route('customer.auth.check', [$user->id]));
            }
        }
        //phone or email verification check end

        if(isset($user->temp_block_time ) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time){
            $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();

            if($request->ajax()) {
                return response()->json([
                    'status'=>'error',
                    'message'=>translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                    'redirect_url'=>''
                ]);
            }else{
                Toastr::error(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
                return back()->withInput();
            }
        }

        if (isset($user) && auth('customer')->attempt(['email' => $user['email'], 'password' => $request['password']], $remember)) {

            if (!$user->is_active) {
                auth()->guard('customer')->logout();
                if($request->ajax()) {
                    return response()->json([
                        'status'=>'error',
                        'message'=>translate('your_account_is_suspended'),
                    ]);
                }else{
                    Toastr::error(translate('your_account_is_suspended'));
                    return back()->withInput();
                }
            }

            $wish_list = Wishlist::whereHas('wishlistProduct',function($q){
                return $q;
            })->where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray();

            $compare_list = ProductCompare::where('user_id', auth('customer')->id())->pluck('product_id')->toArray();

            session()->forget('wish_list');
            session()->forget('compare_list');
            session()->put('wish_list', $wish_list);
            session()->put('compare_list', $compare_list);
            Toastr::info(translate('welcome_to') .' '. Helpers::get_business_settings('company_name') . '!');
            CartManager::cart_to_db();

            $user->login_hit_count = 0;
            $user->is_temp_blocked = 0;
            $user->temp_block_time = null;
            $user->updated_at = now();
            $user->save();

            $redirect_url = "";
            $previous_url = url()->previous();

            if (
                strpos($previous_url,'checkout-complete') !== false ||
                strpos($previous_url,'offline-payment-checkout-complete') !== false ||
                strpos($previous_url,'track-order') !== false
            ) {
                $redirect_url = route('home');
            }

            if($request->ajax()) {
                return response()->json([
                    'status'=>'success',
                    'message'=>translate('login_successful'),
                    'redirect_url'=> $redirect_url,
                ]);
            }else{
                return redirect(session('keep_return_url'));
            }

        }else{

            //login attempt check start
            if(isset($user->temp_block_time ) && Carbon::parse($user->temp_block_time)->diffInSeconds() <= $temp_block_time){
                $time= $temp_block_time - Carbon::parse($user->temp_block_time)->diffInSeconds();

                $ajax_message = [
                    'status'=>'error',
                    'message'=> translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                    'redirect_url'=>''
                ];
                Toastr::error(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());

            }elseif($user->is_temp_blocked == 1 && Carbon::parse($user->temp_block_time)->diffInSeconds() >= $temp_block_time){

                $user->login_hit_count = 0;
                $user->is_temp_blocked = 0;
                $user->temp_block_time = null;
                $user->updated_at = now();
                $user->save();

                $ajax_message = [
                    'status'=>'error',
                    'message'=> translate('credentials_doesnt_match'),
                    'redirect_url'=>''
                ];
                Toastr::error(translate('credentials_doesnt_match'));

            }elseif($user->login_hit_count >= $max_login_hit &&  $user->is_temp_blocked == 0){
                $user->is_temp_blocked = 1;
                $user->temp_block_time = now();
                $user->updated_at = now();
                $user->save();

                $time= $temp_block_time - Carbon::parse($user->temp_block_time)->diffInSeconds();

                $ajax_message = [
                    'status'=>'error',
                    'message'=> translate('too_many_attempts._please_try_again_after_'). CarbonInterval::seconds($time)->cascade()->forHumans(),
                    'redirect_url'=>''
                ];
                Toastr::error(translate('too_many_attempts._please_try_again_after_'). CarbonInterval::seconds($time)->cascade()->forHumans());
            }else{
                $ajax_message = [
                    'status'=>'error',
                    'message'=> translate('credentials_doesnt_match'),
                    'redirect_url'=>''
                ];
                Toastr::error(translate('credentials_doesnt_match'));

                $user->login_hit_count += 1;
                $user->save();
            }
            //login attempt check end

            if($request->ajax()) {
                return response()->json($ajax_message);
            }else{
                return back()->withInput();
            }
        }
    }

    public function logout(Request $request)
    {
        auth()->guard('customer')->logout();
        session()->forget('wish_list');
        Toastr::info(translate('come_back_soon').'!');
        return redirect()->route('home');
    }

    public function get_login_modal_data(Request $request)
    {
        return response()->json([
            'login_modal' => view(VIEW_FILE_NAMES['get_login_modal_data'])->render(),
            'register_modal' => view(VIEW_FILE_NAMES['get_register_modal_data'])->render(),
        ]);
    }


}
