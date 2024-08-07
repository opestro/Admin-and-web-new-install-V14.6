<?php

namespace App\Http\Controllers;

use App\Utils\Helpers;
use App\Models\Admin;
use App\Models\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => 'logout']);
    }

    public function login($login_url)
    {
        $data = array_column(BusinessSetting::whereIn('type',['employee_login_url','admin_login_url'])->get(['type','value'])->toArray(), 'value', 'type');

        $loginTypes = [
            'admin' => 'admin_login_url',
            'employee' => 'employee_login_url'
        ];

        $role = null;

        $user_type = array_search($login_url,$data);
        abort_if(!$user_type, 404 );
        $role = array_search($user_type,$loginTypes,true);
        abort_if($role == null ,404);

        $custome_recaptcha = new CaptchaBuilder;
        $custome_recaptcha->build();
        Session::put('six_captcha', $custome_recaptcha->getPhrase());

        if ($role == 'admin') {
            return view('admin-views.auth.login', compact('custome_recaptcha','role'));
        }else if ($role == 'employee') {
            return view('admin-views.auth.login', compact('custome_recaptcha','role'));
        }

    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $recaptcha = Helpers::get_business_settings('recaptcha');
        if (isset($recaptcha) && $recaptcha['status'] == 1) {
            $request->validate([
                'g-recaptcha-response' => [
                    function ($attribute, $value, $fail) {
                        $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
                        $response = $value;
                        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                        $response = Http::get($url);
                        $response = $response->json();
                        if (!isset($response['success']) || !$response['success']) {
                            $fail(translate('ReCAPTCHA_Failed'));
                        }
                    },
                ],
            ]);
        } else if(strtolower(session('default_recaptcha_id_'.$request->role.'_login')) != strtolower($request->default_captcha_value))
        {
            Toastr::error(translate('ReCAPTCHA_Failed'));
            return back();
        }

        if ($request->role == 'admin') {
            $data = Admin::where('email', $request->email)->where('admin_role_id', 1)->first();

            if (!isset($data)) {
                return redirect()->back()->withInput($request->only('email', 'remember'))
                            ->withErrors(['Credentials does not match.']);
            }else if (isset($data) && $data->status != 1) {
                return redirect()->back()->withInput($request->only('email', 'remember'))
                    ->withErrors(['You are blocked!!, contact with admin.']);
            }
        } elseif ($request->role == 'employee') {

            $data = Admin::where('email', $request->email)->where('admin_role_id','!=' ,1)->first();

            if (!isset($data)) {
                return redirect()->back()->withInput($request->only('email', 'remember'))
                            ->withErrors(['Credentials does not match.']);
            }else if (isset($data) && $data->status != 1) {
                return redirect()->back()->withInput($request->only('email', 'remember'))
                    ->withErrors(['You are blocked!!, contact with admin.']);
            }

        } else {
            Toastr::error(translate('role_missing'));
            return back();
        }

        $data = $this->login_attemp($request->role, $request->email, $request->password, $request->remember);

        if ($data == 'admin' || $data == 'employee') {
            return redirect()->route('admin.dashboard.index');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors(['Credentials does not match.']);
    }

    public function login_attemp($role,$email ,$password, $remember = false)
    {
        if ($role == 'admin' || $role == 'employee') {
            if (auth('admin')->attempt(['email' => $email, 'password' => $password], $remember)) {
                return $role;
            }
        }
        return false;
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


}
