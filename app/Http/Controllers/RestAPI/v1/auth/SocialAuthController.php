<?php

namespace App\Http\Controllers\RestAPI\v1\auth;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\User;
use App\Utils\CartManager;
use App\Utils\Helpers;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function social_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'unique_id' => 'required',
            'medium' => 'required|in:google,facebook,apple',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $client = new Client();
        $token = $request['token'];
        $email = $request['email'];
        $unique_id = $request['unique_id'];

        try {
            if ($request['medium'] == 'google') {
                $res = $client->request('GET', 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $token);
                $data = json_decode($res->getBody()->getContents(), true);
            } elseif ($request['medium'] == 'facebook') {
                $res = $client->request('GET', 'https://graph.facebook.com/' . $unique_id . '?access_token=' . $token . '&&fields=name,email');
                $data = json_decode($res->getBody()->getContents(), true);
            } elseif ($request['medium'] == 'apple') {
                $apple_login = BusinessSetting::where(['type'=>'apple_login'])->first();
                if($apple_login){
                    $apple_login = json_decode($apple_login->value)[0];
                }
                $teamId = $apple_login->team_id;
                $keyId = $apple_login->key_id;
                $sub = $apple_login->client_id;
                $aud = 'https://appleid.apple.com';
                $iat = strtotime('now');
                $exp = strtotime('+60days');
                $keyContent = file_get_contents('storage/app/public/apple-login/'.$apple_login->service_file);

                $token = JWT::encode([
                    'iss' => $teamId,
                    'iat' => $iat,
                    'exp' => $exp,
                    'aud' => $aud,
                    'sub' => $sub,
                ], $keyContent, 'ES256', $keyId);
                $redirect_uri = $apple_login->redirect_url??'www.example.com/apple-callback';
                $res = Http::asForm()->post('https://appleid.apple.com/auth/token', [
                    'grant_type' => 'authorization_code',
                    'code' => $unique_id,
                    'redirect_uri' => $redirect_uri,
                    'client_id' => $sub,
                    'client_secret' => $token,
                ]);

                $claims = explode('.', $res['id_token'])[1];
                $data = json_decode(base64_decode($claims),true);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => translate('wrong_credential')]);
        }

        if($request['medium'] == 'apple' && isset($data['email'])){
            $fast_name = strstr($data['email'], '@', true);
            $user = User::where('email', $data['email'])->first();
            if (isset($user) == false) {
                $user = User::create([
                    'f_name' => $fast_name,
                    'email' => $data['email'],
                    'phone' => '',
                    'password' => bcrypt($data['email']),
                    'is_active' => 1,
                    'login_medium' => $request['medium'],
                    'social_id' => $data['sub'],
                    'is_phone_verified' => 0,
                    'is_email_verified' => 1,
                    'referral_code' => Helpers::generate_referer_code(),
                    'temporary_token' => Str::random(40)
                ]);
            } else {
                $user->temporary_token = Str::random(40);
                $user->save();
            }
            if(!isset($user->phone))
            {
                return response()->json([
                    'token_type' => 'update phone number',
                    'temporary_token' => $user->temporary_token ]);
            }

            $token = self::login_process_passport($user, $user['email'], $data['email']);
            if ($token != null) {

                CartManager::cart_to_db($request);
                return response()->json(['token' => $token]);
            }
            return response()->json(['error_message' => translate('customer_not_found_or_account_has_been_suspended')]);


        }elseif ( strcmp($email, $data['email']) === 0) {
            $name = explode(' ', $data['name']);
            if (count($name) > 1) {
                $fast_name = implode(" ", array_slice($name, 0, -1));
                $last_name = end($name);
            } else {
                $fast_name = implode(" ", $name);
                $last_name = '';
            }
            $user = User::where('email', $email)->first();
            if (isset($user) == false) {
                $user = User::create([
                    'f_name' => $fast_name,
                    'l_name' => $last_name,
                    'email' => $email,
                    'phone' => '',
                    'password' => bcrypt($data['id']),
                    'is_active' => 1,
                    'login_medium' => $request['medium'],
                    'social_id' => $data['id'],
                    'is_phone_verified' => 0,
                    'is_email_verified' => 1,
                    'referral_code' => Helpers::generate_referer_code(),
                    'temporary_token' => Str::random(40)
                ]);
            } else {
                $user->temporary_token = Str::random(40);
                $user->save();
            }
            if(!isset($user->phone))
            {
                return response()->json([
                    'token_type' => 'update phone number',
                    'temporary_token' => $user->temporary_token ]);
            }

            $token = self::login_process_passport($user, $user['email'], $data['id']);
            if ($token != null) {

                CartManager::cart_to_db($request);
                return response()->json(['token' => $token]);
            }
            return response()->json(['error_message' => translate('customer_not_found_or_account_has_been_suspended')]);
        }

        return response()->json(['error' => translate('email_does_not_match')]);
    }

    public static function login_process_passport($user, $email, $password)
    {
        $data = [
            'email' => $email,
            'password' => $password
        ];

        if (isset($user) && $user->is_active && auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
        } else {
            $token = null;
        }

        return $token;
    }
    public function update_phone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'temporary_token' => 'required',
            'phone' => 'required|min:11|max:14'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user = User::where(['temporary_token' => $request->temporary_token])->first();
        $user->phone = $request->phone;
        $user->save();


        $phone_verification = BusinessSetting::where('type', 'phone_verification')->first();

        if($phone_verification->value == 1)
        {
            return response()->json([
                'token_type' => 'phone verification on',
                'temporary_token' => $request->temporary_token
            ]);

        }else{
            return response()->json(['message' => translate('phone_number_updated_successfully')]);
        }
    }

}
