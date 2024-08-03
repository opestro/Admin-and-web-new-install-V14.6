<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Http\Controllers\Controller;
use App\Models\ProductCompare;
use App\Utils\Helpers;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function __construct(
        private ProductCompare $product_compare,
    ) {

    }

    public function list(Request $request){
        $compare_lists = $this->product_compare->with(['product.rating', 'product.brand'])
            ->whereHas('product')
            ->where('user_id', $request->user()->id)
            ->get();

        $compare_lists->map(function ($data) {
            $data['product'] = Helpers::product_data_formatting($data['product']);
            return $data;
        });

        return response()->json(['compare_lists'=>$compare_lists], 200);
    }

    public function compare_product_store(Request $request)
    {
        $compare_list = $this->product_compare->where(['user_id'=> $request->user()->id, 'product_id'=> $request->product_id])->first();
        if ($compare_list) {
            $compare_list->delete();
            $product_count = $this->product_compare->where(['product_id' => $request->product_id])->count();
            return response()->json(['total_product_add'=>$product_count, 'message'=>'Product removed from the compare list'],200);
        }else{
            $count_compare_list_exist = $this->product_compare->where('user_id', $request->user()->id)->count();
            $count_compare_list_exist == 3 ? $this->product_compare->where('user_id', $request->user()->id)->orderBY('id')->first()->delete():'';

            $compare_list = new ProductCompare;
            $compare_list->user_id = $request->user()->id;
            $compare_list->product_id = $request->product_id;
            $compare_list->save();

            $product_count = $this->product_compare->where(['product_id' => $request->product_id])->count();
            return response()->json(['total_product_add'=>$product_count, 'message'=>'Successfully added'],200);
        }
    }

    public function compare_product_replace(Request $request){

        $new_compare_list = $this->product_compare->find($request->compare_id);
        if ($new_compare_list) {
            $new_compare_list->product_id = $request->product_id;
            $new_compare_list->save();
        }else{
            $compare_list = $this->product_compare->where(['user_id'=> $request->user()->id, 'product_id'=> $request->product_id])->first();
            if($compare_list){
                return response()->json(['message'=>'Product already eadded'],403);
            }

            $this->product_compare->insert([
                'user_id'=> auth('customer')->id(),
                'product_id'=> $request->product_id
            ]);
        }
        return response()->json(['message'=>'Successfully added'],200);
    }

    public function clear_all(Request $request){
        $this->product_compare->where('user_id', $request->user()->id)->delete();

        return response()->json(['message'=>'Compare list removed'],200);
    }
}
