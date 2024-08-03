<?php

namespace App\Http\Controllers\RestAPI\v3\seller;

use App\Events\OrderStatusEvent;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\DeliveryManTransaction;
use App\Models\DeliverymanWallet;
use App\Models\DeliveryZipCode;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Traits\CommonTrait;
use App\User;
use App\Utils\BackEndHelper;
use App\Utils\Convert;
use App\Utils\CustomerManager;
use App\Utils\Helpers;
use App\Utils\ImageManager;
use App\Utils\OrderManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;


class OrderController extends Controller
{
    use CommonTrait;
    public function __construct(
        private DeliveryZipCode $delivery_zip_code,
        private Order $order,
    ){

    }
    public function list(Request $request)
    {
        $seller = $request->seller;
        $status = $request->status;

        $orders = Order::with('offlinePayments')->with(['customer', 'shipping', 'deliveryMan', 'orderDetails'])
            ->when($status != 'all', function ($q) use ($status) {
                $q->where(function ($query) use ($status) {
                    $query->orWhere('order_status', $status);
                });
            })
            ->where(['seller_is' => 'seller', 'seller_id' => $seller['id']])
            ->latest()
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $orders?->map(function ($data) {
            if (isset($data['offlinePayments'])) {
                $data['offlinePayments']->payment_info = $data->offlinePayments->payment_info;
            }

            $totalTaxAmount = 0;
            $totalProductPrice = 0;
            $totalProductDiscount = 0;
            if (isset($data['orderDetails']) && count($data['orderDetails']) > 0) {
                $totalTaxAmount = $data['orderDetails']->sum('tax');
                $totalProductPrice = $data['orderDetails']->sum('price');
                $totalProductDiscount = $data['orderDetails']->sum('discount');
            }
            $data['total_tax_amount'] = $totalTaxAmount;
            $data['total_product_price'] = $totalProductPrice;
            $data['total_product_discount'] = $totalProductDiscount;
            return $data;
        });

        return response()->json([
            'total_size' => $orders->total(),
            'limit' => (int)$request['limit'],
            'offset' => (int)$request['offset'],
            'orders' => $orders->items()
        ], 200);
    }

    public function details(Request $request, $id)
    {
        $seller = $request->seller;

        $details = OrderDetail::with('order.customer','order.deliveryMan','verificationImages')->where(['seller_id' => $seller['id'], 'order_id' => $id])->get();
        foreach ($details as $det) {
            $det['product_details'] = Helpers::product_data_formatting(json_decode($det['product_details'], true));
        }

        return response()->json($details, 200);
    }

    public function assign_delivery_man(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'delivery_man_id' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $seller = $request->seller;
        $order = Order::with('deliveryMan')->where(['seller_id' => $seller['id'], 'id' => $request['order_id']])->first();

        $order->delivery_man_id = $request['delivery_man_id'];
        $order->delivery_type = 'self_delivery';
        $order->delivery_service_name = null;
        $order->third_party_delivery_tracking_id = null;
        $order->save();
        OrderStatusEvent::dispatch('new_order_assigned_message', 'delivery_man', $order);
        return response()->json(['success' => 1, 'message' => translate('order_deliveryman_assigned_successfully')], 200);
    }

    public function amount_date_update(Request $request){
        $seller = $request->seller;

        $deliveryman_charge = $request->deliveryman_charge;

        $order = Order::with('deliveryMan')->find($request->order_id);
        $db_expected_date  = $order->expected_delivery_date;

        $order->deliveryman_charge = $deliveryman_charge;
        $order->expected_delivery_date = $request->expected_delivery_date;

        try {
            DB::beginTransaction();

            if(!empty($request->expected_delivery_date) && $db_expected_date != $request->expected_delivery_date){
                CommonTrait::add_expected_delivery_date_history($request->order_id, $seller['id'], $request->expected_delivery_date, 'seller');
            }
            $order->save();

            DB::commit();
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json(['success' => 0, 'message' => translate('Update fail!')], 403);
        }

        if(!empty($request->expected_delivery_date) && $db_expected_date != $request->expected_delivery_date){
            OrderStatusEvent::dispatch('expected_delivery_date', 'delivery_man', $order);
        }

        return response()->json(['success' => 0, 'message' => translate('Updated successfully!')], 200);
    }

    /**
     *  Digital file upload after sell
     */
    public function digital_file_upload_after_sell(Request $request)
    {
        $seller = $request->seller;

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'digital_file_after_sell' => 'required|mimes:jpg,jpeg,png,gif,zip,pdf',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $order_details = OrderDetail::find($request->order_id);
        if($order_details){
            $order_details->digital_file_after_sell = ImageManager::update('product/digital-product/', $order_details->digital_file_after_sell, $request->digital_file_after_sell->getClientOriginalExtension(), $request->file('digital_file_after_sell'), 'file');
            $order_details->save();
            return response()->json(['success' => 1, 'message' => translate('File_upload_successfully')], 200);
        }else{
            return response()->json(['success' => 0, 'message' => translate("File_upload_fail!")], 202);
        }
    }

    public function order_detail_status(Request $request)
    {
        $seller = $request->seller;
        $order = Order::with(['customer','seller.shop', 'deliveryMan'])->find($request->id);
        if(!$order->is_guest && empty($order->customer))
        {
            return response()->json(['success' => 0, 'message' => translate("Customer_account_has_been_deleted").' '.translate("you_cant_update_status")], 202);
        }

        $wallet_status = Helpers::get_business_settings('wallet_status');
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');

        if ($order->order_status == 'delivered') {
            return response()->json(['success' => 0, 'message' => translate('order is already delivered')], 200);
        }

        event(new OrderStatusEvent(key: $request['order_status'], type: 'customer', order: $order));
        if ($request->order_status == 'canceled'){
            event(new OrderStatusEvent(key: 'canceled', type: 'delivery_man', order: $order));
        }


        $order->order_status = $request->order_status;
        if ($request->order_status == 'delivered'){
            $order->payment_status = 'paid';
            OrderDetail::where('order_id', $order->id)->update(['delivery_status'=>'delivered','payment_status'=>'paid']);
        }
        OrderManager::stock_update_on_order_status_change($order, $request->order_status);
        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'seller');

        }

        $order->save();

        if ($order->delivery_man_id && $request->order_status == 'delivered') {
            $dm_wallet = DeliverymanWallet::where('delivery_man_id', $order->delivery_man_id)->first();
            $cash_in_hand = $order->payment_method == 'cash_on_delivery' ? $order->order_amount : 0;

            if (empty($dm_wallet)) {
                DeliverymanWallet::create([
                    'delivery_man_id' => $order->delivery_man_id,
                    'current_balance' => BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0,
                    'cash_in_hand' => BackEndHelper::currency_to_usd($cash_in_hand),
                    'pending_withdraw' => 0,
                    'total_withdraw' => 0,
                ]);
            } else {
                $dm_wallet->current_balance += BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0;
                $dm_wallet->cash_in_hand += BackEndHelper::currency_to_usd($cash_in_hand);
                $dm_wallet->save();
            }

            if($order->deliveryman_charge && $request->order_status == 'delivered'){
                DeliveryManTransaction::create([
                    'delivery_man_id' => $order->delivery_man_id,
                    'user_id' => $seller->id,
                    'user_type' => 'seller',
                    'credit' => BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0,
                    'transaction_id' => Uuid::uuid4(),
                    'transaction_type' => 'deliveryman_charge'
                ]);
            }
        }

        if(!$order->is_guest && $wallet_status == 1 && $loyalty_point_status == 1)
        {
            if($request->order_status == 'delivered'){
                CustomerManager::create_loyalty_point_transaction($order->customer_id, $order->id, Convert::default($order->order_amount-$order->shipping_cost), 'order_place');
            }
        }

        $ref_earning_status = BusinessSetting::where('type', 'ref_earning_status')->first()->value ?? 0;
        $ref_earning_exchange_rate = BusinessSetting::where('type', 'ref_earning_exchange_rate')->first()->value ?? 0;

        if(!$order->is_guest && $wallet_status == 1 && $ref_earning_status == 1 && $request->order_status == 'delivered'){

            $customer = User::find($order->customer_id);
            $is_first_order = Order::where(['customer_id'=>$order->customer_id,'order_status'=>'delivered','payment_status'=>'paid'])->count();
            $referred_by_user = User::find($customer->referred_by);

            if ($is_first_order == 1 && isset($customer->referred_by) && isset($referred_by_user)){
                CustomerManager::create_wallet_transaction($referred_by_user->id, floatval($ref_earning_exchange_rate), 'add_fund_by_admin', 'earned_by_referral');
            }
        }

        CommonTrait::add_order_status_history($order->id, $seller->id, $request->order_status, 'seller');

        return response()->json(['success' => 1, 'message' => translate('order_status_updated_successfully')], 200);
    }

    public function assign_third_party_delivery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'delivery_service_name' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $order = Order::find($request->order_id);
        $order->delivery_type = 'third_party_delivery';
        $order->delivery_service_name = $request->delivery_service_name;
        $order->third_party_delivery_tracking_id = $request->third_party_delivery_tracking_id;
        $order->delivery_man_id = null;
        $order->deliveryman_charge = 0;
        $order->expected_delivery_date = null;
        $order->save();

        return response()->json(['success' => 1, 'message' => translate('third_party_delivery_assigned_successfully')], 200);
    }

    public function update_payment_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'payment_status' => 'required|in:paid,unpaid'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        if ($request->payment_status != 'paid') {
            return response()->json(['success' => 0, 'message' => translate('When payment status paid then you can`t change payment status paid to unpaid') . '.'], 200);
        }
        $order = Order::find($request['order_id']);
        if (isset($order)) {
            if ($order->is_guest == '0' && empty($order->customer)) {
                return response()->json(['success' => 0, 'message' => translate("Customer account has been deleted. you can't update status!")], 202);
            }

            if ($order['payment_method'] == 'cash_on_delivery' && $order['order_status'] != 'delivered' && $request['payment_status'] == 'paid') {
                return response()->json([
                    'errors' => [
                        ['code' => 'order', 'message' => translate('Can not change payment status before order delivered!')]
                    ]
                ], 404);
            }

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

    public function address_update(Request $request){
        $order = $this->order->find($request->order_id)->toArray();
        $shipping_address_data = $order['shipping_address_data'] ? json_decode(json_encode($order['shipping_address_data']), true) : [];
        $billing_address_data = $order['billing_address_data'] ? json_decode(json_encode($order['billing_address_data']), true) : [];

        $common_address_data = [
            'contact_person_name' => $request->contact_person_name,
            'phone' => $request->phone,
            'city' => $request->city,
            'zip' => $request->zip,
            'email' => $request->email,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'updated_at' => now(),
        ];

        if ($request->address_type == 'shipping') {
            $shipping_address_data = array_merge($shipping_address_data, $common_address_data);
        } elseif ($request->address_type == 'billing') {
            $billing_address_data = array_merge($billing_address_data, $common_address_data);
        }
        $update_data = [];

        if ($request->address_type == 'shipping') {
            $update_data['shipping_address_data'] = json_encode($shipping_address_data);
        } elseif ($request->address_type == 'billing') {
            $update_data['billing_address_data'] = json_encode($billing_address_data);
        }

        if (!empty($update_data)) {
            DB::table('orders')->where('id', $request->order_id)->update($update_data);
        }

        return response()->json(['message' => 'Address updated successfully'], 200);
    }
}
