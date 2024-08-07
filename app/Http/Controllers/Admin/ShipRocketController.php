<?php

namespace App\Http\Controllers\Admin;

use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Seshac\Shiprocket\Shiprocket;

class ShipRocketController extends Controller
{
    private $loginDetails;

    public function __construct()
    {
        $config = Helpers::get_business_settings('shiprocket_credentials');
        $this->loginDetails = Shiprocket::login([
            'email' => isset($config) ? $config['email'] : 'no_email@email.com',
            'password' => isset($config) ? $config['password'] : 'no_password'
        ]);

    }

    public function login(Request $request)
    {

    }

    public function index()
    {
        if ($this->loginDetails['status_code'] != 200) {
            return view('admin-views.shiprocket.login');
            dd($this->loginDetails['message']);
        }

        $orderDetails = [
            'per_page' => 20,
        ];
        $response = Shiprocket::order($this->loginDetails['token'])->getOrders($orderDetails);
    }
}
