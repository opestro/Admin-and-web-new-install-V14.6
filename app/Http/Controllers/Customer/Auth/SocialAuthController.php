<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Wishlist;
use App\User;
use App\Utils\CartManager;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Session;

class SocialAuthController extends Controller
{
    public function redirectToProvider(Request $request, $service)
    {
        return Socialite::driver($service)->redirect();
    }

    public function handleProviderCallback($service)
    {
        $user_data = Socialite::driver($service)->stateless()->user();

        $user = User::where('email', $user_data->getEmail())->first();

        $name = explode(' ', $user_data['name']);
        if (count($name) > 1) {
            $fast_name = implode(" ", array_slice($name, 0, -1));
            $last_name = end($name);
        } else {
            $fast_name = implode(" ", $name);
            $last_name = '';
        }

        if (isset($user) == false) {
            $user = User::create([
                'f_name' => $fast_name,
                'l_name' => $last_name,
                'email' => $user_data->getEmail(),
                'phone' => '',
                'password' => bcrypt($user_data->id),
                'is_active' => 1,
                'login_medium' => $service,
                'social_id' => $user_data->id,
                'is_phone_verified' => 0,
                'is_email_verified' => 1,
                'referral_code' => Helpers::generate_referer_code(),
                'temporary_token' => Str::random(40)
            ]);
        } else {
            $user->temporary_token = Str::random(40);
            $user->save();
        }

        /*if ($user->phone == '') {
            return redirect()->route('customer.auth.update-phone', $user->id);
        }*/
        //redirect if website user
        $message = self::login_process($user, $user_data->getEmail(), $user_data->id);
        Toastr::info($message);
        return redirect()->route('home');
    }

    public function editPhone($id)
    {
        $user = User::find($id);
        return view('customer-view.auth.update-phone', compact('user'));
    }

    public function updatePhone(Request $request)
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => 'required|unique:users|min:11',
        ], [
            'f_name.required' => translate('first_name_is_required'),
            'l_name.required' => translate('last_name_is_required'),
            'phone.required' => translate('phone_number_is_required'),
            'unique' => translate('phone_number_must_be_unique').'!',
            'phone.min' => translate('phone_number_should_be_minimum_of_11_character')
        ]);

        $user = User::find($request->id);
        $user->f_name = $request->f_name;
        $user->l_name = $request->l_name;
        $user->phone = $request->phone;
        $user->is_active = 1;
        $user->save();

        return redirect(route('customer.auth.check', [$user->id]));
    }

    public static function login_process($user, $email, $password)
    {
        $company_name = BusinessSetting::where('type', 'company_name')->first();

        if ($user->is_active) {
            auth('customer')->login($user);
            $wish_list = Wishlist::whereHas('wishlistProduct',function($q){
                return $q;
            })->where('customer_id', $user->id)->pluck('product_id')->toArray();

            session()->forget('wish_list');
            session()->put('wish_list', $wish_list);
            $message = translate('welcome_to').' ' . $company_name->value . '!';
            CartManager::cart_to_db();
        } else {
            $message = translate('credentials_are_not_matched_or_your_account_is_not_active').'!';
        }

        return $message;
    }
}
