<?php

namespace App\Http\Controllers\Web;

use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Utils\CartManager;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request): JsonResponse|RedirectResponse
    {
        self::removeCurrentCouponActivity();

        $couponLimit = Order::where(['customer_id' => auth('customer')->id(), 'coupon_code' => $request['code']])
            ->groupBy('order_group_id')->get()->count();

        $firstCoupon = Coupon::where(['code' => $request['code']])
            ->where('status', 1)
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'))->first();

        if (!$firstCoupon) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 0,
                    'messages' => ['0' => translate('invalid_coupon')]
                ]);
            }
            Toastr::error(translate('invalid_coupon'));
            return back();
        }
        if ($firstCoupon && $firstCoupon->coupon_type == 'first_order') {
            $coupon = $firstCoupon;
        } else {
            $coupon = $firstCoupon->limit > $couponLimit ? $firstCoupon : null;
        }

        if ($coupon && $coupon->coupon_type == 'first_order') {
            $orders = Order::where(['customer_id' => auth('customer')->id()])->count();
            if ($orders > 0) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 0,
                        'messages' => ['0' => translate('sorry_this_coupon_is_not_valid_for_this_user') . '!']
                    ]);
                }
                Toastr::error(translate('sorry_this_coupon_is_not_valid_for_this_user'));
                return back();
            }
        }

        if ($coupon && (($coupon->coupon_type == 'first_order') || ($coupon->coupon_type == 'discount_on_purchase' && ($coupon->customer_id == '0' || $coupon->customer_id == auth('customer')->id())))) {
            $total = 0;
            foreach (CartManager::get_cart(type: 'checked') as $cart) {
                if ($coupon->seller_id == '0' || (is_null($coupon->seller_id) && $cart->seller_is == 'admin') || ($coupon->seller_id == $cart->seller_id && $cart->seller_is == 'seller')) {
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

                session()->put('coupon_code', $request['code']);
                session()->put('coupon_type', $coupon->coupon_type);
                session()->put('coupon_discount', $discount);
                session()->put('coupon_bearer', $coupon->coupon_bearer);
                session()->put('coupon_seller_id', $coupon->seller_id);

                return response()->json([
                    'status' => 1,
                    'discount' => Helpers::currency_converter($discount),
                    'total' => Helpers::currency_converter($total - $discount),
                    'messages' => ['0' => translate('coupon_applied_successfully') . '!']
                ]);
            }
        } elseif ($coupon && $coupon->coupon_type == 'free_delivery' && ($coupon->customer_id == '0' || $coupon->customer_id == auth('customer')->id())) {
            $total = 0;
            $shipping_fee = 0;
            foreach (CartManager::get_cart(type: 'checked') as $cart) {
                if ($coupon->seller_id == '0' || (is_null($coupon->seller_id) && $cart->seller_is == 'admin') || ($coupon->seller_id == $cart->seller_id && $cart->seller_is == 'seller')) {
                    $product_subtotal = $cart['price'] * $cart['quantity'];
                    $total += $product_subtotal;
                    if (is_null($coupon->seller_id) || $coupon->seller_id == '0' || $coupon->seller_id == $cart->seller_id) {
                        $shipping_fee += $cart['shipping_cost'];
                    }
                }
            }

            if ($total >= $coupon['min_purchase']) {
                session()->put('coupon_code', $request['code']);
                session()->put('coupon_type', $coupon->coupon_type);
                session()->put('coupon_discount', $shipping_fee);
                session()->put('coupon_bearer', $coupon->coupon_bearer);
                session()->put('coupon_seller_id', $coupon->seller_id);

                return response()->json([
                    'status' => 1,
                    'discount' => Helpers::currency_converter($shipping_fee),
                    'total' => Helpers::currency_converter($total - $shipping_fee),
                    'messages' => ['0' => translate('coupon_applied_successfully') . '!']
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 0,
                'messages' => ['0' => translate('invalid_coupon')]
            ]);
        }
        Toastr::error(translate('invalid_coupon'));
        return back();
    }

    public function removeCoupon(Request $request): JsonResponse|RedirectResponse
    {
        self::removeCurrentCouponActivity();

        if ($request->ajax()) {
            return response()->json(['messages' => translate('coupon_removed')]);
        }
        Toastr::success(translate('coupon_removed'));
        return back();
    }

    function removeCurrentCouponActivity(): void
    {
        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('coupon_seller_id');
    }
}
