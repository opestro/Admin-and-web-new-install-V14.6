<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Utils\CartManager;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function list(Request $request){
        $customer_id = $request->user() ? $request->user()->id : '0';

        $coupons = Coupon::with('seller.shop')
            ->withCount(['order'=>function($query) use($customer_id){
                $query->where(['customer_id'=>$customer_id]);
            }])
            ->where(['status' => 1])
            ->whereIn('customer_id',[$customer_id, '0'])
            ->whereDate('start_date', '<=', now())
            ->whereDate('expire_date', '>=', now())
            ->select('coupons.*', DB::raw('DATE(expire_date) as plain_expire_date'))
            ->inRandomOrder()
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        return [
            'total_size' => $coupons->total(),
            'limit' => (int)$request['limit'],
            'offset' => (int)$request['offset'],
            'coupons' => $coupons->items()
        ];
    }

    public function applicable_list(Request $request) {
        $customer_id = $request->user() ? $request->user()->id : '0';

        $cart_data = Cart::where(['customer_id'=> $customer_id, 'is_guest'=>'0'])->pluck('product_id');
        $product_group = Product::whereIn('id', $cart_data)->select('id', 'added_by', 'user_id')->get();

        if($cart_data->count() > 0 && $product_group->count() > 0) {
            $coupons = Coupon::with('seller.shop')
                ->select('coupons.*', DB::raw('DATE(expire_date) as plain_expire_date'))
                ->withCount(['order'=>function($query) use($customer_id){
                    $query->where(['customer_id'=>$customer_id]);
                }])
                ->when($product_group->where('added_by', 'seller')->count() > 0, function($query) use($product_group) {
                    $query->where(['coupon_bearer'=>'seller'])
                        ->whereIn('seller_id', $product_group
                        ->where('added_by', 'seller')
                        ->pluck('user_id'))
                        ->orWhereIn('seller_id', ['0']);
                })
                ->when($product_group->where('added_by', 'admin')->count() > 0, function($query){
                    $query->where(['coupon_bearer'=>'inhouse']);
                })
                ->where(['status' => 1])
                ->whereIn('customer_id',[$customer_id, '0'])
                ->whereDate('start_date', '<=', now())
                ->whereDate('expire_date', '>=', now())
                ->get();


            $coupons = $coupons->filter(function($data) {
                return (($data->order_count < $data->limit) || empty($data->limit)) && ($data->start_date <= now() && $data->expire_date >= now());
            })->values();

            $customer_order_count = Order::where('customer_id', $customer_id)->count();
            if($customer_order_count > 0) {
                $coupons = $coupons->whereNotIn('coupon_type', ['first_order']);
            }
        }
        return response()->json($coupons ?? [], 200);
    }

    public function apply(Request $request)
    {
        $couponLimit = Order::where(['customer_id' => $request->user()->id, 'coupon_code' => $request['code']])
            ->groupBy('order_group_id')->get()->count();

        $coupon_f = Coupon::where(['code' => $request['code']])
            ->where('status', 1)
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'))->first();

        if (!$coupon_f) {
            return response()->json(translate('invalid_coupon'), 202);
        }
        if ($coupon_f && $coupon_f->coupon_type == 'first_order') {
            $coupon = $coupon_f;
        } else {
            $coupon = $coupon_f->limit > $couponLimit ? $coupon_f : null;
        }

        if ($coupon && $coupon->coupon_type == 'first_order') {
            $orders = Order::where(['customer_id' => $request->user()->id])->count();
            if ($orders > 0) {
                return response()->json(translate('sorry_this_coupon_is_not_valid_for_this_user'), 202);
            }
        }

        if ($coupon && (($coupon->coupon_type == 'first_order') || ($coupon->coupon_type == 'discount_on_purchase' && ($coupon->customer_id == '0' || $coupon->customer_id == $request->user()->id)))) {
            $total = 0;
            foreach (CartManager::get_cart_for_api(request: $request, type: 'checked') as $cart) {
                if ((is_null($coupon->seller_id) && $cart->seller_is == 'admin') || $coupon->seller_id == '0' || ($coupon->seller_id == $cart->seller_id && $cart->seller_is == 'seller')) {
                    $product_subtotal = $cart['price'] * $cart['quantity'];
                    $total += $product_subtotal;
                }
            }
            if ($total >= $coupon['min_purchase']) {
                if ($coupon['discount_type'] == 'percentage') {
                    $discount = (($total / 100) * $coupon['discount']) > $coupon['max_discount'] ? $coupon['max_discount'] : (($total / 100) * $coupon['discount']);
                } else {
                    $discount = $coupon['discount'];
                }

                return response()->json([
                    'coupon_discount' => $discount
                ], 200);
            }
        } elseif ($coupon && $coupon->coupon_type == 'free_delivery' && ($coupon->customer_id == '0' || $coupon->customer_id == $request->user()->id)) {
            $total = 0;
            $shipping_fee = 0;
            $shippingMethod = Helpers::get_business_settings('shipping_method');
            $admin_shipping = \App\Models\ShippingType::where('seller_id', 0)->first();
            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';

            foreach (CartManager::get_cart_for_api(request: $request, type: 'checked') as $cart) {
                if ($coupon->seller_id == '0' || (is_null($coupon->seller_id) && $cart->seller_is == 'admin') || ($coupon->seller_id == $cart->seller_id && $cart->seller_is == 'seller')) {
                    $product_subtotal = $cart['price'] * $cart['quantity'];
                    $total += $product_subtotal;
                    if (is_null($coupon->seller_id) || $coupon->seller_id == '0' || $coupon->seller_id == $cart->seller_id) {
                        $shipping_fee += $cart['shipping_cost'];
                    }
                }
                if ($shipping_type == 'order_wise' && ($coupon->seller_id == '0' || (is_null($coupon->seller_id) && $cart->seller_is == 'admin') || ($coupon->seller_id == $cart->seller_id && $cart->seller_is == 'seller'))) {
                    $shipping_fee += CartManager::get_shipping_cost(groupId: $cart->cart_group_id, type: 'checked');
                }
            }

            if ($total >= $coupon['min_purchase']) {
                return response()->json([
                    'coupon_discount' => $shipping_fee,
                    'coupon_type' => $coupon['coupon_type']
                ], 200);
            }
        }

        return response()->json(translate('invalid_coupon'), 202);
    }

    public function get_seller_wise_coupon(Request $request, $seller_id){
        $seller_ids = ['0'];
        $coupons = Coupon::with('seller.shop')
            ->where(['status' => 1])
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'))
            ->when($seller_id == '0', function ($query) use ($seller_ids) {
                return $query->whereNull('seller_id');
            })
            ->when($seller_id != '0', function ($query) use ($seller_ids, $seller_id) {
                $seller_ids[] = $seller_id;
                return $query->whereIn('seller_id', $seller_ids);
            })
            ->select('coupons.*', DB::raw('DATE(expire_date) as plain_expire_date'))
            ->inRandomOrder()
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        return [
            'total_size' => $coupons->total(),
            'limit' => (int)$request['limit'],
            'offset' => (int)$request['offset'],
            'coupons' => $coupons->items()
        ];
    }
}
