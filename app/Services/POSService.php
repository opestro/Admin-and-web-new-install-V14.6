<?php

namespace App\Services;

use App\Enums\SessionKey;
use App\Traits\CalculatorTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;

class POSService
{
    use CalculatorTrait;
    public function getTotalHoldOrders():int
    {
        $totalHoldOrders = 0;
        if (session()->has(SessionKey::CART_NAME)){
            foreach (session(SessionKey::CART_NAME) as $item){
                if (session()->has($item) && count(session($item)) > 1){
                    if (isset(session($item)[0]) && is_array(session($item)[0]) && isset(session($item)[0]['customerOnHold']) && session($item)[0]['customerOnHold']) {
                        $totalHoldOrders++;
                    }
                }
            }
        }
        return $totalHoldOrders;
    }
    public function getCartNames():array
    {
        $cartNames = [];
        if (session()->has(SessionKey::CART_NAME)){
            foreach (session(SessionKey::CART_NAME) as $item){
                if (session()->has($item) && count(session($item)) > 1){
                    $cartNames[] = $item;
                }
            }
        }
        return $cartNames ;
    }

    public function UpdateSessionWhenCustomerChange(string $cartId):void
    {
        if(!in_array($cartId,session(SessionKey::CART_NAME)??[]))
        {
            session()->push(SessionKey::CART_NAME, $cartId);
        }
        $cart = session(session(SessionKey::CURRENT_USER));
        $cartKeeper = [];
        if (session()->has(session(SessionKey::CURRENT_USER)) && count($cart) > 0) {
            foreach ($cart as $cartItem) {
                if (is_array($cartItem)) {
                    $cartItem['customerId'] = Str::contains($cartId, 'walking-customer') ? '0' : explode('-',$cartId)[2];
                }
                $cartKeeper[] = $cartItem;
            }
        }
        if(session(SessionKey::CURRENT_USER) != $cartId)
        {
            $tempCartName = [];
            foreach(session(SessionKey::CART_NAME) as $cartName)
            {
                if($cartName != session(SessionKey::CURRENT_USER))
                {
                    $tempCartName[] = $cartName;
                }
            }
            session()->put(SessionKey::CART_NAME,$tempCartName);
        }
        session()->forget(session(SessionKey::CURRENT_USER));
        session()->put($cartId , $cartKeeper);
        session()->put(SessionKey::CURRENT_USER,$cartId);
    }
    public function checkConditions(float $amount):bool
    {
        $condition = false;
        $cartId =session(SessionKey::CURRENT_USER);
        if (session()->has($cartId)) {
            if (count(session()->get($cartId)) < 1) {
                Toastr::error(translate('cart_empty_warning'));
                $condition = true;
            }
        }else {
            Toastr::error(translate('cart_empty_warning'));
            $condition = true;
        }
        if($amount <= 0)
        {
            Toastr::error(translate('amount_cannot_be_lees_then_0'));
            $condition = true;
        }
        return $condition;
    }

    public function getCouponCalculation(object $coupon, float $totalProductPrice, float $productDiscount, float $productTax): array
    {
        $extraDiscount = 0;
        if ($coupon['discount_type'] === 'percentage') {
            $discount = min((($totalProductPrice / 100) * $coupon['discount']), $coupon['max_discount']);
        } else {
            $discount = $coupon['discount'];
        }
        if (isset($carts['ext_discount_type'])) {
            $extraDiscount = $this->getDiscountAmount(price: $totalProductPrice, discount: $carts['ext_discount'], discountType: $carts['ext_discount_type']);
        }
        $total = $totalProductPrice - $productDiscount + $productTax - $discount - $extraDiscount;
        return [
            'total' => $total,
            'discount' => $discount,
        ];
    }
    public function putCouponDataOnSession($cartId,$discount,$couponTitle,$couponBearer,$couponCode):void
    {
        $cart = session($cartId, collect([]));
        $cart['coupon_code'] = $couponCode;
        $cart['coupon_discount'] = $discount;
        $cart['coupon_title'] = $couponTitle;
        $cart['coupon_bearer'] = $couponBearer;
        session()->put($cartId, $cart);
    }
    public function getVariantData(string $type,array $variation,int $quantity):array
    {
        $variationData = [];
        foreach ($variation as $variant) {
            if ($type == $variant['type']) {
                $variant['qty'] -= $quantity;
            }
            $variationData[] = $variant;
        }
        return $variationData;
    }
    public function getSummaryData():array
    {
        return  [
            'cartName'=>session(SessionKey::CART_NAME),
            'currentUser'=>session(SessionKey::CURRENT_USER),
            'totalHoldOrders'=>$this->getTotalHoldOrders(),
            'cartNames' =>$this->getCartNames(),
        ];
    }
}
