<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerWallet;
use Illuminate\Support\Facades\DB;

class RewardPointController extends Controller
{
    public function convert()
    {
        $wallet = CustomerWallet::where(['customer_id' => auth('customer')->id()])->first();
        if ($wallet['royality_points'] != 0) {
            CustomerWallet::where(['customer_id' => auth('customer')->id()])->increment('balance', $wallet['royality_points'] * 10);
            CustomerWallet::where(['customer_id' => auth('customer')->id()])->decrement('royality_points', $wallet['royality_points']);
            DB::table('customer_wallet_histories')->insert([
                'customer_id'=>auth('customer')->id(),
                'transaction_amount'=>$wallet['royality_points'] * 10,
                'transaction_type'=>'points_to_balance',
                'created_at'=>now(),
                'updated_at'=>now()
            ]);

            //to do

            return 1;
        }

        //to do

        return 0;
    }
}
