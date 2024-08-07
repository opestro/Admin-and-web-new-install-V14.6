<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use App\Utils\Helpers;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function get_banners(Request $request)
    {
        $theme_name = theme_root_path();

        $banner_array = match ($theme_name) {
            'default' => array(
                'Main Banner',
                'Footer Banner',
                'Popup Banner',
                'Main Section Banner'
            ),
            'theme_aster' => array(
                'Main Banner',
                'Footer Banner',
                'Popup Banner',
                'Header Banner',
                'Sidebar Banner',
                'Top Side Banner',
                'Main Section Banner'
            ),
            'theme_fashion' => array(
                'Main Banner',
                'Footer Banner',
                'Popup Banner',
                'Main Section Banner',
                'Promo Banner Left',
                'Promo Banner Middle Top',
                'Promo Banner Middle Bottom',
                'Promo Banner Right',
                'Promo Banner Bottom'
            ),
        };

        $banners = Banner::whereIn('banner_type',$banner_array)->where(['published' => 1, 'theme'=>$theme_name])->get();
        $pro_ids = [];
        $data = [];
        foreach ($banners as $banner) {
            if ($banner['resource_type'] == 'product' && !in_array($banner['resource_id'], $pro_ids)) {
                array_push($pro_ids,$banner['resource_id']);
                $product = Product::find($banner['resource_id']);
                $banner['product'] = Helpers::product_data_formatting($product);
            }
            $data[] = $banner;
        }

        return response()->json($data, 200);

    }
}
