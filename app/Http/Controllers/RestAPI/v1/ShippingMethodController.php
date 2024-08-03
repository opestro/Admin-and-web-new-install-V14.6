<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartShipping;
use App\Models\ShippingMethod;
use App\Models\ShippingType;
use App\Utils\CartManager;
use App\Utils\Helpers;
use App\Utils\OrderManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingMethodController extends Controller
{
    public function get_shipping_method_info($id)
    {
        try {
            $shipping = ShippingMethod::find($id);
            return response()->json($shipping, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function shipping_methods_by_seller(Request $request, $id, $seller_is)
    {
        $seller_is = $seller_is == 'admin' ? 'admin' : 'seller';
        return response()->json(Helpers::get_shipping_methods($id, $seller_is), 200);
    }

    public function choose_for_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_group_id' => 'required',
            'id' => 'required'
        ], [
            'id.required' => translate('shipping_id_is_required')
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        if ($request['cart_group_id'] == 'all_cart_group') {
            foreach (CartManager::get_cart_group_ids(request: $request) as $group_id) {
                $request['cart_group_id'] = $group_id;
                self::insert_into_cart_shipping($request);
            }
        } else {
            self::insert_into_cart_shipping($request);
        }

        return response()->json(translate('successfully_added'));
    }

    public static function insert_into_cart_shipping($request)
    {
        $shipping = CartShipping::where(['cart_group_id' => $request['cart_group_id']])->first();
        if (isset($shipping) == false) {
            $shipping = new CartShipping();
        }
        $shipping['cart_group_id'] = $request['cart_group_id'];
        $shipping['shipping_method_id'] = $request['id'];
        $shipping['shipping_cost'] = ShippingMethod::find($request['id'])->cost;
        $shipping->save();
    }

    public function chosen_shipping_methods(Request $request): JsonResponse
    {
        $groupIds = CartManager::get_cart_group_ids(request: $request);
        $cartShipping = CartShipping::whereIn('cart_group_id', $groupIds)->get();

        $cartShipping->map(function ($data) {
            $isCheckedItemExist = Cart::where(['cart_group_id' => $data['cart_group_id'], 'is_checked' => 1])->exists();
            $freeDeliveryStatus = OrderManager::free_delivery_order_amount($data['cart_group_id'])['status'];
            $data['free_delivery_status'] = $freeDeliveryStatus;
            $data['is_check_item_exist'] = $isCheckedItemExist ? 1 : 0;
            return $data;
        });

        return response()->json($cartShipping, 200);
    }

    public function check_shipping_type(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'seller_is' => 'required',
            'seller_id' => 'required'
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        if($request->seller_is == 'admin')
        {
            $admin_shipping = ShippingType::where('seller_id',0)->first();
            $shipping_type = isset($admin_shipping)==true?$admin_shipping->shipping_type:'order_wise';

        }
        else{
            $seller_shipping = ShippingType::where('seller_id',$request->seller_id)->first();
            $shipping_type = isset($seller_shipping)==true? $seller_shipping->shipping_type:'order_wise';

        }
        return response()->json(['shipping_type'=>$shipping_type], 200);
    }
}
