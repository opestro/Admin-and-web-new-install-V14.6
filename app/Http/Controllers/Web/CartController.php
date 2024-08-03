<?php

namespace App\Http\Controllers\Web;


use App\Models\CartShipping;
use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Color;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Utils\CartManager;
use App\Utils\OrderManager;
use App\Utils\ProductManager;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private OrderDetail $order_details,
        private Product $product,
    ) {

    }
    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $productVariationCode = $request['product_variation_code'];
        $str = '';
        $quantity = 0;
        $price = 0;
        $color_name = '';
        $requestQuantity = $request['quantity'];

        if ($request->has('color')) {
            $str = Color::where('code', $request['color'])->first()->name;
        }

        foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        $requestQuantity = $productVariationCode != $str ? $product['minimum_order_qty'] : $request['quantity'];
        $inCartExistStatus = 0;
        $inCartExistKey = null;
        $getCartList = \App\Utils\CartManager::get_cart();
        foreach ($getCartList as $cartItem) {
            if ($cartItem['product_id'] == $product['id'] && $cartItem['variant'] == $str) {
                $inCartExistStatus = 1;
                $inCartExistKey = $cartItem['id'];
                $requestQuantity = $productVariationCode == $str ? $request['quantity'] : $cartItem['quantity'];
            }
        }

        if ($str != null) {
            $count = count(json_decode($product->variation));
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variation)[$i]->type == $str) {
                    $tax = $product->tax_model=='exclude' ? Helpers::tax_calculation(product: $product, price: json_decode($product->variation)[$i]->price, tax: $product['tax'], tax_type: $product['tax_type']):0;
                    $update_tax = $tax * $requestQuantity;
                    $discount = Helpers::get_product_discount($product, json_decode($product->variation)[$i]->price);
                    $price = json_decode($product->variation)[$i]->price - $discount + $tax;
                    $discountedUnitPrice = json_decode($product->variation)[$i]->price - $discount;
                    $unit_price = json_decode($product->variation)[$i]->price;
                    $quantity = json_decode($product->variation)[$i]->qty;
                }
            }
        } else {
            $tax = $product->tax_model=='exclude' ? Helpers::tax_calculation(product: $product, price: $product->unit_price, tax: $product['tax'], tax_type: $product['tax_type']) : 0;
            $update_tax = $tax * $requestQuantity;
            $discount = Helpers::get_product_discount($product, $product->unit_price);
            $price = $product->unit_price - $discount + $tax;
            $discountedUnitPrice = $product->unit_price - $discount;
            $unit_price = $product->unit_price;
            $quantity = $product->current_stock;
        }

        $delivery_info = [];

        $stock_limit = 0;
        if(theme_root_path() == 'theme_fashion') {
            $delivery_info = ProductManager::get_products_delivery_charge($product, $requestQuantity);
            $stock_limit= \App\Models\BusinessSetting::where('type','stock_limit')->first()->value;
            if ($request->has('color')) {
                $color_name = Color::where(['code'=>$request->color])->first()->name;
            }
        }

        return [
            'price' => \App\Utils\Helpers::currency_converter($price * $requestQuantity),
            'discount' => \App\Utils\Helpers::currency_converter($discount),
            'discount_amount' => $discount,
            'tax' => $product->tax_model=='exclude' ? \App\Utils\Helpers::currency_converter($tax) : 'incl.',
            'update_tax' => $product->tax_model=='exclude' ? \App\Utils\Helpers::currency_converter($update_tax) : 'incl.', // for others theme
            'quantity' => $product['product_type'] == 'physical' ? $quantity : 100,
            'delivery_cost' => isset($delivery_info['delivery_cost']) ? \App\Utils\Helpers::currency_converter($delivery_info['delivery_cost']):0,
            'unit_price'=>\App\Utils\Helpers::currency_converter($price), //fashion theme
            'total_unit_price'=>\App\Utils\Helpers::currency_converter($unit_price), //fashion theme
            'discounted_unit_price'=>\App\Utils\Helpers::currency_converter($discountedUnitPrice), //fashion theme
            'color_name'=>$color_name,
            'stock_limit'=>$stock_limit,

            'in_cart_status' => $inCartExistStatus,
            'in_cart_quantity' => $requestQuantity,
            'in_cart_key' => $inCartExistKey,
            'variation_code' => $str,
        ];
    }

    public function addToCart(Request $request): JsonResponse|RedirectResponse
    {
        $cart = CartManager::add_to_cart($request);
        if ($cart['status'] == 2) {
            $cart['shippingMethodHtmlView'] = view(VIEW_FILE_NAMES['product_shipping_method_modal_view_partials'], [
                'shipping_method_list' => $cart['shipping_method_list'],
                'productData' => $request->all(),
            ])->render();
        }
        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('coupon_seller_id');

        if (isset($cart['redirect_to']) && $cart['redirect_to'] == 'checkout') {
            $cart['redirect_to_url'] = route('checkout-details');
            return request()->ajax() ? response()->json($cart) : redirect()->route('checkout-details');
        }

        if (!request()->ajax() && $cart['status'] == 0) {
            Toastr::warning($cart['message']);
            return back();
        }
        return response()->json($cart);

    }

    public function updateNavCart(): JsonResponse
    {
        return response()->json(['data' => view(VIEW_FILE_NAMES['products_cart_partials'])->render(), 'mobile_nav' => view(VIEW_FILE_NAMES['products_mobile_nav_partials'])->render()]);
    }

    /**
     * For theme fashion floating nav
     */
    public function update_floating_nav(): JsonResponse
    {
        return response()->json(['floating_nav' => view(VIEW_FILE_NAMES['products_floating_nav_partials'])->render()]);
    }

    /**
     * removes from Cart
     */
    public function removeFromCart(Request $request)
    {
        $user = Helpers::get_customer();

        Cart::where(['id' => $request->key, 'customer_id' => ($user == 'offline' ? session('guest_id') : auth('customer')->id())])->delete();

        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('coupon_seller_id');
        session()->forget('shipping_method_id');
        session()->forget('order_note');

        return response()->json(['data' => view(VIEW_FILE_NAMES['products_cart_details_partials'], compact('request'))->render(), 'message'=>translate('Item_has_been_removed_from_cart')]);
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $response = CartManager::update_cart_qty($request);

        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('coupon_seller_id');

        if ($response['status'] == 0) {
            return response()->json($response);
        }
        return response()->json(view(VIEW_FILE_NAMES['products_cart_details_partials'], compact('request'))->render());
    }

    // Updated the quantity for a cart item
    public function updateQuantity_guest(Request $request): JsonResponse
    {
        $sub_total = 0;
        $response = CartManager::update_cart_qty($request);
        $cart = CartManager::get_cart();
        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('coupon_seller_id');

        $product = Cart::find($request['key']);
        $quantity_price = Helpers::currency_converter($product['price'] * (int)$product['quantity']);
        $discount_price = Helpers::currency_converter(($product['price'] - $product['discount']) * (int)$product['quantity']);
        $total_discount = 0;
        foreach ($cart as $cartItem) {
            $sub_total += ($cartItem['price'] - $cartItem['discount']) * $cartItem['quantity'];
            $total_discount += $cartItem['discount'] * $cartItem['quantity'];
        }
        $total_price = Helpers::currency_converter($sub_total);
        $total_discount_price = Helpers::currency_converter($total_discount);

        if ($response['status'] == 0) {
            return response()->json([
                'status' => $response['status'],
                'message' => $response['message'],
                'qty' => $response['status'] == 0 ? $response['qty'] : $request->quantity,
            ]);
        }
        /** for default theme nav cart ,showing free delivery amount */
        $free_delivery_status = OrderManager::free_delivery_order_amount($cart[0]->cart_group_id);

        return response()->json([
            'status' => $response['status'],
            'message' => translate('successfully_updated!'),
            'qty' => $response['status'] == 0 ? $response['qty'] : $request->quantity,
            'total_price' => $total_price,
            'discount_price' => $discount_price,
            'quantity_price' => $quantity_price,
            'total_discount_price' => $total_discount_price,
            'free_delivery_status' => $free_delivery_status,
            'in_cart_key' => $product['id'],
        ]);
    }

    public function order_again(Request $request)
    {
        $data = OrderManager::order_again($request);
        $orderProductCount = $data['order_product_count'];
        $addToCartCount = $data['add_to_cart_count'];
        $failedAddToCartCount = $data['failedAddToCartCount'];
        $message = $failedAddToCartCount == 0 ? translate('added_to_cart_successfully!') : translate('Some_items_were_not_added_to_your_cart_because_they_are_currently_unavailable_for_purchase.!');

        if ($orderProductCount == $addToCartCount) {
            session()->forget('coupon_code');
            session()->forget('coupon_type');
            session()->forget('coupon_bearer');
            session()->forget('coupon_discount');
            session()->forget('coupon_seller_id');
            session()->forget('shipping_method_id');
            session()->forget('order_note');

            if (auth('customer')->check()) {
                return response()->json([
                    'status' => 1,
                    'redirect_url' => route('shop-cart'),
                    'message' => $message
                ], 200);
            } else {
                return response()->json(['message' => $message], 200);
            }
        } elseif ($addToCartCount > 0) {
            if (auth('customer')->check()) {
                return response()->json([
                    'status' => 1,
                    'redirect_url' => route('shop-cart'),
                    'message' => $message
                ], 200);
            } else {
                return response()->json(['message' => $message], 200);
            }
        } else {
            if (auth('customer')->check()) {
                return response()->json([
                    'status' => 0,
                    'message' => translate('all_items_were_not_added_to_cart_as_they_are_currently_unavailable_for_purchase!')
                ], 200);
            } else {
                return response()->json([
                    'message' => translate('all_items_were_not_added_to_cart_as_they_are_currently_unavailable_for_purchase!')
                ], 403);
            }
        }
    }

    function update_variation(Request $request){
        $product = Product::find($request->product_id);
        $user = Helpers::get_customer($request);
        $str = '';
        $variations = [];
        $price = 0;
        $discount = 0;
        if ($request->has('color')) {
            $str = Color::where('code', $request['color'])->first()->name;
            $variations['color'] = $str;
        }

        //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
        $choices = [];
        foreach (json_decode($product->choice_options) as $key => $choice) {

            $choices[$choice->name] = $request[$choice->name];
            $variations[$choice->title] = $request[$choice->name];
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        if ($str != null) {
            $count = count(json_decode($product->variation));
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variation)[$i]->type == $str) {
                    $tax = $product->tax_model=='exclude' ? Helpers::tax_calculation(product: $product, price: json_decode($product->variation)[$i]->price, tax: $product['tax'], tax_type: $product['tax_type']):0;
                    $discount = Helpers::get_product_discount($product, json_decode($product->variation)[$i]->price);
                    $price = json_decode($product->variation)[$i]->price - $discount + $tax;
                    $quantity = json_decode($product->variation)[$i]->qty;
                }
            }
        } else {
            $tax = $product->tax_model=='exclude' ? Helpers::tax_calculation(product: $product, price:  $product->unit_price, tax: $product['tax'], tax_type: $product['tax_type']) : 0;
            $discount = Helpers::get_product_discount($product, $product->unit_price);
            $price = $product->unit_price - $discount + $tax;
            $quantity = $product->current_stock;
        }
        $cart = Cart::where([
            'product_id' => $request->product_id,
            'customer_id' => $user == 'offline' ? session('guest_id') : $user->id,
            'is_guest' => $user == 'offline' ? 1 : '0',
            'variant' => $str
        ])->first();
        if (isset($cart) == false) {
            $cart = Cart::find($request->id);
            $cart['color']          = $request->has('color') ? $request['color'] : null;
            $cart['choices']        = json_encode($choices);

            $cart['variations']     = json_encode($variations);
            $cart['variant']        = $str;

            //Check the string and decreases quantity for the stock
            if ($str != null) {
                $count = count(json_decode($product->variation));
                for ($i = 0; $i < $count; $i++) {
                    if (json_decode($product->variation)[$i]->type == $str) {
                        $price = json_decode($product->variation)[$i]->price;
                        if (json_decode($product->variation)[$i]->qty < $request['quantity']) {
                            return [
                                'status' => 0,
                                'message' => translate('out_of_stock!')
                            ];
                        }
                    }
                }
            } else {
                $price = $product->unit_price;
            }
            $cart['price'] = $price;
            $cart['discount'] = $discount;
            $cart['tax'] = $tax;
            $cart['quantity'] = $request['quantity'];
            $cart->save();

            return [
                'status' => 1,
                'message' => translate('successfully_added!'),
                'price' => \App\Utils\Helpers::currency_converter($price),
                'discount' => \App\Utils\Helpers::currency_converter($discount*$request['quantity']),
                'data' => view(VIEW_FILE_NAMES['products_cart_details_partials'], compact('request'))->render()
            ];
        } else {
            return [
                'status' => 0,
                'message' => translate('already_added!')
            ];
        }
    }
    public function remove_all_cart(){
        $user = Helpers::get_customer();

        Cart::where([
            'customer_id'=> ($user == 'offline' ? session('guest_id') : auth('customer')->id()),
            'is_guest' => ($user == 'offline' ? 1 : '0'),
        ])->delete();
        return redirect()->back();
    }


    public function updateCheckedCartItems(Request $request): JsonResponse
    {
        $user = Helpers::get_customer();
        Cart::where([
            'customer_id' => ($user == 'offline' ? session('guest_id') : auth('customer')->id()),
            'is_guest' => ($user == 'offline' ? 1 : '0'),
        ])->update(['is_checked' => 0]);

        if ($request['ids']) {
            Cart::where([
                'customer_id' => ($user == 'offline' ? session('guest_id') : auth('customer')->id()),
                'is_guest' => ($user == 'offline' ? 1 : '0'),
            ])->whereIn('id', $request['ids'])->update(['is_checked' => 1]);
        }

        $cartGroupIds = Cart::where([
            'customer_id' => ($user == 'offline' ? session('guest_id') : auth('customer')->id()),
            'is_guest' => ($user == 'offline' ? 1 : '0'),
        ])->pluck('cart_group_id');

        if (count($cartGroupIds) > 0) {
            CartShipping::whereIn('cart_group_id', $cartGroupIds)->delete();
        }

        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');

        return response()->json([
            'message' => translate('Successfully_Update'),
            'htmlView' => view(VIEW_FILE_NAMES['products_cart_details_partials'], compact('request'))->render()
        ], 200);
    }
}
