<?php

namespace App\Http\Controllers\RestAPI\v2\seller;

use App\Http\Controllers\Controller;
use App\Models\Brand;

class BrandController extends Controller
{
    public function getBrands()
    {
        try {
            $brands = Brand::all();
        } catch (\Exception $e) {
        }

        return response()->json($brands,200);
    }
}
