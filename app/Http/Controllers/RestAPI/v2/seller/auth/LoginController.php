<?php

namespace App\Http\Controllers\RestAPI\v2\seller\auth;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\SellerWallet;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $seller = Seller::where(['email' => $request['email']])->first();
        if (isset($seller) && $seller['status'] == 'approved' && auth('seller')->attempt($data)) {
            $token = Str::random(50);
            Seller::where(['id' => auth('seller')->id()])->update(['auth_token' => $token]);
            if (SellerWallet::where('seller_id', $seller['id'])->first() == false) {
                DB::table('seller_wallets')->insert([
                    'seller_id' => $seller['id'],
                    'withdrawn' => 0,
                    'commission_given' => 0,
                    'total_earning' => 0,
                    'pending_withdraw' => 0,
                    'delivery_charge_earned' => 0,
                    'collected_cash' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            return response()->json(['token' => $token], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => translate('Invalid credential or account no verified yet')]);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }
}
