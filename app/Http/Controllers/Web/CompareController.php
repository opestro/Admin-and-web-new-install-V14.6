<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\ProductCompare;
use Illuminate\Http\Request;


class CompareController extends Controller
{
    public function __construct(
        private ProductCompare $product_compare,
    ) {

    }
    public function index(){
        $attributes = [];
        $compare_lists = $this->product_compare->with('product')->whereHas('product')->where('user_id', auth('customer')->id())->get();
        if(theme_root_path()=='theme_fashion' || theme_root_path() == 'theme_all_purpose'){
            $attributes = Attribute::all();
        }
        return view(VIEW_FILE_NAMES['account_compare_list'], compact('compare_lists','attributes'));
    }

    public function store_compare_list(Request $request)
    {
        if ($request->ajax()) {
            if (auth('customer')->check()) {
                $compare_list = $this->product_compare->where(['user_id' => auth('customer')->id(), 'product_id' => $request->product_id])->first();
                if ($compare_list) {
                    $compare_list->delete();
                    $count_compare_list = $this->product_compare->whereHas('product', function ($q) {
                        return $q;
                    })->where('user_id', auth('customer')->id())->count();

                    $compare_product_ids = $this->product_compare->where('user_id', auth('customer')->id())->pluck('product_id')->toArray();
                    $product_count = count($compare_product_ids);
                    session()->put('compare_list', $this->product_compare->where('user_id', auth('customer')->id())->pluck('product_id')->toArray());

                    return response()->json([
                        'error' => translate("compare_list_Removed"),
                        'value' => 2,
                        'count' => $count_compare_list,
                        'product_count' => $product_count,
                        'compare_product_ids' => $compare_product_ids
                    ]);


                } else {
                    $count_compare_list_exist = $this->product_compare->where('user_id', auth('customer')->id())->count();

                    if ($count_compare_list_exist == 3) {
                        $this->product_compare->where('user_id', auth('customer')->id())->orderBY('id')->first()->delete();
                    }

                    $compare_list = new ProductCompare;
                    $compare_list->user_id = auth('customer')->id();
                    $compare_list->product_id = $request->product_id;
                    $compare_list->save();

                    $count_compare_list = $this->product_compare->whereHas('product', function ($q) {
                        return $q;
                    })->where('user_id', auth('customer')->id())->count();

                    $compare_product_ids = $this->product_compare->where('user_id', auth('customer')->id())->pluck('product_id')->toArray();
                    $product_count = count($compare_product_ids);
                    session()->put('compare_list', $this->product_compare->where('user_id', auth('customer')->id())->pluck('product_id')->toArray());

                    return response()->json([
                        'success' => translate("Product_has_been_added_to_Compare_list"),
                        'value' => 1,
                        'count' => $count_compare_list,
                        'id' => $request->product_id,
                        'product_count' => $product_count,
                        'compare_product_ids' => $compare_product_ids
                    ]);
                }
            }else {
                return response()->json(['error' => translate('login_first'), 'value' => 0]);
            }
        }else{
            $compare_list = $this->product_compare->where(['user_id'=> auth('customer')->id(), 'product_id'=> $request->product_id])->first();
            if($compare_list){
                return redirect()->back();
            }
            else{
                $new_compare_list = $this->product_compare->find($request->compare_id);
                if ($new_compare_list) {
                    $new_compare_list->product_id = $request->product_id;
                    $new_compare_list->save();
                }else{
                    $this->product_compare->insert([
                        'user_id'=> auth('customer')->id(),
                        'product_id'=> $request->product_id
                    ]);
                }
                return redirect()->back();
            }
        }
    }

    //for fashion theme
    public function delete_compare_list(Request $request){
        $this->product_compare->where(['id'=>$request->id])->delete();
        session()->put('compare_list', $this->product_compare->where('user_id', auth('customer')->user()->id)->pluck('product_id')->toArray());
        return redirect()->back();
    }

    public function delete_compare_list_all(){
        $this->product_compare->where('user_id', auth('customer')->id())->delete();
        session()->put('compare_list', $this->product_compare->where('user_id', auth('customer')->user()->id)->pluck('product_id')->toArray());
        return redirect()->back();
    }
}
