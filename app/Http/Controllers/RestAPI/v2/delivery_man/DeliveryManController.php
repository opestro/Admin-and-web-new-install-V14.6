<?php

namespace App\Http\Controllers\RestAPI\v2\delivery_man;

use App\Events\OrderStatusEvent;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\DeliveryHistory;
use App\Models\DeliveryMan;
use App\Models\DeliverymanNotification;
use App\Models\DeliveryManTransaction;
use App\Models\DeliverymanWallet;
use App\Models\EmergencyContact;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Review;
use App\Traits\CommonTrait;
use App\User;
use App\Utils\BackEndHelper;
use App\Utils\CustomerManager;
use App\Utils\Helpers;
use App\Utils\ImageManager;
use App\Utils\OrderManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class DeliveryManController extends Controller
{
    use CommonTrait;
    public function __construct(
        private Order $order,
    ){

    }

    public function info(Request $request)
    {
        $d_man = $request['delivery_man'];
        $delivery_man = DeliveryMan::where(['id' => $d_man['id']])->with(['review'])->first();
        $wallet = DeliverymanWallet::where('delivery_man_id', $request['delivery_man']['id'])->first();
        $withdrawable_balance = CommonTrait::delivery_man_withdrawable_balance($request['delivery_man']['id']);
        $total_earn = CommonTrait::delivery_man_total_earn($request['delivery_man']['id']);
        $order = Order::where('delivery_man_id', $request['delivery_man']['id'])->get();
        $completed_delivery = $order->where('order_status', 'delivered')->count();
        $pause_delivery = $order->where('is_pause', 1)->count();
        $pending_delivery = $order->where('order_status', 'pending')->count();
        $total_deposit = DeliveryManTransaction::where(['delivery_man_id' => $request['delivery_man']['id'], 'transaction_type' => 'cash_in_hand'])->sum('credit');

        $request['delivery_man']['withdrawable_balance'] = $withdrawable_balance;
        $request['delivery_man']['current_balance'] = $wallet->current_balance ?? 0;
        $request['delivery_man']['cash_in_hand'] = $wallet->cash_in_hand ?? 0;
        $request['delivery_man']['pending_withdraw'] = $wallet->pending_withdraw ?? 0;
        $request['delivery_man']['total_withdraw'] = $wallet->total_withdraw ?? 0;
        $request['delivery_man']['total_earn'] = $total_earn;
        $request['delivery_man']['completed_delivery'] = $completed_delivery;
        $request['delivery_man']['pending_delivery'] = $pending_delivery;
        $request['delivery_man']['total_delivery'] = $order->count();
        $request['delivery_man']['pause_delivery'] = $pause_delivery;
        $request['delivery_man']['total_deposit'] = $total_deposit;
        $request['delivery_man']['average_rating'] = count($delivery_man->rating)>0?number_format($delivery_man->rating[0]->average, 2, '.', ' '):0;

        return response()->json($request['delivery_man'], 200);
    }

    public function get_current_orders(Request $request)
    {
        $d_man = $request['delivery_man'];
        $orders = Order::with(['shippingAddress', 'customer', 'seller.shop'])
            ->whereIn('order_status', ['pending', 'processing', 'out_for_delivery', 'confirmed'])
            ->where(['delivery_man_id' => $d_man['id']])
            ->orderBy('expected_delivery_date', 'asc')
            ->get();
        return response()->json($orders, 200);
    }

    public function record_location_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'location' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $d_man = $request['delivery_man'];
        DB::table('delivery_histories')->insert([
            'order_id' => $request['order_id'],
            'deliveryman_id' => $d_man['id'],
            'longitude' => $request['longitude'],
            'latitude' => $request['latitude'],
            'time' => now(),
            'location' => $request['location'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['message' => 'location recorded'], 200);
    }

    public function get_order_history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $d_man = $request['delivery_man'];
        $history = DeliveryHistory::where(['order_id' => $request['order_id'], 'deliveryman_id' => $d_man['id']])->get();
        return response()->json($history, 200);
    }

    public function update_order_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'status' => 'required|in:delivered,canceled,returned,out_for_delivery'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $d_man = $request['delivery_man'];
        $cause = null;
        if ($request['status'] == 'canceled') {
            $cause = $request['cause'];
        }

        $order = Order::with(['customer','deliveryMan'])->where(['delivery_man_id' => $d_man['id'], 'id' => $request['order_id']])->first();

        if ($order->order_status == 'delivered') {
            return response()->json(['success' => 0, 'message' => 'order is already delivered.'], 200);
        }

        Order::where(['id' => $request['order_id'], 'delivery_man_id' => $d_man['id']])->update([
            'order_status' => $request['status'],
            'cause' => $cause
        ]);

        if (isset($d_man['id']) && $request['status'] == 'delivered') {
            $dm_wallet = DeliverymanWallet::where('delivery_man_id', $d_man['id'])->first();
            $cash_in_hand = $order->payment_method == 'cash_on_delivery' ? $order->order_amount : 0;

            if (empty($dm_wallet)) {
                DeliverymanWallet::create([
                    'delivery_man_id' => $d_man['id'],
                    'current_balance' => BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0,
                    'cash_in_hand' => BackEndHelper::currency_to_usd($cash_in_hand),
                    'pending_withdraw' => 0,
                    'total_withdraw' => 0,
                ]);
            } else {
                $dm_wallet->cash_in_hand += BackEndHelper::currency_to_usd($cash_in_hand);
                $dm_wallet->current_balance += BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0;
                $dm_wallet->save();
            }
        }
        if ($request['status'] == 'out_for_delivery') {
            event(new OrderStatusEvent(key: 'out_for_delivery', type: 'customer', order: $order));
        } elseif ($request['status'] == 'delivered') {
            event(new OrderStatusEvent(key: 'delivered', type: 'customer', order: $order));
        } elseif ($request['status'] == 'canceled') {
            event(new OrderStatusEvent(key: 'canceled', type: 'delivery_man', order: $order));
        }

        OrderManager::stock_update_on_order_status_change($order, $request['status']);

        if ($request['status'] == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'delivery man');
            OrderDetail::where('order_id', $order->id)->update(
                ['delivery_status' => 'delivered']
            );
        }

        $ref_earning_status = BusinessSetting::where('type', 'ref_earning_status')->first()->value ?? 0;
        $ref_earning_exchange_rate = BusinessSetting::where('type', 'ref_earning_exchange_rate')->first()->value ?? 0;

        $wallet_status = Helpers::get_business_settings('wallet_status');
        if(!$order->is_guest && $wallet_status == 1 && $ref_earning_status == 1 && $request['status'] == 'delivered' && $order->payment_status =='paid'){

            $customer = User::find($order->customer_id);
            $is_first_order = Order::where(['customer_id'=>$order->customer_id,'order_status'=>'delivered','payment_status'=>'paid'])->count();
            $referred_by_user = User::find($customer->referred_by);

            if ($is_first_order == 1 && isset($customer->referred_by) && isset($referred_by_user)){
                CustomerManager::create_wallet_transaction($referred_by_user->id, floatval($ref_earning_exchange_rate), 'add_fund_by_admin', 'earned_by_referral');
            }
        }

        CommonTrait::add_order_status_history($order->id, $d_man['id'], $request['status'], 'delivery_man', $request['cause']);

        return response()->json(['message' => 'Order status updated successfully!'], 200);
    }

    public function update_expected_delivery(Request $request){
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'expected_delivery_date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $d_man = $request['delivery_man'];
        $order = Order::with(['deliveryMan'])->where(['delivery_man_id' => $d_man['id'], 'id' => $request['order_id']])->first();

        if ($order->order_status == 'delivered') {
            return response()->json(['success' => 0, 'message' => 'order is already delivered.'], 200);
        }

        $order->expected_delivery_date = $request['expected_delivery_date'];
        $order->cause = $request['cause'];
        $order->save();
        CommonTrait::add_expected_delivery_date_history($order->id, $d_man['id'], $request['expected_delivery_date'], 'delivery_man', $request['cause']);

        OrderStatusEvent::dispatch('order_rescheduled_message', 'delivery_man', $order);

        return response()->json(['message' => 'Order status updated successfully!'], 200);
    }

    public function order_update_is_pause(Request $request){
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'is_pause' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $d_man = $request['delivery_man'];
        $order = Order::where(['delivery_man_id' => $d_man['id'], 'id' => $request['order_id']])->first();

        if ($order->order_status == 'delivered') {
            return response()->json(['success' => 0, 'message' => 'order is already delivered.'], 200);
        }

        $order->is_pause = $request['is_pause'];
        $order->cause = $request['cause'];
        $order->save();

        return response()->json(['message' => 'Order status updated successfully!'], 200);
    }

    public function get_order_details(Request $request)
    {
        $d_man = $request['delivery_man'];
        $order = Order::with(['details'])->where(['delivery_man_id' => $d_man['id'], 'id' => $request['order_id']])->first();
        $details = $order->details;
        foreach ($details as $detail) {
            $detail['is_pause'] = $order['is_pause'];
            $detail['variation'] = json_decode($detail['variation']);
            $detail['product_details'] = Helpers::product_data_formatting(json_decode($detail['product_details'], true));
        }
        return response()->json($details, 200);
    }

    public function get_all_orders(Request $request)
    {
        $delivery_man = $request['delivery_man'];

        $orders = Order::with(['shippingAddress', 'customer', 'seller.shop'])
            ->where(['delivery_man_id'=> $delivery_man->id])
            ->when(!empty($request->search), function($query) use($request){
                $query->where('id', 'like', "%{$request->search}%")
                ->orWhere(function ($query) use($request){
                    $query->whereHas('customer', function($query) use($request){
                        $query->where('phone', 'like', "%{$request->search}%");
                    });
                });
            })
            ->when(!empty($request->status), function ($query)use($request){
                $query->where('order_status', $request['status']);
            })
            ->when(!empty($request->is_pause), function ($query)use($request){
                $query->where('is_pause', $request['is_pause']);
            })
            ->when(!empty($request->start_date) && !empty($request->end_date), function ($query) use($request){
                $start_date = Carbon::parse($request['start_date'])->format('Y-m-d 00:00:00');
                $end_data = Carbon::parse($request['end_date'])->format('Y-m-d 23:59:59');

                $query->whereBetween('created_at', [$start_date, $end_data]);
            })
            ->latest()->get();

        return response()->json($orders, 200);
    }

    public function get_last_location(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $last_data = DeliveryHistory::where(['order_id' => $request['order_id']])->latest()->first();
        return response()->json($last_data, 200);
    }

    public function order_payment_status_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'payment_status' => 'required|in:paid'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $d_man = $request['delivery_man'];
        $order = Order::where(['delivery_man_id' => $d_man['id'], 'id' => $request['order_id']])->first();
        if (!empty($order)) {
            $order->payment_status = $request['payment_status'];
            $order->save();

            return response()->json(['message' => translate('Payment status updated')], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('not found!')]
            ]
        ], 404);
    }

    public function update_fcm_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $d_man = $request['delivery_man'];
        DeliveryMan::where(['id' => $d_man['id']])->update([
            'fcm_token' => $request['fcm_token']
        ]);

        return response()->json(['message' => 'successfully updated!'], 200);
    }

    public function delivery_wise_earned(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offset' => 'required',
            'limit' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $dateType = $request->type;
        $delivery_man = $request->delivery_man;

        $order = Order::with(['seller.shop', 'customer'])->where(['delivery_man_id' => $delivery_man->id, 'payment_status' => 'paid']);

        if (isset($request->start_date) && isset($request->end_date)) {
            $start_date = Carbon::parse($request['start_date'])->format('Y-m-d 00:00:00');
            $end_data = Carbon::parse($request['end_date'])->format('Y-m-d 23:59:59');

            $order->whereBetween('updated_at', [$start_date, $end_data]);
        } elseif ($dateType == 'TodayEarn') {
            $start_time = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
            $end_time = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

            $order->whereBetween('created_at', [$start_time, $end_time]);
        } elseif ($dateType == 'ThisWeekEarn') {
            $start_date = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
            $end_data = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');

            $order->whereBetween('created_at', [$start_date, $end_data]);
        } elseif ($dateType == 'ThisMonthEarn') {
            $start_date = date('Y-m-01 00:00:00');
            $end_data = date('Y-m-t 23:59:59');

            $order->whereBetween('created_at', [$start_date, $end_data]);
        }

        $orders = $order->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data['total_size'] = $orders->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['orders'] = $orders->items();
        return response()->json($data, 200);

    }

    public function search(Request $request)
    {

        $delivery_man = $request['delivery_man'];
        $order = Order::where('id', 'like', '%' . $request->input('search') . '%')
            ->where('delivery_man_id', $delivery_man->id)->get();

        if (empty(json_decode($order))) {
            $terms = explode(" ", $request->input('search'));

            $users = User::where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->orWhere('f_name', 'like', '%' . $term . '%')
                        ->orWhere('l_name', 'like', '%' . $term . '%');
                }
            })->pluck('id');

            $order = Order::whereIn('customer_id', $users)->where('delivery_man_id', $delivery_man->id)->get();

            if (!empty(json_decode($order))) {
                return response()->json($order, 200);
            }
            return response()->json('No Result Found', 400);
        }

        return response()->json($order, 200);

    }

    public function profile_dashboard_counts(Request $request)
    {
        $delivery_man = $request['delivery_man'];
        $orders = Order::where('delivery_man_id', $delivery_man->id);
        $data = DeliverymanWallet::where('delivery_man_id', $delivery_man->id)->first();

        $data['total_delivery_count'] = $orders->count();
        $data['delivered_orders'] = $orders->where('order_status', 'delivered')->count();
        return response()->json($data);
    }

    public function change_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $delivery_man = $request['delivery_man'];
        $delivery_man = DeliveryMan::find($delivery_man->id);
        $delivery_man->is_active = $request->status;

        if ($delivery_man->save()) {
            return response()->json('Status changed successfully', 200);
        } else {
            return response()->json('Status change failed!', 403);
        }
    }

    public function update_info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'password' => 'nullable|same:confirm_password|min:8',
            ],
            [
                'f_name.required' => 'The first name field is required.',
                'l_name.required' => 'The last name field is required.'
            ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $delivery_man = DeliveryMan::find($request['delivery_man']->id);
        $image = $request->file('image');
        if ($image != null) {
            $imageName = ImageManager::update('delivery-man/', $delivery_man->image, 'webp', $request->file('image'));
        } else {
            $imageName = $delivery_man->image;
        }

        $delivery_man->f_name = $request['f_name'];
        $delivery_man->l_name = $request['l_name'];
        $delivery_man->address = $request['address'];
        $delivery_man->image = $imageName;
        if(!empty($request->password)){
            $delivery_man->password = bcrypt(str_replace(' ', '', $request['password']));
        }

        if ($delivery_man->save()) {
            return response()->json(['message' => translate('Profile updated successfully')], 200);
        } else {
            return response()->json(['message' => translate('Profile update failed!'), 403]);
        }
    }

    public function bank_info(Request $request)
    {
        $delivery_man = $request['delivery_man'];
        $delivery_man->bank_name = $request->bank_name;
        $delivery_man->branch = $request->branch;
        $delivery_man->account_no = $request->account_no;
        $delivery_man->holder_name = $request->holder_name;

        if ($delivery_man->save()) {
            return response()->json(['message' => translate('Bank Info updated successfully')], 200);
        } else {
            return response()->json(['message' => translate('Bank Info update failed!'), 403]);
        }
    }

    public function collected_cash_history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offset' => 'required',
            'limit' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $dateType = $request->type;
        $delivery_man_id = $request['delivery_man']->id;

        $collect_cash_history = DeliveryManTransaction::where(['delivery_man_id' => $delivery_man_id, 'transaction_type' => 'cash_in_hand']);

        if (isset($request->start_date) && isset($request->end_date)) {
            $start_date = Carbon::parse($request['start_date'])->format('Y-m-d 00:00:00');
            $end_data = Carbon::parse($request['end_date'])->format('Y-m-d 23:59:59');

            $collect_cash_history->whereBetween('created_at', [$start_date, $end_data]);
        } elseif ($dateType == 'TodayPaid') {
            $start_time = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
            $end_time = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

            $collect_cash_history->whereBetween('created_at', [$start_time, $end_time]);
        } elseif ($dateType == 'ThisWeekPaid') {
            $start_date = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
            $end_data = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');

            $collect_cash_history->whereBetween('created_at', [$start_date, $end_data]);
        } elseif ($dateType == 'ThisMonthPaid') {
            $start_date = date('Y-m-01 00:00:00');
            $end_data = date('Y-m-t 23:59:59');

            $collect_cash_history->whereBetween('created_at', [$start_date, $end_data]);
        }
        $collect_cash_history = $collect_cash_history->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data = array();
        $data['total_size'] = $collect_cash_history->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['deposit'] = $collect_cash_history->items();

        return response()->json($data, 200);
    }

    public function emergency_contact_list(Request $request)
    {

        $list = EmergencyContact::where(['user_id'=> $request['delivery_man']->seller_id, 'status'=>1])->get();
        $data = array();
        $data['contact_list'] = $list;

        return response()->json($data, 200);
    }

    public function review_list(Request $request)
    {
        $dm = $request['delivery_man'];

        $reviews = Review::with('customer','order')
            ->when($request->is_saved, function ($query) use($request){
                $query->where('is_saved', 1);
            })
            ->where('delivery_man_id',$dm->id)
            ->latest('updated_at')
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data = array();
        $data['total_size'] = $reviews->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['review'] = $reviews->items();

        return response()->json($data, 200);
    }

    public function is_online(Request $request){
        $dm = $request['delivery_man'];
        $delivery_man = '';
        if($request->is_online == '0') {
            $delivery_man = DeliveryMan::whereHas('orders', function ($query) {
                $query->where(['order_status' => 'out_for_delivery', 'is_pause' => 0]);
            })->find($request['delivery_man']->id);
        }

        if($request->is_online =='0' && $delivery_man) {
            return response()->json(["message" => translate("You have ongoing order. You can't go offline now!")], 403);
        }else{
            $dm->is_online = $request->is_online;
            $dm->save();
            return response()->json(["message" => translate("update successfully!")], 200);
        }
    }

    public function get_all_notification(Request $request){
        $notifications = DeliverymanNotification::with('order')
            ->where(['delivery_man_id'=>$request['delivery_man']->id])
            ->latest()
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data = array();
        $data['total_size'] = $notifications->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['notifications'] = $notifications->items();

        return response()->json($data, 200);
    }

    public function distance_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'origin_lat' => 'required',
            'origin_lng' => 'required',
            'destination_lat' => 'required',
            'destination_lng' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $api_key = Helpers::get_business_settings('map_api_key_server');

        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $request['origin_lat'] . ',' . $request['origin_lng'] . '&destinations=' . $request['destination_lat'] . ',' . $request['destination_lng'] . '&key=' . $api_key);

        return response()->json($response->json(), 200);
    }
    public function is_saved(Request $request)
    {
        $dm = $request['delivery_man'];
        $get_review = Review::where(['id'=> $request->review_id, 'delivery_man_id' => $dm->id])->first();

        if (!$get_review) {
            return response()->json([
                'errors' => [[
                        'code' => 'review',
                        'message' => translate('not_found!')]
                ]], 404);
        }
        $get_review->is_saved = $request->is_saved;

        if ($get_review->save()) {
            return response()->json(['message' => translate('update_successfully!')], 200);

        }
        return response()->json([
            'errors' => [[
                'code' => 'update',
                'message' => translate('failed!')]
            ]], 403);

    }
    /** Dellivery man order verification */
    public function verify_order_delivery_otp(Request $request){
        $order = $this->order->where('id',$request['order_id'])->first();

        if ($order->verification_code == $request['verification_code']) {
            $order->verification_status = 1;
            $order->save();
            return response()->json(['message' => translate('otp_verified_successfully')], 200);
        }else{
            return response()->json(["message" => translate("invalid_otp")], 403);
        }
    }

      /**Order Delivery verification */
      public function order_delivery_verification(Request $request){
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'image'    => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        foreach($request->file('image') as $key=>$img){
            DB::table('order_delivery_verifications')->insert([
                'order_id' => $request->order_id,
                'image'    => ImageManager::upload('delivery-man/verification-image/','webp',$img),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return response()->json(['message' => 'successfully_uploaded'], 200);
      }

    /** Resend OTP Verification */
    public function resend_verification_code(Request $request){
        $order = $this->order::with('customer')->where('id',$request['order_id'])->first();

        $fcm_token = $order->customer->cm_firebase_token ?? null;
        $verification_code = rand(100000, 999999);
        $order->verification_code = $verification_code;
        if ($order->save() && !$order->is_guest && $fcm_token) {
            $data = [
                'title' => translate('order_verification_code'),
                'description' => translate('order_verification_code').' '.$verification_code,
                'order_id' => $order->id,
                'image' => '',
                'type'=>'order'
            ];
            Helpers::send_push_notif_to_device($fcm_token, $data);
            return response()->json(['message' => 'successfully send verification code'], 200);
        }else{
            return response()->json(["message" => "verification code send failed"], 403);
        }
    }

    public function language_change(Request $request){
        $delivery_man = $request->delivery_man;
        $delivery_man->app_language = $request->current_language;
        $delivery_man->save();

        return response()->json(['message' => 'Successfully change'], 200);
    }
}
