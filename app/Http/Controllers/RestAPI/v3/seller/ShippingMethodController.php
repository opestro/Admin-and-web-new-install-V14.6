<?php

namespace App\Http\Controllers\RestAPI\v3\seller;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Utils\BackEndHelper;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ShippingMethodController extends Controller
{
    public function store(Request $request)
    {
        $seller = $request->seller;
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'duration' => 'required',
            'cost' => 'numeric'
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        DB::table('shipping_methods')->insert([
            'creator_id' => $seller['id'],
            'creator_type' => 'seller',
            'title' => $request['title'],
            'duration' => $request['duration'],
            'cost' => BackEndHelper::currency_to_usd($request['cost']),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => translate('successfully_added')], 200);
    }

    public function list(Request $request)
    {
        $seller = $request->seller;
        $shipping_method = ShippingMethod::where(['creator_type' => 'seller', 'creator_id' => $seller['id']])->get();

        return response()->json($shipping_method, 200);
    }

    public function status_update(Request $request)
    {
        $seller = $request->seller;
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required|in:1,0',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        ShippingMethod::where(['id' => $request['id'], 'creator_id' => $seller['id']])->update([
            'status' => $request['status']
        ]);

        return response()->json(['message' => translate('successfully_status_updated')], 200);
    }

    public function edit(Request $request, $id)
    {
        $seller = $request->seller;
        $method = ShippingMethod::where(['id' => $id, 'creator_id' => $seller['id']])->first();
        if (isset($method)) {
            return response()->json($method, 200);
        }

        return response()->json(['message' => translate('data_not_found')], 200);
    }

    public function update(Request $request, $id)
    {
        $seller = $request->seller;
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'duration' => 'required',
            'cost' => 'numeric'
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        DB::table('shipping_methods')->where(['id' => $id, 'creator_id' => $seller['id']])->update([
            'title' => $request['title'],
            'duration' => $request['duration'],
            'cost' => BackEndHelper::currency_to_usd($request['cost']),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => translate('successfully_updated')], 200);
    }

    public function delete(Request $request)
    {
        $seller = $request->seller;
        ShippingMethod::where(['id' => $request->id, 'creator_id' => $seller['id']])->delete();

        return response()->json(['message' => translate('successfully_deleted')], 200);
    }
}
