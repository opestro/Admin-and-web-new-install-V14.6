<?php

namespace App\Http\Controllers\RestAPI\v2\seller;

use App\Events\OrderStatusEvent;
use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\RefundRequest;
use App\Models\RefundStatus;
use App\User;
use App\Utils\CustomerManager;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{
    public function list(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }
        try{

            $refund_list = RefundRequest::with('customer','product','order_details')->whereHas('order', function ($query) use($data) {
                $query->where('seller_is', 'seller')->where('seller_id',$data['data']['id']);
            });

            $search = $request->search;
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $refund_list = $refund_list->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('order_id', 'like', "%{$value}%");
                    }
                });
                $query_param = ['search' => $request['search']];
            }
            $refund_list = $refund_list->latest()->get();
            $refund_list = $refund_list->map(function($data){
                $data['images'] = json_decode($data['images']);
                return $data;
            });
            return response()->json($refund_list);

        }catch (\Exception $e) {

        }
    }
    public function refund_details(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }
        $order_details = OrderDetail::find($request->order_details_id);
        $refund_request = RefundRequest::with('refund_status')->where('order_details_id',$request->order_details_id)->get();

            $order = Order::find($order_details->order_id);

            $total_product_price = 0;
            $refund_amount = 0;
            $data = [];
            foreach ($order->details as $key => $or_d) {
                $total_product_price += ($or_d->qty*$or_d->price) + $or_d->tax - $or_d->discount;
            }

            $subtotal = ($order_details->price * $order_details->qty) - $order_details->discount + $order_details->tax;

            $coupon_discount = ($order->discount_amount*$subtotal)/$total_product_price;

            $refund_amount = $subtotal - $coupon_discount;

            $data['product_price'] = $order_details->price;
            $data['quntity'] = $order_details->qty;
            $data['product_total_discount'] = $order_details->discount;
            $data['product_total_tax'] = $order_details->tax;
            $data['subtotal'] = $subtotal;
            $data['coupon_discount'] = $coupon_discount;
            $data['refund_amount'] = $refund_amount;
            $data['refund_request']=$refund_request->map(function($data){
                $data['images']=json_decode($data['images']);
                return $data;
            });
            $data['deliveryman_details']= DeliveryMan::find($order->delivery_man_id);

            return response()->json($data, 200);


    }

    public function refund_status_update(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'refund_status' => 'required',
            'refund_request_id' => 'required',
            'note'=>'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $refund = RefundRequest::whereHas('order', function ($query) use($data) {
                                    $query->where('seller_is', 'seller')->where('seller_id',$data['data']['id']);
                                })->find($request->refund_request_id);

        $user = User::find($refund->customer_id);

        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');

        if($loyalty_point_status == 1)
        {
            $loyalty_point = CustomerManager::count_loyalty_point_for_amount($refund->order_details_id);

            if($user->loyalty_point < $loyalty_point && $request->refund_status == 'approved')
            {
                return response()->json(['message'=>'Customer has not sufficient loyalty point to take refund for this order'],302);
            }
        }

        if($refund->change_by =='admin'){

            return response()->json(['message'=>'refunded status can not be changed!! Admin already changed the status : '.$refund->status.'!!'],302);
        }
        if($refund->status != 'refunded')
        {
            $order_details = OrderDetail::find($refund->order_details_id);
            $refund_status = new RefundStatus;
            $refund_status->refund_request_id = $refund->id;
            $refund_status->change_by = 'seller';
            $refund_status->change_by_id = $data['data']['id'];
            $refund_status->status = $request->refund_status;

            if($request->refund_status == 'pending')
            {
                $order_details->refund_request = 1;
            }
            elseif($request->refund_status == 'approved')
            {
                $order_details->refund_request = 2;
                $refund->approved_note = $request->note;

                $refund_status->message = $request->note;
            }
            elseif($request->refund_status == 'rejected')
            {
                $order_details->refund_request = 3;
                $refund->rejected_note = $request->note;

                $refund_status->message = $request->note;
            }

            $order_details->save();

            $refund->status = $request->refund_status;
            $refund->change_by = 'seller';
            $refund->save();
            $refund_status->save();

            $order = Order::find($refund->order_id);
            if ($request->refund_status == 'refunded') {
                OrderStatusEvent::dispatch('order_refunded_message', 'customer', $order);
            } elseif ($request->refund_status == 'rejected') {
                OrderStatusEvent::dispatch('refund_request_canceled_message', 'customer', $order);
            }

            return response()->json(['message'=>'refund status updated successfully!'], 200);

        }else{
            return response()->json(['message'=>'refunded status can not be changed!!'],302);
        }

    }
}
