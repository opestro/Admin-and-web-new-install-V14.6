<?php

namespace App\Http\Controllers\RestAPI\v3\seller;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\DeliveryManTransaction;
use App\Models\DeliverymanWallet;
use App\Utils\BackEndHelper;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryManCashCollectController extends Controller
{

    public function list(Request $request, $id)
    {
        $seller = $request->seller;
        $delivery_man = DeliveryMan::with('wallet')->where('seller_id',$seller->id)->find($id);
        if(!$delivery_man){
            return response()->json(['message' => translate('invalid_deliveryman!')], 403);
        }
        $transactions = $delivery_man->transactions()->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data = array();
        $data['total_size'] = $transactions->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['collected_cash'] = $transactions->items();

        return response()->json($data, 200);
    }
    public function cash_receive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $id = $request->deliveryman_id;
        $seller = $request->seller;
        $lang = Helpers::default_lang();

        $wallet = DeliverymanWallet::where('delivery_man_id', $id)
            ->whereHas('deliveryMan', function($query) use($seller){
                $query->where('seller_id',$seller->id);
            })->first();

        if (empty($wallet) || BackEndHelper::currency_to_usd($request->input('amount'))  > $wallet->cash_in_hand) {
            return response()->json(['message' => translate('receive_amount_can_not_be_more_than_cash_in_hand!')], 403);
        }

        $delivery_man = DeliveryMan::find($id);
        $delivery_man_fcm_token = $delivery_man?->fcm_token;
        if(!empty($delivery_man_fcm_token)) {
            $lang = $delivery_man?->app_language ?? $lang;
            $value_delivery_man = Helpers::push_notificatoin_message('cash_collect_by_seller_message','delivery_man',$lang);
            if ($value_delivery_man != null) {
                $data = [
                    'title' => BackEndHelper::set_symbol((BackEndHelper::currency_to_usd($request->input('amount')))).' '.translate('_cash_deposit'),
                    'description' => $value_delivery_man,
                    'image' => '',
                    'type' => 'notification'
                ];
                Helpers::send_push_notif_to_device($delivery_man_fcm_token, $data);
            }
        }

        $wallet->cash_in_hand -= $request->input('amount');
        DeliveryManTransaction::create([
            'delivery_man_id' => $id,
            'user_id'         => $seller->id,
            'user_type'       => 'seller',
            'credit'           => BackEndHelper::currency_to_usd($request->input('amount')),
            'transaction_type' => 'cash_in_hand'
        ]);

        if ($wallet->save()) {
            return response()->json(['message' => translate('amount_receive_successfully!')], 200);
        }
        return response()->json(['message' => translate('amount_receive_failed')], 403);
    }
}
