<?php

namespace App\Utils;

use App\Models\DigitalProductVariation;
use App\Models\ShippingMethod;
use App\Utils\Helpers;
use App\Models\Cart;
use App\Models\CartShipping;
use App\Models\CategoryShippingCost;
use App\Models\Color;
use App\Models\Product;
use App\Models\ShippingType;
use App\Models\Shop;
use Illuminate\Support\Str;

class CartManager
{
    public static function cart_to_db($request = null)
    {
        $user = Helpers::get_customer($request);
        if (session()->has('guest_id') || $request->guest_id) {
            $guestId = session('guest_id') ?? $request->guest_id;
            $cartList = Cart::where(['is_guest' => 1, 'customer_id' => $guestId])->get();
            foreach ($cartList as $cart) {
                $databaseCart = Cart::where([
                    'customer_id' => $user->id,
                    'seller_id' => $cart['seller_id'],
                    'seller_is' => $cart['seller_is']
                ])->first();

                Cart::where([
                    'customer_id' => $user->id,
                    'product_id' => $cart['product_id'],
                    'variant' => $cart['variant'],
                    'seller_id' => $cart['seller_id'],
                    'seller_is' => $cart['seller_is']
                ])->delete();

                $cart->cart_group_id = isset($databaseCart) ? $databaseCart['cart_group_id'] : str_replace('guest', $user->id, $cart['cart_group_id']);
                $cart->customer_id = $user->id;
                $cart->is_guest = 0;
                $cart->save();
            }
        }
    }

    public static function get_cart($groupId = null, $type = null)
    {
        return Cart::with(['product'])->when($groupId == null, function ($query) {
            return $query->whereIn('cart_group_id', CartManager::get_cart_group_ids());
        })
            ->when($groupId, function ($query) use ($groupId) {
                return $query->where('cart_group_id', $groupId);
            })
            ->when($type == 'checked', function ($query) {
                return $query->where(['is_checked' => 1]);
            })
            ->get();
    }

    public static function get_cart_for_api($request, $groupId = null, $type = null)
    {
        return Cart::when(($groupId == null && $type != 'checked'), function ($query) use ($request) {
            return $query->whereIn('cart_group_id', CartManager::get_cart_group_ids(request: $request));
        })
            ->when(($groupId == null && $type == 'checked'), function ($query) use ($request) {
                return $query->whereIn('cart_group_id', CartManager::get_cart_group_ids(request: $request, type: 'checked'));
            })
            ->when($groupId, function ($query) use ($groupId) {
                return $query->where('cart_group_id', $groupId);
            })
            ->when($type == 'checked', function ($query) {
                return $query->where(['is_checked' => 1]);
            })
            ->get();
    }

    public static function get_cart_group_ids($request = null, $type = null)
    {
        $user = Helpers::get_customer($request);

        return Cart::when($user == 'offline', function ($query) use ($request) {
            return $query->where(['customer_id' => session('guest_id') ?? ($request->guest_id ?? 0), 'is_guest' => 1]);
        })->when($user != 'offline', function ($query) use ($user) {
            return $query->where(['customer_id' => $user->id, 'is_guest' => '0']);
        })
            ->when($type == 'checked', function ($query) {
                return $query->where(['is_checked' => 1]);
            })
            ->groupBy('cart_group_id')
            ->pluck('cart_group_id')
            ->toArray();
    }

    public static function get_shipping_cost($groupId = null, $type = null)
    {
        $cost = 0;

        $cartShippingCost = Cart::where(['product_type' => 'physical'])
            ->when(($groupId == null && $type != 'checked'), function ($query) {
                return $query->whereIn('cart_group_id', CartManager::get_cart_group_ids());
            })
            ->when(($groupId == null && $type == 'checked'), function ($query) {
                return $query->whereIn('cart_group_id', CartManager::get_cart_group_ids(type: 'checked'));
            })
            ->when($groupId != null, function ($query) use ($groupId) {
                return $query->where(['cart_group_id' => $groupId]);
            })
            ->when($type == 'checked', function ($query) {
                return $query->where(['is_checked' => 1]);
            })->sum('shipping_cost');

        $orderWiseShippingCostData = CartShipping::whereHas('cart', function ($query) use ($type) {
            $query->where(['product_type' => 'physical'])->when($type == 'checked', function ($query) {
                return $query->where(['is_checked' => 1]);
            });
        })->when(($groupId == null && $type != 'checked'), function ($query) {
            return $query->whereIn('cart_group_id', CartManager::get_cart_group_ids());
        })
            ->when(($groupId == null && $type == 'checked'), function ($query) {
                return $query->whereIn('cart_group_id', CartManager::get_cart_group_ids(type: 'checked'));
            })
            ->when(($groupId != null), function ($query) use ($groupId) {
                return $query->where('cart_group_id', $groupId);
            });

        if ($groupId == null) {
            $orderWiseShippingCost = $orderWiseShippingCostData->sum('shipping_cost');
        } else {
            $data = $orderWiseShippingCostData->first();
            $orderWiseShippingCost = isset($data) ? $data->shipping_cost : 0;
        }
        return ($orderWiseShippingCost + $cartShippingCost);
    }

    public static function order_wise_shipping_discount()
    {
        if (auth('customer')->check()) {
            $shippingMethod = \App\Utils\Helpers::get_business_settings('shipping_method');
            $cart_group_ids = CartManager::get_cart_group_ids();

            $amount = 0;
            if (count($cart_group_ids) > 0) {

                foreach ($cart_group_ids as $cart) {
                    $cart_data = Cart::where('cart_group_id', $cart)->first();
                    if ($shippingMethod == 'inhouse_shipping') {
                        $admin_shipping = \App\Models\ShippingType::where('seller_id', 0)->first();
                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                    } else {
                        if ($cart_data->seller_is == 'admin') {
                            $admin_shipping = \App\Models\ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                        } else {
                            $seller_shipping = \App\Models\ShippingType::where('seller_id', $cart_data->seller_id)->first();
                            $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                        }
                    }

                    if ($shipping_type == 'order_wise' && session('coupon_type') == 'free_delivery' && (session('coupon_seller_id') == '0' || (is_null(session('coupon_seller_id')) && $cart_data->seller_is == 'admin') || (session('coupon_seller_id') == $cart_data->seller_id && $cart_data->seller_is == 'seller'))) {
                        $amount += CartManager::get_shipping_cost(groupId: $cart, type: 'checked');
                    }
                }
            }

            return $amount;

        }
    }

    public static function cart_total($cart)
    {
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $product_subtotal = $item['price'] * $item['quantity'];
                $total += $product_subtotal;
            }
        }
        return $total;
    }

    public static function cart_total_applied_discount($cart)
    {
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $product_subtotal = ($item['price'] - $item['discount']) * $item['quantity'];
                $total += $product_subtotal;
            }
        }
        return $total;
    }

    public static function cart_total_with_tax($cart)
    {
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $product_subtotal = ($item['price'] * $item['quantity']) + ($item['tax'] * $item['quantity']);
                $total += $product_subtotal;
            }
        }
        return $total;
    }

    public static function cart_grand_total($cartGroupId = null, $type = null)
    {
        if ($type == 'checked') {
            $cart = CartManager::get_cart(groupId: $cartGroupId, type: 'checked');
            $shipping_cost = CartManager::get_shipping_cost(groupId: $cartGroupId, type: 'checked');
        } else {
            $cart = CartManager::get_cart(groupId: $cartGroupId);
            $shipping_cost = CartManager::get_shipping_cost(groupId: $cartGroupId);
        }
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $tax = $item['tax_model'] == 'include' ? 0 : $item['tax'];
                $product_subtotal = ($item['price'] * $item['quantity'])
                    + ($tax * $item['quantity'])
                    - $item['discount'] * $item['quantity'];
                $total += $product_subtotal;
            }
            $total += $shipping_cost;
        }
        return $total;
    }

    public static function api_cart_grand_total($request, $cart_group_id = null)
    {
        $cart = CartManager::get_cart_for_api(request: $request, groupId: $cart_group_id);
        $shipping_cost = CartManager::get_shipping_cost(groupId: $cart_group_id, type: 'checked');
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $tax = $item['tax_model'] == 'include' ? 0 : $item['tax'];
                $product_subtotal = ($item['price'] * $item['quantity'])
                    + ($tax * $item['quantity'])
                    - $item['discount'] * $item['quantity'];
                $total += $product_subtotal;
            }
            $total += $shipping_cost;
        }
        return $total;
    }

    public static function getCartGrandTotalWithoutShippingCharge($cartGroupId = null, $type = null): float|int
    {
        if ($type) {
            $cart = CartManager::get_cart(groupId: $cartGroupId, type: 'checked');
        } else {
            $cart = CartManager::get_cart(groupId: $cartGroupId);
        }
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $tax = $item['tax_model'] == 'include' ? 0 : $item['tax'];
                $productSubtotal = ($item['price'] * $item['quantity'])
                    + ($tax * $item['quantity'])
                    - $item['discount'] * $item['quantity'];
                $total += $productSubtotal;
            }
        }
        return $total;
    }

    public static function cart_clean($request = null): void
    {
        $cartGroupIDs = CartManager::get_cart_group_ids(request: $request, type: 'checked');
        self::cartCleanByCartGroupIds(cartGroupIDs: $cartGroupIDs);
    }

    public static function cartCleanByCartGroupIds($cartGroupIDs): void
    {
        CartShipping::whereIn('cart_group_id', $cartGroupIDs)->delete();
        Cart::whereIn('cart_group_id', $cartGroupIDs)->where(['is_checked' => 1])->delete();

        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('payment_method');
        session()->forget('shipping_method_id');
        session()->forget('billing_address_id');
        session()->forget('order_id');
        session()->forget('cart_group_id');
        session()->forget('order_note');
    }

    public static function cart_clean_for_api_digital_payment($data): void
    {
        $cartIds = Cart::when($data['request']['is_guest'], function ($query) use ($data) {
            return $query->where(['is_guest' => 1]);
        })->when(!($data['request']['is_guest']), function ($query) use ($data) {
            return $query->where(['is_guest' => 0]);
        })->where(['customer_id' => $data['request']['customer_id'], 'is_checked' => 1])
            ->groupBy('cart_group_id')->pluck('cart_group_id')->toArray();

        CartShipping::whereIn('cart_group_id', $cartIds)->delete();
        Cart::when($data['request']['is_guest'], function ($query) use ($data) {
            return $query->where(['is_guest' => '1']);
        })->when(!($data['request']['is_guest']), function ($query) use ($data) {
            return $query->where(['is_guest' => '0']);
        })->where(['customer_id' => $data['request']['customer_id'], 'is_checked' => 1])
            ->delete();
    }

    public static function addToCartPhysicalProduct($request, $product, $shippingType, $sellerShippingList): array
    {
        $price = 0;
        $string = '';
        $variations = [];

        $user = Helpers::get_customer($request);
        $guestId = session('guest_id') ?? ($request->guest_id ?? 0);

        if (($product['product_type'] == 'physical') && ($product['current_stock'] < $request['quantity'])) {
            return ['status' => 0, 'message' => translate('out_of_stock!')];
        }

        if ($user == 'offline') {
            $customerId = $guestId;
            $isGuest = 1;
        } else {
            $customerId = $user->id;
            $isGuest = 0;
        }

        if ($request->has('color')) {
            $string .= Color::where(['code' => $request['color']])->first()->name;
            $variations['color'] = $string;
        }

        // Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
        $choices = [];
        foreach (json_decode($product->choice_options) as $key => $choice) {
            $choices[$choice->name] = $request[$choice->name];
            $variations[$choice->title] = $request[$choice->name];
            if ($string != null) {
                $string .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $string .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        if (!($request['buy_now'])) {
            if ($request['shipping_method_exist'] && $request->has('product_variation_code') && $request['product_variation_code']) {
                $string = str_replace(' ', '', $request['product_variation_code']);
            }
        }

        $cartArray = [
            'color' => $request['color'] ?? null,
            'product_id' => $product['id'],
            'product_type' => $product['product_type'],
            'choices' => json_encode($choices),
            'variations' => json_encode($variations),
            'variant' => $string,
        ];

        // Check the string and decreases quantity for the stock
        if ($string != null) {
            $count = count(json_decode($product->variation));
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variation)[$i]->type == $string) {
                    $price = json_decode($product->variation)[$i]->price;
                    if (json_decode($product->variation)[$i]->qty < $request['quantity']) {
                        return ['status' => 0, 'message' => translate('out_of_stock!')];
                    }
                }
            }
        } else {
            $price = $product->unit_price;
        }

        $tax = Helpers::tax_calculation(product: $product, price: $price, tax: $product['tax'], tax_type: 'percent');
        $getProductDiscount = Helpers::get_product_discount($product, $price);

        $cartArray += [
            'customer_id' => ($user == 'offline' ? $guestId : $user->id),
            'product_id' => $request['id'],
            'product_type' => $product['product_type'],
            'quantity' => $request['quantity'],
            'price' => $price,
            'tax' => $tax,
            'tax_model' => $product->tax_model,
            'discount' => $getProductDiscount,
            'is_checked' => 1,
            'slug' => $product['slug'],
            'name' => $product['name'],
            'thumbnail' => $product['thumbnail'],
            'seller_id' => ($product->added_by == 'admin') ? 1 : $product->user_id,
            'seller_is' => $product['added_by'],
            'created_at' => now(),
            'updated_at' => now(),
            'shop_info' => $product->added_by == 'admin' ? getWebConfig(name: 'company_name') : Shop::where(['seller_id' => $product->user_id])->first()->name,
            'shipping_cost' => $product->product_type == 'physical' ? CartManager::get_shipping_cost_for_product_category_wise($product, $request['quantity']) : 0,
            'shipping_type' => $shippingType,
            'is_guest' => ($user == 'offline' ? 1 : 0),
        ];

        $cartCheck = Cart::where(['customer_id' => $customerId, 'is_guest' => $isGuest, 'seller_id' => ($product->added_by == 'admin') ? 1 : $product->user_id, 'seller_is' => $product->added_by])->first();
        if ($cartCheck) {
            $cartArray['cart_group_id'] = $cartCheck['cart_group_id'];
        } else {
            $cartArray['cart_group_id'] = ($user == 'offline' ? 'guest' : $user->id) . '-' . Str::random(5) . '-' . time();
        }

        $cart = Cart::where(['product_id' => $request['id'], 'customer_id' => $customerId, 'is_guest' => $isGuest, 'variant' => $string])->first();
        if ($cart) {
            $cartArray['cart_group_id'] = $cart['cart_group_id'];
            Cart::where(['id' => $cart['id']])->update($cartArray);
        } else {
            $cartID = Cart::insertGetId($cartArray);
            $cart = Cart::where(['id' => $cartID])->first();
        }

        if ($request['buy_now'] == 1) {
            $calculateTax = $product['tax_model'] == 'include' ? 0 : ($tax * $request['quantity']);
            $productTotalPrice = ($price * $request['quantity']) + $calculateTax - ($getProductDiscount * $request['quantity']);
            $verifyStatus = OrderManager::checkSingleProductMinimumOrderAmountVerify(request: $request, product: $product, totalAmount: $productTotalPrice);
            if ($verifyStatus['status'] == 0) {
                return ['status' => 0, 'message' => $verifyStatus['message']];
            }

            Cart::where(['customer_id' => ($user == 'offline' ? $guestId : $user->id), 'is_guest' => ($user == 'offline' ? 1 : 0)])
                ->update(['is_checked' => 0]);

            Cart::where(['id' => $cart['id']])->update(['is_checked' => 1]);

            if ($product['product_type'] == 'digital') {
                return [
                    'status' => 1,
                    'redirect_to' => 'checkout',
                    'cart' => $cart,
                    'message' => translate('successfully_added!'),
                ];
            }

            if ($product['product_type'] == 'physical' && $shippingType == 'order_wise') {
                if ($request['shipping_method_exist'] && $request['shipping_method_id'] && count($sellerShippingList) > 0) {
                    $cart->update(['is_checked' => 1]);
                    $cartGroupIds = Cart::where(['customer_id' => ($user == 'offline' ? $guestId : $user->id), 'is_guest' => ($user == 'offline' ? 1 : 0)])
                        ->pluck('cart_group_id');
                    if (count($cartGroupIds) > 0) {
                        CartShipping::whereIn('cart_group_id', $cartGroupIds)->delete();
                    }

                    $shipping = CartShipping::where(['cart_group_id' => $cart['cart_group_id']])->first();
                    if (!isset($shipping)) {
                        $shipping = new CartShipping();
                    }
                    $getShippingCost = ShippingMethod::find($request['shipping_method_id']);
                    if (!$getShippingCost) {
                        return ['status' => 0, 'message' => translate('Selected_shipping_method_not_found')];
                    }
                    $shipping['cart_group_id'] = $cart['cart_group_id'];
                    $shipping['shipping_method_id'] = $request['shipping_method_id'];
                    $shipping['shipping_cost'] = $getShippingCost->cost ?? 0;
                    $shipping->save();

                    $cart['free_delivery_order_amount'] = OrderManager::free_delivery_order_amount($cart['cart_group_id']);

                    return [
                        'status' => 1,
                        'redirect_to' => 'checkout',
                        'cart' => $cart,
                        'cart_shipping_cost' => $getShippingCost->cost ?? 0,
                        'message' => translate('successfully_added!'),
                    ];
                }
                return [
                    'status' => $sellerShippingList && count($sellerShippingList) > 0 ? 2 : 0,
                    'message' => $sellerShippingList && count($sellerShippingList) > 0 ? translate('Please_select_shipping_method') : translate('Shipping_Not_Available_for_this_Shop'),
                    'shipping_method_list' => $sellerShippingList,
                ];
            } elseif ($product['product_type'] == 'physical' && ($shippingType == 'category_wise' || $shippingType == 'product_wise')) {
                $cart->update([
                    'is_checked' => 1,
                    'shipping_cost' => CartManager::get_shipping_cost_for_product_category_wise($product, $request->quantity) ?? 0,
                ]);

                $cart['free_delivery_order_amount'] = OrderManager::free_delivery_order_amount($cart['cart_group_id']);

                return [
                    'status' => 1,
                    'redirect_to' => 'checkout',
                    'cart' => $cart,
                    'cart_shipping_cost' => $getShippingCost->cost ?? 0,
                    'message' => translate('successfully_added!'),
                ];
            }
            $cart->update(['is_checked' => 1]);
        }

        if ($product->product_type == 'physical') {
            $cart['free_delivery_order_amount'] = OrderManager::free_delivery_order_amount($cart['cart_group_id']);
        }

        return [
            'status' => 1,
            'in_cart_key' => $cart['id'],
            'cart' => $cart,
            'message' => translate('successfully_added!'),
        ];
    }

    public static function addToCartDigitalProduct($request, $product, $shippingType, $sellerShippingList): array
    {
        $price = $product->unit_price;
        $digitalVariation = DigitalProductVariation::where(['product_id' => $product['id'], 'variant_key' => $request['variant_key']])->first();
        if ($request['variant_key'] && $digitalVariation) {
            $price = $digitalVariation['price'];
        }
        $user = Helpers::get_customer($request);
        $guestId = session('guest_id') ?? ($request->guest_id ?? 0);

        if ($user == 'offline') {
            $customerId = $guestId;
            $isGuest = 1;
        } else {
            $customerId = $user->id;
            $isGuest = 0;
        }

        $tax = Helpers::tax_calculation(product: $product, price: $price, tax: $product['tax'], tax_type: 'percent');
        $getProductDiscount = Helpers::get_product_discount($product, $price);
        $cartArray = [
            'customer_id' => $customerId,
            'product_id' => $request['id'],
            'product_type' => $product['product_type'],
            'digital_product_type' => $product['digital_product_type'],
            'choices' => json_encode([]),
            'variations' => json_encode([]),
            'variant' => $request['variant_key'],
            'quantity' => $request['quantity'],
            'price' => $price,
            'tax' => $tax,
            'tax_model' => $product['tax_model'],
            'discount' => $getProductDiscount,
            'is_checked' => 1,
            'slug' => $product['slug'],
            'name' => $product['name'],
            'thumbnail' => $product['thumbnail'],
            'seller_id' => ($product->added_by == 'admin') ? 1 : $product->user_id,
            'seller_is' => $product['added_by'],
            'created_at' => now(),
            'updated_at' => now(),
            'shop_info' => $product->added_by == 'admin' ? getWebConfig(name: 'company_name') : Shop::where(['seller_id' => $product->user_id])->first()->name,
            'shipping_cost' => $product['product_type'] == 'physical' ? CartManager::get_shipping_cost_for_product_category_wise($product, $request['quantity']) : 0,
            'shipping_type' => $shippingType,
            'is_guest' => $isGuest,
        ];

        $cartCheck = Cart::where(['customer_id' => $customerId, 'is_guest' => $isGuest, 'seller_id' => ($product->added_by == 'admin') ? 1 : $product->user_id, 'seller_is' => $product->added_by])->first();
        if ($cartCheck) {
            $cartArray['cart_group_id'] = $cartCheck['cart_group_id'];
        } else {
            $cartArray['cart_group_id'] = ($user == 'offline' ? 'guest' : $user->id) . '-' . Str::random(5) . '-' . time();
        }

        $cart = Cart::where(['product_id' => $request->id, 'customer_id' => $customerId, 'is_guest' => $isGuest, 'variant' => $request['variant_key']])->first();
        if ($cart) {
            Cart::where(['id' => $cart['id']])->update($cartArray);
        } else {
            $cartID = Cart::insertGetId($cartArray);
            $cart = Cart::where(['id' => $cartID])->first();
        }

        if ($request['buy_now'] == 1) {
            $calculateTax = $product['tax_model'] == 'include' ? 0 : ($tax * $request['quantity']);
            $productTotalPrice = ($price * $request['quantity']) + $calculateTax - ($getProductDiscount * $request['quantity']);
            $verifyStatus = OrderManager::checkSingleProductMinimumOrderAmountVerify(request: $request, product: $product, totalAmount: $productTotalPrice);
            if ($verifyStatus['status'] == 0) {
                return ['status' => 0, 'message' => $verifyStatus['message']];
            }

            Cart::where(['customer_id' => ($user == 'offline' ? $guestId : $user->id), 'is_guest' => ($user == 'offline' ? 1 : 0)])
                ->update(['is_checked' => 0]);

            Cart::where(['id' => $cart['id']])->update(['is_checked' => 1]);

            if ($product['product_type'] == 'digital') {
                return [
                    'status' => 1,
                    'redirect_to' => 'checkout',
                    'cart' => $cart,
                    'message' => translate('successfully_added!'),
                ];
            }
        }

        return [
            'status' => 1,
            'in_cart_key' => $cart['id'],
            'cart' => $cart,
            'message' => translate('successfully_added!'),
        ];
    }

    public static function add_to_cart($request, $from_api = false): array
    {
        $product = Product::with(['digitalVariation'])->where(['id' => $request['id']])->first();

        $shippingMethod = Helpers::get_business_settings('shipping_method');
        $adminShipping = ShippingType::where('seller_id', 0)->first();
        $sellerShippingList = null;
        if ($shippingMethod == 'inhouse_shipping') {
            $shippingType = isset($adminShipping) == true ? $adminShipping->shipping_type : 'order_wise';
            $sellerShippingList = $shippingType == 'order_wise' ? ShippingMethod::where(['status' => 1])->where(['creator_type' => 'admin'])->get() : null;
        } else {
            if ($product->added_by == 'admin') {
                $shippingType = isset($adminShipping) == true ? $adminShipping->shipping_type : 'order_wise';
                $sellerShippingList = $shippingType == 'order_wise' ? ShippingMethod::where(['status' => 1])->where(['creator_type' => 'admin'])->get() : null;
            } else {
                $sellerShipping = ShippingType::where('seller_id', $product['user_id'])->first();
                $shippingType = isset($sellerShipping) == true ? $sellerShipping->shipping_type : 'order_wise';
                $sellerShippingList = ShippingMethod::where(['status' => 1])->where(['creator_id' => $product->user_id, 'creator_type' => 'seller'])->get();
            }
        }

        if ($product['product_type'] == 'digital') {
            return self::addToCartDigitalProduct($request, $product, $shippingType, $sellerShippingList);
        } else {
            return self::addToCartPhysicalProduct($request, $product, $shippingType, $sellerShippingList);
        }
    }

    public static function update_cart_qty($request): array
    {
        $user = Helpers::get_customer($request);
        $guest_id = session('guest_id') ?? ($request->guest_id ?? 0);
        $status = 1;
        $qty = 0;
        $cart = Cart::where(['id' => $request->key, 'customer_id' => ($user == 'offline' ? $guest_id : $user->id)])->first();

        if (!$cart) {
            return [
                'status' => 0,
                'qty' => $request['quantity'],
                'message' => translate('Product_not_found_in_cart'),
            ];
        }

        $product = Product::find($cart['product_id']);
        $count = count(json_decode($product->variation));
        if ($count) {
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variation)[$i]->type == $cart['variant']) {
                    if (json_decode($product->variation)[$i]->qty < $request->quantity) {
                        $status = 0;
                        $qty = $cart['quantity'];
                    }
                }
            }
        } else if (($product['product_type'] == 'physical') && $product['current_stock'] < $request->quantity) {
            $status = 0;
            $qty = $cart['quantity'];
        }

        if ($status) {
            $qty = $request->quantity;
            $cart['quantity'] = $request->quantity;
            $cart['shipping_cost'] = $product->product_type == 'physical' ? CartManager::get_shipping_cost_for_product_category_wise($product, $request->quantity) : 0;
        }

        $cart->save();

        if ($request['buy_now'] == 1) {
            Cart::where(['customer_id' => ($user == 'offline' ? $guest_id : $user->id), 'is_guest' => ($user == 'offline' ? 1 : 0)])
                ->update(['is_checked' => 0]);
            Cart::where(['id' => $request->key, 'customer_id' => ($user == 'offline' ? $guest_id : $user->id)])->update(['is_checked' => 1]);
        }

        return [
            'status' => $status,
            'qty' => $qty,
            'message' => $status == 1 ? translate('successfully_updated!') : translate('sorry_stock_is_limited')
        ];
    }

    public static function get_shipping_cost_for_product_category_wise($product, $qty)
    {
        $shippingMethod = Helpers::get_business_settings('shipping_method');
        $cost = 0;

        if ($shippingMethod == 'inhouse_shipping') {
            $admin_shipping = ShippingType::where('seller_id', 0)->first();
            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
        } else {
            if ($product->added_by == 'admin') {
                $admin_shipping = ShippingType::where('seller_id', 0)->first();
                $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
            } else {
                $seller_shipping = ShippingType::where('seller_id', $product->user_id)->first();
                $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
            }
        }

        if ($shipping_type == 'category_wise') {
            $categoryID = 0;
            foreach (json_decode($product->category_ids) as $ct) {
                if ($ct->position == 1) {
                    $categoryID = $ct->id;
                }
            }

            if ($shippingMethod == 'inhouse_shipping') {
                $category_shipping_cost = CategoryShippingCost::where('seller_id', 0)->where('category_id', $categoryID)->first();
            } else {
                if ($product->added_by == 'admin') {
                    $category_shipping_cost = CategoryShippingCost::where('seller_id', 0)->where('category_id', $categoryID)->first();
                } else {
                    $category_shipping_cost = CategoryShippingCost::where('seller_id', $product->user_id)->where('category_id', $categoryID)->first();
                }
            }

            if (isset($category_shipping_cost->multiply_qty) && $category_shipping_cost->multiply_qty == 1) {
                $cost = $qty * $category_shipping_cost->cost;
            } else {
                $cost = $category_shipping_cost->cost ?? 0;
            }

        } else if ($shipping_type == 'product_wise') {
            if ($product->multiply_qty == 1) {
                $cost = $qty * $product->shipping_cost;
            } else {
                $cost = $product->shipping_cost;
            }
        } else {
            $cost = 0;
        }

        return $cost;
    }

    public static function get_shipping_cost_saved_for_free_delivery($groupId = null, $type = null)
    {
        $costSaved = 0;

        $cartGroup = Cart::where(['product_type' => 'physical'])
            ->when($groupId != null, function ($query) use ($groupId) {
                return $query->where('cart_group_id', $groupId);
            })
            ->when(($groupId == null && $type != 'checked'), function ($query) {
                return $query->whereIn('cart_group_id', CartManager::get_cart_group_ids());
            })
            ->when(($groupId == null && $type == 'checked'), function ($query) {
                return $query->whereIn('cart_group_id', CartManager::get_cart_group_ids(type: 'checked'));
            })
            ->when($type == 'checked', function ($query) {
                return $query->where(['is_checked' => 1]);
            })->get()->groupBy('cart_group_id');

        foreach ($cartGroup as $cart) {
            if ($cart->count() > 0) {
                $freeDeliveryCheck = OrderManager::free_delivery_order_amount($cart[0]->cart_group_id);
                $costSaved += $freeDeliveryCheck['shipping_cost_saved'];
            }
        }

        return $costSaved;
    }

    public static function product_stock_check($carts): bool
    {
        $status = true;

        foreach ($carts as $cart) {
            if ($cart->product) {
                $product = $cart->product;
                $count = count(json_decode($product->variation));
                if ($count) {
                    for ($i = 0; $i < $count; $i++) {
                        if (json_decode($product->variation)[$i]->type == $cart['variant']) {
                            if (json_decode($product->variation)[$i]->qty < $cart->quantity) {
                                $status = false;
                            }
                        }
                    }
                } else if (($product['product_type'] == 'physical') && $product['current_stock'] < $cart->quantity) {
                    $status = false;
                }
            } else {
                $status = false;
            }
        }
        return $status;
    }
}
