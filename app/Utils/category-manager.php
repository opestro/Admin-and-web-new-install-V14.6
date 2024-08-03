<?php

namespace App\Utils;

use App\Utils\Helpers;
use App\Models\Category;
use App\Models\Product;

class CategoryManager
{
    public static function parents()
    {
        return Category::with(['childes.childes'])->where('position', 0)->priority()->get();
    }

    public static function child($parent_id)
    {
        $x = Category::where(['parent_id' => $parent_id])->get();
        return $x;
    }

    public static function products($category_id, $request=null)
    {
        $user = Helpers::get_customer($request);
        $id = '"'.$category_id.'"';
        $products = Product::with(['flashDealProducts.flashDeal','rating','tags', 'seller.shop'])
            ->withCount(['reviews','wishList' => function($query) use($user){
                $query->where('customer_id', $user != 'offline' ? $user->id : '0');
            }])
            ->active()
            ->where('category_ids', 'like', "%{$id}%");

        $products = ProductManager::getPriorityWiseCategoryWiseProductsQuery(query: $products, dataLimit: 'all');

        $currentDate = date('Y-m-d H:i:s');
        $products?->map(function ($product) use ($currentDate) {
            $flashDealStatus = 0;
            $flashDealEndDate = 0;
            if (count($product->flashDealProducts) > 0) {
                $flashDeal = null;
                foreach($product->flashDealProducts as $flashDealData){
                    if($flashDealData->flashDeal){
                        $flashDeal = $flashDealData->flashDeal;
                    }
                }
                if ($flashDeal) {
                    $startDate = date('Y-m-d H:i:s', strtotime($flashDeal->start_date));
                    $endDate = date('Y-m-d H:i:s', strtotime($flashDeal->end_date));
                    $flashDealStatus = $flashDeal->status == 1 && (($currentDate >= $startDate) && ($currentDate <= $endDate)) ? 1 : 0;
                    $flashDealEndDate = $flashDeal->end_date;
                }
            }
            $product['flash_deal_status'] = $flashDealStatus;
            $product['flash_deal_end_date'] = $flashDealEndDate;
            return $product;
        });

        return $products;
    }

    public static function get_category_name($id){
        $category = Category::find($id);

        if($category){
            return $category->name;
        }
        return '';
    }

    public static function getCategoriesWithCountingAndPriorityWiseSorting($dataLimit = null)
    {
        $categories = Category::with(['product' => function ($query) {
                return $query->active()->withCount(['orderDetails']);
            }])->withCount(['product' => function ($query) {
                $query->active();
            }])->with(['childes' => function ($query) {
            $query->with(['childes' => function ($query) {
                $query->withCount(['subSubCategoryProduct'])->where('position', 2);
            }])->withCount(['subCategoryProduct'])->where('position', 1);
        }, 'childes.childes'])->where('position', 0);

        $categoriesProcessed = self::getPriorityWiseCategorySortQuery(query: $categories->get());
        if ($dataLimit) {
            $categoriesProcessed = $categoriesProcessed->paginate($dataLimit);
        }
        return $categoriesProcessed;
    }

    public static function getPriorityWiseCategorySortQuery($query)
    {
        $categoryProductSortBy = getWebConfig(name: 'category_list_priority');
        if ($categoryProductSortBy && ($categoryProductSortBy['custom_sorting_status'] == 1)) {
            if ($categoryProductSortBy['sort_by'] == 'most_order') {
                return $query->map(function ($category) {
                    $category->order_count = $category?->product?->sum('order_details_count') ?? 0;
                    return $category;
                })->sortByDesc('order_count');
            } elseif ($categoryProductSortBy['sort_by'] == 'latest_created') {
                return $query->sortByDesc('id');
            } elseif ($categoryProductSortBy['sort_by'] == 'first_created') {
                return $query->sortBy('id');
            } elseif ($categoryProductSortBy['sort_by'] == 'a_to_z') {
                return $query->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
            } elseif ($categoryProductSortBy['sort_by'] == 'z_to_a') {
                return $query->sortByDesc('name', SORT_NATURAL | SORT_FLAG_CASE);
            }
        }
        return $query->sortByDesc('priority');
    }
}
