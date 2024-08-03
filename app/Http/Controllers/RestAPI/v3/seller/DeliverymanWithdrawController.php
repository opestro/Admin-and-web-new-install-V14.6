<?php

namespace App\Http\Controllers\RestAPI\v3\seller;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\DeliverymanWallet;
use App\Models\WithdrawRequest;
use App\Utils\Convert;
use App\Utils\Helpers;
use Illuminate\Http\Request;

class DeliverymanWithdrawController extends Controller
{
    public function list(Request $request)
    {
        $seller = $request->seller;
        $status = null;
        if($request->status == 'approved'){
            $status = 1;
        }elseif($request->status == 'denied'){
            $status = 2;
        }elseif($request->status == 'pending'){
            $status = '0';
        }

        $withdraws = WithdrawRequest::with(['deliveryMan'])
            ->where('seller_id', $seller->id)
            ->whereNotNull('delivery_man_id')
            ->when($request->status == 'all', function ($query) {
                return $query;
            })
            ->when($status!=null, function ($query) use($status){
                return $query->where('approved', $status);
            })
            ->latest()
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data = array();
        $data['total_size'] = $withdraws->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['withdraws'] = $withdraws->items();
        return response()->json($data, 200);
    }

    public function details(Request $request, $id){
        $seller = $request->seller;
        $details = WithdrawRequest::with(['deliveryMan'])
            ->where('delivery_man_id', '<>', null)
            ->where(['seller_id' => $seller->id])
            ->find($id);

        return response()->json(['details'=>$details], 200);
    }

    public function status_update(Request $request)
    {
        $id = $request->id;
        $seller = $request->seller;
        $withdraw = WithdrawRequest::where(['seller_id' => $seller->id])->find($id);
        if(!$withdraw){
            return response()->json(['message' => translate('Invalid_withdraw!')], 403);
        }
        $withdraw->approved = $request->approved;
        $withdraw->transaction_note = $request->note;
        $lang = Helpers::default_lang();

        $delivery_man = DeliveryMan::find($withdraw->delivery_man_id);
        $delivery_man_fcm_token = $delivery_man?->fcm_token;

        if(!empty($delivery_man_fcm_token)) {
            $lang = $delivery_man?->app_language ?? $lang;
            $value_delivery_man = Helpers::push_notificatoin_message('withdraw_request_status_message','delivery_man',$lang);
            if ($value_delivery_man != null) {
                $data = [
                    'title' => translate('withdraw_request_' . ($request->approved == 1 ? 'approved' : 'denied')),
                    'description' => $value_delivery_man,
                    'image' => '',
                    'type' => 'notification'
                ];
                Helpers::send_push_notif_to_device($delivery_man_fcm_token, $data);
            }
        }

        $wallet = DeliverymanWallet::where('delivery_man_id', $withdraw->delivery_man_id)->first();
        if ($request->approved == 1) {
            $wallet->total_withdraw   += Convert::usd($withdraw['amount']);
            $wallet->pending_withdraw -= Convert::usd($withdraw['amount']);
            $wallet->current_balance  -= Convert::usd($withdraw['amount']);
            $wallet->save();
            $withdraw->save();

            return response()->json(['message' => translate('Delivery_man_payment_has_been_approved_successfully!')], 200);
        }else{
            $wallet->pending_withdraw -= Convert::usd($withdraw['amount']);
            $wallet->save();
            $withdraw->save();

            return response()->json(['message' => translate('Delivery_man_payment_request_has_been_Denied_successfully!')], 200);
        }
    }
}
