<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Http\Controllers\Controller;
use App\Models\DealOfTheDay;
use App\Models\Product;
use App\Utils\Helpers;
use Illuminate\Http\Request;

class DealOfTheDayController extends Controller
{
    public function get_deal_of_the_day_product(Request $request)
    {
        $deal_of_the_day = DealOfTheDay::where('deal_of_the_days.status', 1)->first();

        if(isset($deal_of_the_day)){

            $product = Product::active()->with(['rating'])
                ->withCount(['reviews' => function ($query) {
                    $query->active()->whereNull('delivery_man_id');
                }])->find($deal_of_the_day->product_id);

            if(!isset($product))
            {
                $product = Product::active()->with(['rating'])
                    ->withCount(['reviews' => function ($query) {
                        $query->active()->whereNull('delivery_man_id');
                    }])->inRandomOrder()->first();
            }
            $product = Helpers::product_data_formatting($product);
            return response()->json($product, 200);
        }else{
            $product = Product::active()->with(['rating'])
                ->withCount(['reviews' => function ($query) {
                    $query->active()->whereNull('delivery_man_id');
                }])->inRandomOrder()->first();
            $product = Helpers::product_data_formatting($product);

            return response()->json($product, 200);
        }

    }
}
