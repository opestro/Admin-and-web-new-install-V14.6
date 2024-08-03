<?php

namespace App\Http\Controllers\RestAPI\v3\seller;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\User;
use App\Utils\Convert;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function list(Request $request){
        $seller = $request->seller;
        $coupons = Coupon::whereIn('seller_id', [$seller->id, '0'])
            ->when(isset($request['search']) && !empty($request['search']), function($query) use($request){
                $key = explode(' ', $request['search']);
                foreach ($key as $value) {
                    $query->where('title', 'like', "%{$value}%")
                        ->orWhere('code', 'like', "%{$value}%")
                        ->orWhere('discount_type', 'like', "%{$value}%");
                }
            })
            ->withCount('order')->latest()
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data = array();
        $data['total_size'] = $coupons->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['coupons'] = $coupons->items();

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $seller = $request->seller;
        $validator = Validator::make($request->all(), [
            'coupon_type' => 'required',
            'customer_id' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'limit' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'discount_type' => 'required_if:coupon_type,discount_on_purchase',
            'discount' => 'required_if:coupon_type,discount_on_purchase',
            'min_purchase' => 'required',
            'code' => 'required|unique:coupons',
            'title' => 'required',
            'start_date' => 'required',
            'expire_date' => 'required',
        ],[
            'coupon_bearer.required_if' => 'Coupon bearer is required',
            'seller_id.required_if' => 'Seller is required',
            'customer_id.required_if' => 'Customer is required',
            'limit.required_if' => 'Limit for same user is required',
            'discount_type.required_if' => 'Discount type is required',
            'discount.required_if' => 'Discount amount is required',
            'min_purchase.required' => 'Minimum purchase is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => Helpers::error_processor($validator)], 403);
        }

        $coupon = new Coupon();
        $coupon->added_by = 'seller';
        $coupon->coupon_type = $request->coupon_type;
        $coupon->title = $request->title;
        $coupon->code = $request->code;
        $coupon->start_date = $request->start_date;
        $coupon->expire_date = $request->expire_date;
        $coupon->status = 1;
        $coupon->coupon_bearer = 'seller';
        $coupon->seller_id = $seller->id;
        $coupon->customer_id = $request->customer_id;
        $coupon->limit = $request->limit;
        $coupon->min_purchase = Convert::usd($request->min_purchase);

        if ($request->coupon_type == 'discount_on_purchase') {
            $coupon->discount_type = $request->discount_type;
            $coupon->discount = $request->discount_type == 'amount' ? Convert::usd($request->discount) : $request['discount'];
            $coupon->max_discount = Convert::usd($request->max_discount != null ? $request->max_discount : $request->discount);
        }
        $coupon->save();

        return response()->json(['message' => translate('coupon_added_successfully')], 200);

    }

    public function update(Request $request, $id)
    {
        $seller = $request->seller;
        $request->validate([
            'coupon_type' => 'required',
            'customer_id' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'limit' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'discount_type' => 'required_if:coupon_type,discount_on_purchase',
            'discount' => 'required_if:coupon_type,discount_on_purchase',
            'min_purchase' => 'required',
            'code' => 'required|unique:coupons,code,' . $id,
            'title' => 'required',
            'start_date' => 'required',
            'expire_date' => 'required',
        ], [
            'customer_id.required_if' => 'Customer is required',
            'limit.required_if' => 'Limit for same user is required',
            'discount_type.required_if' => 'Discount type is required',
            'discount.required_if' => 'Discount amount is required',
            'min_purchase.required' => 'Minimum purchase is required',

        ]);

        $coupon = Coupon::where(['coupon_bearer' => 'seller'])->whereIn('seller_id', [$seller->id, '0'])->find($id);
        if(!$coupon){
            return response()->json(['message' => translate('coupon_not_found')], 403);
        }
        $coupon->coupon_type = $request->coupon_type;
        $coupon->title = $request->title;
        $coupon->code = $request->code;
        $coupon->start_date = $request->start_date;
        $coupon->expire_date = $request->expire_date;
        $coupon->limit = $request->limit;
        $coupon->min_purchase = Convert::usd($request->min_purchase);

        if ($request->coupon_type == 'discount_on_purchase') {
            $coupon->customer_id = $request->customer_id;
            $coupon->discount_type = $request->discount_type;
            $coupon->discount = $request->discount_type == 'amount' ? Convert::usd($request->discount) : $request['discount'];
            $coupon->max_discount = Convert::usd($request->max_discount != null ? $request->max_discount : $request->discount);
        } elseif ($request->coupon_type == 'free_delivery') {
            $coupon->customer_id = $request->customer_id;
            $coupon->discount_type = 'percentage';
            $coupon->discount = 0;
            $coupon->max_discount = 0;
        }
        $coupon->save();

        return response()->json(['message' => translate('coupon_updated_successfully')], 200);
    }

    public function status_update(Request $request)
    {
        $seller = $request->seller;
        $coupon = Coupon::where(['coupon_bearer' => 'seller'])->whereIn('seller_id', [$seller->id, '0'])->find($request->id);
        if(!$coupon){
            return response()->json(['message' => translate('coupon_not_found')], 403);
        }
        $coupon->status = $request->status;
        $coupon->save();

        return response()->json(['message' => translate('coupon_status_updated')], 200);
    }

    public function delete(Request $request, $id)
    {
        $seller = $request->seller;
        $coupon = Coupon::where(['added_by'=>'seller', 'coupon_bearer'=>'seller'])
            ->whereIn('seller_id', [$seller->id, '0'])->find($id);

        if(!$coupon){
            return response()->json(['message' => translate('coupon_not_found')], 403);
        }
        $coupon->delete();

        return response()->json(['message' => translate('coupon_deleted_successfully')], 200);
    }

    public function check_coupon(Request $request)
    {
        $seller = $request->seller;
        $user_id = $request->user_id;
        if($user_id != 0){
            $couponLimit = Order::where('customer_id', $user_id)
                ->where('customer_type', 'customer')
                ->where('coupon_code', $request['code'])->count();

            $coupon = Coupon::where(['code' => $request['code'], 'coupon_bearer'=>'seller'])
                ->where('limit', '>', $couponLimit)
                ->where('status', '=', 1)
                ->whereDate('start_date', '<=', now())
                ->whereDate('expire_date', '>=', now())->first();
        }else {
            $coupon = Coupon::where(['code' => $request->code, 'coupon_bearer'=>'seller'])
                ->where('expire_date', '>=', Carbon::now())
                ->where('start_date', '<=', Carbon::now())
                ->where('status', 1)
                ->first();
        }

        if(!$coupon || $coupon->coupon_type == 'free_delivery' || $coupon->coupon_type == 'first_order') {
            return response(['message' => translate('coupon_invalid')], 202);
        }

        if(($coupon->seller_id=='0' || $coupon->seller_id==$seller->id) && ($coupon->customer_id == '0' || $coupon->customer_id == $user_id)) {
            if ($request->order_amount < $coupon->min_purchase) {
                return response(['message' => translate('Does_not_satisfy_minimum_purchase_amount')], 202);
            } else {
                if ($coupon->discount_type == 'percentage') {
                    $discount = (($request->order_amount / 100) * $coupon->discount) > $coupon->max_discount ? $coupon->max_discount : (($request->order_amount / 100) * $coupon->discount);
                } else {
                    $discount = $coupon->discount;
                }

                $data =  ['coupon_discount_amount' => $discount];

                return response()->json($data, 200);
            }
        }
        return response(['message' => translate('coupon_invalid')], 202);
    }

    public function customers(Request $request)
    {
        $seller = $request->seller;
        $customers = User::when($request['name'], function ($query) use ($request) {
                $name_array = explode(' ', $request->name);
                foreach ($name_array as $name) {
                    $query->orWhere('f_name', 'like', "%{$name}%")
                        ->orWhere('l_name', 'like', "%{$name}%");
                }
            })
            ->whereNotNull(['f_name', 'l_name', 'phone'])
            ->whereNotIn('id',[0])
            ->take(10)
            ->get()->toArray();

        $customer_add = array(
            array('id'=>0,'f_name'=>'All','l_name'=>'Customer')
        );
        array_splice($customers, 0, 0, $customer_add);

        $data = array(
            'customers'=>$customers
        );
        return response()->json($data, 200);
    }

}
