<?php

namespace App\Http\Controllers\RestAPI\v2\seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryShippingCost;
use App\Models\ShippingType;
use App\Utils\Convert;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class shippingController extends Controller
{
    public function get_shipping_type(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }
        $seller_id = $seller['id'];
        $shippingMethod = Helpers::get_business_settings('shipping_method');

        $seller_shipping = ShippingType::where('seller_id',$seller_id)->first();

        $shippingType = isset($seller_shipping)==true ? $seller_shipping->shipping_type : 'order_wise';

        return response()->json([
            'type'=>$shippingType
        ]);

    }
    public function selected_shipping_type(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }
        $seller_id = $seller['id'];

        $seller_shipping = ShippingType::where('seller_id',$seller_id)->first();

        if(isset($seller_shipping)){

            $seller_shipping->shipping_type = $request->shipping_type;
            $seller_shipping->save();
        }else{
            $new_shipping_type = new ShippingType;
            $new_shipping_type->seller_id = $seller_id;
            $new_shipping_type->shipping_type = $request->shipping_type;
            $new_shipping_type->save();

        }

        return response()->json([
            'message'=>translate('successfully updated')
        ]);
    }

    public function all_category_cost(Request $request){

        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }
        $seller_id = $seller['id'];

        $all_category_ids = Category::where(['position' => 0])->pluck('id')->toArray();
        $category_shipping_cost_ids = CategoryShippingCost::where('seller_id',$seller_id)->pluck('category_id')->toArray();
        if(isset($all_category_ids)){
            foreach($all_category_ids as $id)
            {
                if(!in_array($id,$category_shipping_cost_ids))
                {
                    $new_category_shipping_cost = new CategoryShippingCost;
                    $new_category_shipping_cost->seller_id = $seller_id;
                    $new_category_shipping_cost->category_id = $id;
                    $new_category_shipping_cost->cost = 0;
                    $new_category_shipping_cost->save();
                }
            }
        }
        $all_category_shipping_cost = CategoryShippingCost::with('category')->where('seller_id',$seller_id)->get();

        return response()->json([
            'all_category_shipping_cost'=>$all_category_shipping_cost
        ]);
    }

    public function set_category_cost(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }
        $seller_id = $seller['id'];

        $validator = Validator::make($request->all(), [
            'ids' => 'required',
            'cost' => 'required',
            'multiply_qty'=>'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }
        if(isset($request->ids))
        {
            foreach($request->ids as $key=>$id){

                $category_shipping_cost = CategoryShippingCost::find($id);
                $category_shipping_cost->cost = Convert::usd($request->cost[$key]);
                $category_shipping_cost->multiply_qty = $request->multiply_qty[$key];
                $category_shipping_cost->save();
            }
        }

        return response()->json([
            'success'=>translate('successfully_updated')
        ]);

    }
}
