<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Http\Controllers\Controller;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Product;
use App\Utils\Helpers;
use App\Utils\ProductManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FlashDealController extends Controller
{
    public function getFlashDeal(): JsonResponse
    {
        $flashDeal = ProductManager::getPriorityWiseFlashDealsProductsQuery()['flashDeal'];
        return response()->json($flashDeal, 200);
    }

    public function getFlashDealProducts(Request $request, $deal_id): JsonResponse
    {
        $user = Helpers::get_customer($request);
        $userId = $user != 'offline' ? $user->id : '0';
        $products = ProductManager::getPriorityWiseFlashDealsProductsQuery(id: $deal_id, userId: $userId)['flashDealProducts'];
        return response()->json(Helpers::product_data_formatting($products, true), 200);
    }
}
