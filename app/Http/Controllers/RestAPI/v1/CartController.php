<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Utils\CartManager;
use App\Utils\Helpers;
use App\Utils\OrderManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function React\Promise\all;

class CartController extends Controller
{
    public function __construct(
        private Order $order,
    )
    {
    }

    public function cart(Request $request)
    {
        $user = Helpers::get_customer($request);
        $cart_query = Cart::with('product:id,name,slug,current_stock,minimum_order_qty,variation', 'shop');
        if ($user == 'offline') {
            $cart = $cart_query->where(['customer_id' => $request->guest_id, 'is_guest' => 1])->get();
        } else {
            $cart = $cart_query->where(['customer_id' => $user->id, 'is_guest' => '0'])->get();
        }

        if ($cart) {
            foreach ($cart as $key => $value) {
                if (!isset($value['product'])) {
                    $cart_data = Cart::find($value['id']);
                    $cart_data->delete();

                    unset($cart[$key]);
                }
            }

            $cart->map(function ($data) use ($request) {
                $product = Product::active()->find($data->product_id);
                if ($product) {
                    $data['is_product_available'] = 1;
                } else {
                    $data['is_product_available'] = 0;
                }
                $data['choices'] = json_decode($data['choices']);
                $data['variations'] = json_decode($data['variations']);

                $data['minimum_order_amount_info'] = OrderManager::minimum_order_amount_verify($request, $data['cart_group_id'])['minimum_order_amount'];

                $cart_group = Cart::where(['product_type' => 'physical'])->where('cart_group_id', $data['cart_group_id'])->get()->groupBy('cart_group_id');
                if (isset($cart_group[$data['cart_group_id']])) {
                    $data['free_delivery_order_amount'] = OrderManager::free_delivery_order_amount($data['cart_group_id']);
                } else {
                    $data['free_delivery_order_amount'] = [
                        'status' => 0,
                        'amount' => 0,
                        'percentage' => 0,
                        'shipping_cost_saved' => 0,
                    ];
                }

                $data['product']['total_current_stock'] = isset($data['product']['current_stock']) ? $data['product']['current_stock'] : 0;
                if (isset($data['product']['variation']) && !empty($data['product']['variation'])) {
                    $variants = json_decode($data['product']['variation']);
                    foreach ($variants as $var) {
                        if ($data['variant'] == $var->type) {
                            $data['product']['total_current_stock'] = $var->qty;
                        }
                    }
                }
                unset($data['product']['variation']);
                return $data;
            });
        }

        return response()->json($cart, 200);
    }

    public function add_to_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'quantity' => 'required',
        ], [
            'id.required' => translate('Product ID is required!')
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $cart = CartManager::add_to_cart($request);
        return response()->json($cart, 200);
    }

    public function update_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'quantity' => 'required',
        ], [
            'key.required' => translate('Cart key or ID is required!')
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $response = CartManager::update_cart_qty($request);
        return response()->json($response);
    }

    public function remove_from_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required'
        ], [
            'key.required' => translate('Cart key or ID is required!')
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $user = Helpers::get_customer($request);
        Cart::where([
            'id' => $request->key,
            'customer_id' => ($user == 'offline' ? (session('guest_id') ?? $request->guest_id) : $user->id),
            'is_guest' => ($user == 'offline' ? 1 : '0'),
        ])->delete();
        return response()->json(translate('successfully_removed'));
    }

    public function remove_all_from_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required'
        ], [
            'key.required' => translate('Cart key or ID is required!')
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $user = Helpers::get_customer($request);
        Cart::where([
            'customer_id' => ($user == 'offline' ? $request->guest_id : $user->id),
            'is_guest' => ($user == 'offline' ? 1 : '0'),
        ])->delete();
        return response()->json(translate('successfully_removed'));
    }

    public function updateCheckedCartItems(Request $request): JsonResponse
    {
        if ($request['action'] == 'unchecked') {
            Cart::whereIn('id', $request['ids'])->update(['is_checked' => 0]);
        } elseif ($request['action'] == 'checked') {
            Cart::whereIn('id', $request['ids'])->update(['is_checked' => 1]);
        }
        return response()->json(translate('Successfully_Update'), 200);
    }
}
