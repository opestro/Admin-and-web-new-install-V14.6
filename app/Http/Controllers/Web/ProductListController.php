<?php

namespace App\Http\Controllers\Web;

use App\Models\BusinessSetting;
use App\Utils\BrandManager;
use App\Utils\CategoryManager;
use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\Review;
use App\Models\Shop;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Product;
use App\Models\Translation;
use App\Models\Wishlist;
use App\Utils\ProductManager;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductListController extends Controller
{
    public function products(Request $request)
    {
        $themeName = theme_root_path();

        return match ($themeName) {
            'default' => self::default_theme($request),
            'theme_aster' => self::theme_aster($request),
            'theme_fashion' => self::theme_fashion($request),
            'theme_all_purpose' => self::theme_all_purpose($request),
        };
    }

    public function default_theme($request): View|JsonResponse|Redirector|RedirectResponse
    {
        $categories = CategoryManager::getCategoriesWithCountingAndPriorityWiseSorting();
        $activeBrands = BrandManager::getActiveBrandWithCountingAndPriorityWiseSorting();
        $productSortBy = $request->get('sort_by');

        $data = [
            'id' => $request['id'],
            'name' => $request['name'],
            'data_from' => $request['data_from'],
            'sort_by' => $request['sort_by'],
            'page_no' => $request['page'],
            'min_price' => $request['min_price'],
            'max_price' => $request['max_price'],
        ];

        if ($request['data_from'] == 'category') {
            $data['brand_name'] = Category::find((int)$request['id'])->name;
        }
        if ($request['data_from'] == 'brand') {
            $brand_data = Brand::active()->find((int)$request['id']);
            if ($brand_data) {
                $data['brand_name'] = $brand_data->name;
            } else {
                Toastr::warning(translate('not_found'));
                return redirect('/');
            }
        }

        $productListData = Product::active()->with([
            'reviews', 'rating', 'seller.shop',
            'wishList' => function ($query) {
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            },
            'compareList' => function ($query) {
                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
            }
        ])->withCount(['reviews']);

        if ($request['data_from'] == 'discounted') {
            $productListData = $productListData->where('discount', '!=', 0);
        }

        if ($request['data_from'] == 'latest') {
            $productListData = $productListData->orderBy('id', 'desc')->get();
        }

        if ($request['min_price'] != null || $request['max_price'] != null) {
            $productListData = $productListData->whereBetween('unit_price', [Helpers::convert_currency_to_usd($request['min_price']), Helpers::convert_currency_to_usd($request['max_price'])]);
        }

        if ($request->has('name') && !empty($request['name'])) {
            $searchName = str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', preg_replace('/\s\s+/', ' ', $request['name']));
            $productListData = $productListData->orderByRaw("CASE WHEN name LIKE '%{$searchName}%' THEN 1 ELSE 2 END, LOCATE('{$searchName}', name), name");
        }

        if ($request['data_from'] == 'category') {
            $categoryWiseProduct = $productListData->where(['category_id' => $request['id']])
                ->orWhere(['sub_category_id' => $request['id']])
                ->orWhere(['sub_sub_category_id' => $request['id']]);
            $productListData = ProductManager::getPriorityWiseCategoryWiseProductsQuery(query: $categoryWiseProduct, dataLimit: 'all');
        }

        if ($request['data_from'] == 'brand') {
            $productListData = $productListData->where('brand_id', $request['id'])->get();
        }

        if ($request['data_from'] == 'top-rated') {
            $reviews = Review::select('product_id', DB::raw('AVG(rating) as count'))
                ->groupBy('product_id')->get();
            $getReviewProductIds = [];
            foreach ($reviews as $review) {
                $getReviewProductIds[] = $review['product_id'];
            }
            $productListData = ProductManager::getPriorityWiseTopRatedProductsQuery(query: $productListData->whereIn('id', $getReviewProductIds));
        }

        if ($request['data_from'] == 'best-selling') {
            $orderDetails = OrderDetail::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->get();
            $getOrderedProductIds = [];
            foreach ($orderDetails as $detail) {
                $getOrderedProductIds[] = $detail['product_id'];
            }
            $productListData = ProductManager::getPriorityWiseBestSellingProductsQuery(query: $productListData->whereIn('id', $getOrderedProductIds), dataLimit: 'all');
        }

        if ($request['data_from'] == 'most-favorite') {
            $wishListItems = Wishlist::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')
                ->get();
            $getWishListedProductIds = [];
            foreach ($wishListItems as $detail) {
                $getWishListedProductIds[] = $detail['product_id'];
            }
            $productListData = $productListData->whereIn('id', $getWishListedProductIds)->get();
        }

        if ($request['data_from'] == 'featured') {
            $productListData = ProductManager::getPriorityWiseFeaturedProductsQuery(query: $productListData->where(['featured' => 1]));
        }

        if ($request['data_from'] == 'featured_deal') {
            $featuredDealID = FlashDeal::where(['deal_type' => 'feature_deal', 'status' => 1])->whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'))->pluck('id')->first();
            $featuredDealProductIDs = $featuredDealID ? FlashDealProduct::where('flash_deal_id', $featuredDealID)->pluck('product_id')->toArray() : [];
            $productListData = ProductManager::getPriorityWiseFeatureDealQuery(query: $productListData->whereIn('id', $featuredDealProductIDs), dataLimit: 'all');
        }

        if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
            $getProductIds = Product::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhereHas('tags', function ($query) use ($value) {
                            $query->where('tag', 'like', "%{$value}%");
                        });
                }
            })->pluck('id');

            if ($getProductIds->count() == 0) {
                $getProductIds = Translation::where('translationable_type', 'App\Models\Product')
                    ->where('key', 'name')
                    ->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('value', 'like', "%{$value}%");
                        }
                    })
                    ->pluck('translationable_id');
            }
            $productListData = ProductManager::getPriorityWiseSearchedProductQuery(query: $productListData->whereIn('id', $getProductIds), keyword: $request['name'], dataLimit: 'all', type: 'searched');
        }

        if ($productSortBy) {
            if ($productSortBy == 'latest') {
                $productListData = $productListData->sortByDesc('id');
            } elseif ($productSortBy == 'low-high') {
                $productListData = $productListData->sortBy('unit_price');
            } elseif ($productSortBy == 'high-low') {
                $productListData = $productListData->sortByDesc('unit_price');
            } elseif ($productSortBy == 'a-z') {
                $productListData = $productListData->sortBy('name');
            } elseif ($productSortBy == 'z-a') {
                $productListData = $productListData->sortByDesc('name');
            }
        }

        $products = $productListData->paginate(20)->appends($data);

        if ($request->ajax()) {
            return response()->json([
                'total_product' => $products->total(),
                'view' => view('web-views.products._ajax-products', compact('products'))->render()
            ], 200);
        }

        return view(VIEW_FILE_NAMES['products_view_page'], [
            'products' => $products,
            'data' => $data,
            'activeBrands' => $activeBrands,
            'categories' => $categories,
        ]);
    }

    public function theme_aster($request): View|JsonResponse|Redirector|RedirectResponse
    {
        $categories = CategoryManager::getCategoriesWithCountingAndPriorityWiseSorting();
        $activeBrands = BrandManager::getActiveBrandWithCountingAndPriorityWiseSorting();
        $productSortBy = $request->get('sort_by');

        $data = [
            'id' => $request['id'],
            'name' => $request['name'],
            'data_from' => $request['data_from'],
            'sort_by' => $request['sort_by'],
            'page_no' => $request['page'],
            'min_price' => $request['min_price'],
            'max_price' => $request['max_price'],
        ];
        if ($request['data_from'] == 'category') {
            $data['brand_name'] = Category::find((int)$request['id'])->name;
        }
        if ($request['data_from'] == 'brand') {
            $brandData = Brand::active()->find((int)$request['id']);
            if ($brandData) {
                $data['brand_name'] = $brandData->name;
            } else {
                Toastr::warning(translate('not_found'));
                return redirect('/');
            }
        }

        $productListData = Product::active()->with([
            'reviews', 'rating', 'seller.shop',
            'wishList' => function ($query) {
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            },
            'compareList' => function ($query) {
                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
            }
        ])->withCount(['reviews']);

        if ($request['min_price'] != null || $request['max_price'] != null) {
            $productListData = $productListData->whereBetween('unit_price', [Helpers::convert_currency_to_usd($request['min_price']), Helpers::convert_currency_to_usd($request['max_price'])]);
        }

        if ($request['ratings'] != null) {
            $productListData = $productListData->with('rating')->whereHas('rating', function ($query) use ($request) {
                return $query;
            });
        }

        if ($request['data_from'] == 'discounted') {
            $productListData = $productListData->where('discount', '!=', 0)->get();
        }

        if ($request->has('name') && !empty($request['name'])) {
            $searchName = str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', preg_replace('/\s\s+/', ' ', $request['name']));
            $productListData = $productListData->orderByRaw("CASE WHEN name LIKE '%{$searchName}%' THEN 1 ELSE 2 END, LOCATE('{$searchName}', name), name");
        }

        if ($request['data_from'] == 'latest') {
            $productListData = $productListData->orderBy('id', 'desc')->get();
        }

        $getProductIds = [];
        if ($request['data_from'] == 'category') {
            $categoryWiseProduct = $productListData->where(['category_id' => $request['id']])
                ->orWhere(['sub_category_id' => $request['id']])
                ->orWhere(['sub_sub_category_id' => $request['id']]);
            $productListData = ProductManager::getPriorityWiseCategoryWiseProductsQuery(query: $categoryWiseProduct, dataLimit: 'all');
        }

        if ($request->has('search_category_value') && $request['search_category_value'] != 'all') {
            $productListData = $productListData->where(['category_id' => $request['search_category_value']])
                ->orWhere(['sub_category_id' => $request['search_category_value']])
                ->orWhere(['sub_sub_category_id' => $request['search_category_value']]);
        }

        if ($request['data_from'] == 'brand') {
            $productListData = $productListData->where('brand_id', $request['id'])->get();
        }

        if ($request['data_from'] == 'top-rated') {
            $reviews = Review::select('product_id', DB::raw('AVG(rating) as count'))
                ->groupBy('product_id')->get();
            $getReviewProductIds = [];
            foreach ($reviews as $review) {
                $getReviewProductIds[] = $review['product_id'];
            }
            $productListData = ProductManager::getPriorityWiseTopRatedProductsQuery(query: $productListData->whereIn('id', $getReviewProductIds));
        }

        if ($request['data_from'] == 'best-selling') {
            $orderDetails = OrderDetail::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->get();
            $getOrderedProductIds = [];
            foreach ($orderDetails as $detail) {
                $getOrderedProductIds[] = $detail['product_id'];
            }
            $productListData = ProductManager::getPriorityWiseBestSellingProductsQuery(query: $productListData->whereIn('id', $getOrderedProductIds), dataLimit: 'all');
        }

        if ($request['data_from'] == 'most-favorite') {
            $wishListItems = Wishlist::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')
                ->get();
            $getWishListedProductIds = [];
            foreach ($wishListItems as $detail) {
                $getWishListedProductIds[] = $detail['product_id'];
            }
            $productListData = $productListData->whereIn('id', $getWishListedProductIds);
        }

        if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
            $getProductIds = Product::with([
                'seller.shop',
                'wishList' => function ($query) {
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compareList' => function ($query) {
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%")
                            ->orWhereHas('tags', function ($query) use ($value) {
                                $query->where('tag', 'like', "%{$value}%");
                            });
                    }
                })->pluck('id');

            if ($getProductIds->count() == 0) {
                $getProductIds = Translation::where('translationable_type', 'App\Models\Product')
                    ->where('key', 'name')
                    ->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('value', 'like', "%{$value}%");
                        }
                    })
                    ->pluck('translationable_id');
            }
            $productListData = ProductManager::getPriorityWiseSearchedProductQuery(query: $productListData->whereIn('id', $getProductIds), keyword: $request['name'], dataLimit: 'all', type: 'searched');
        }

        if ($request['data_from'] == 'featured') {
            $productListData = ProductManager::getPriorityWiseFeaturedProductsQuery(query: $productListData);
        }

        if ($request['data_from'] == 'featured_deal') {
            $featuredDealID = FlashDeal::where(['deal_type' => 'feature_deal', 'status' => 1])->whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'))->pluck('id')->first();
            $featuredDealProductIDs = $featuredDealID ? FlashDealProduct::where('flash_deal_id', $featuredDealID)->pluck('product_id')->toArray() : [];
            $productListData = ProductManager::getPriorityWiseFeatureDealQuery(query: $productListData->whereIn('id', $featuredDealProductIDs), dataLimit: 'all');
        }

        if ($productSortBy) {
            if ($productSortBy == 'latest') {
                $productListData = $productListData->sortByDesc('id');
            } elseif ($productSortBy == 'low-high') {
                $productListData = $productListData->sortBy('unit_price');
            } elseif ($productSortBy == 'high-low') {
                $productListData = $productListData->sortByDesc('unit_price');
            } elseif ($productSortBy == 'a-z') {
                $productListData = $productListData->sortBy('name');
            } elseif ($productSortBy == 'z-a') {
                $productListData = $productListData->sortByDesc('name');
            }
        }

        $ratings = self::getProductsRatingOneToFiveAsArray(productQuery: $productListData);
        $products = $productListData->paginate(20)->appends($data);

        if ($request['ratings'] != null) {
            $products = $products->map(function ($product) use ($request) {
                $product->rating = $product->rating->pluck('average')[0];
                return $product;
            });
            $products = $products->where('rating', '>=', $request['ratings'])
                ->where('rating', '<', $request['ratings'] + 1)
                ->paginate(20)->appends($data);
        }

        if ($request->ajax()) {
            return response()->json([
                'total_product' => $products->total(),
                'view' => view(VIEW_FILE_NAMES['products__ajax_partials'], ['products' => $products, 'product_ids' => $getProductIds])->render(),
            ], 200);
        }

        return view(VIEW_FILE_NAMES['products_view_page'], [
            'products' => $products,
            'data' => $data,
            'ratings' => $ratings,
            'product_ids' => $getProductIds,
            'activeBrands' => $activeBrands,
            'categories' => $categories,
        ]);
    }

    public function theme_fashion(Request $request): View|JsonResponse|Redirector|RedirectResponse
    {
        $categories = CategoryManager::getCategoriesWithCountingAndPriorityWiseSorting();
        $activeBrands = BrandManager::getActiveBrandWithCountingAndPriorityWiseSorting();
        $productSortBy = $request->get('sort_by');

        if ($request['data_from'] == 'brand') {
            $brand_data = Brand::active()->find((int)$request['id']);
            if (!$brand_data) {
                Toastr::warning(translate('not_found'));
                return redirect('/');
            }
        }

        $tagCategory = [];
        if ($request['data_from'] == 'category') {
            $tagCategory = Category::where('id', $request->id)->select('id', 'name')->get();
        }

        $tagBrand = [];
        if ($request['data_from'] == 'brand') {
            $tagBrand = Brand::where('id', $request->id)->select('id', 'name')->get();
        }

        $productListData = Product::active()->withSum('orderDetails', 'qty', function ($query) {
                $query->where('delivery_status', 'delivered');
            })
            ->with(['category', 'reviews', 'rating',
            'wishList' => function ($query) {
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            },
            'compareList' => function ($query) {
                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
            }])->withCount('reviews');

        if ($request->has('search_category_value') && $request['search_category_value'] != 'all') {
            $productListData = $productListData->where(['category_id' => $request['search_category_value']])
                ->orWhere(['sub_category_id' => $request['search_category_value']])
                ->orWhere(['sub_sub_category_id' => $request['search_category_value']]);
        }

        if ($request['data_from'] == 'brand') {
            $productListData = $productListData->where('brand_id', $request['id'])->get();
        }

        if (!$request->has('data_from') || $request['data_from'] == 'default') {
            $productListData = $productListData->orderBy('order_details_sum_qty', 'DESC');
        }

        if ($request['data_from'] == 'discounted') {
            $productListData = $productListData->where('discount', '!=', 0)->get();
        }

        if ($request['data_from'] == 'latest') {
            $productListData = $productListData->orderBy('id', 'desc')->get();
        }

        if ($request['min_price'] != null || $request['max_price'] != null) {
            $productListData = $productListData->whereBetween('unit_price', [Helpers::convert_currency_to_usd($request['min_price']), Helpers::convert_currency_to_usd($request['max_price'])]);
        }

        if ($request['data_from'] == 'most-favorite') {
            $wishListItems = Wishlist::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')
                ->get();
            $getWishListedProductIds = [];
            foreach ($wishListItems as $detail) {
                $getWishListedProductIds[] = $detail['product_id'];
            }
            $productListData = $productListData->whereIn('id', $getWishListedProductIds);
        }

        if ($request->has('name') && !empty($request['name'])) {
            $searchName = str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', preg_replace('/\s\s+/', ' ', $request['name']));
            $productListData = $productListData->orderByRaw("CASE WHEN name LIKE '%{$searchName}%' THEN 1 ELSE 2 END, LOCATE('{$searchName}', name), name");
        }

        $getProductIds = [];
        if ($request['data_from'] == 'category') {
            $categoryWiseProduct = $productListData->where(['category_id' => $request['id']])
                ->orWhere(['sub_category_id' => $request['id']])
                ->orWhere(['sub_sub_category_id' => $request['id']]);
            $productListData = ProductManager::getPriorityWiseCategoryWiseProductsQuery(query: $categoryWiseProduct, dataLimit: 'all');
        }

        if ($request['data_from'] == 'top-rated') {
            $reviews = Review::select('product_id', DB::raw('AVG(rating) as count'))
                ->groupBy('product_id')->get();
            $getReviewProductIds = [];
            foreach ($reviews as $review) {
                $getReviewProductIds[] = $review['product_id'];
            }
            $productListData = ProductManager::getPriorityWiseTopRatedProductsQuery(query: $productListData->whereIn('id', $getReviewProductIds));
        }

        if ($request['data_from'] == 'best-selling') {
            $orderDetails = OrderDetail::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->get();
            $getOrderedProductIds = [];
            foreach ($orderDetails as $detail) {
                $getOrderedProductIds[] = $detail['product_id'];
            }
            $productListData = ProductManager::getPriorityWiseBestSellingProductsQuery(query: $productListData->whereIn('id', $getOrderedProductIds), dataLimit: 'all');
        }

        if ($request['data_from'] == 'featured') {
            $productListData = ProductManager::getPriorityWiseFeaturedProductsQuery(query: $productListData->where(['featured' => 1]));
        }

        if ($request->has('shop_id') && $request['shop_id'] == 0) {
            $query = Product::active()
                ->with(['reviews'])
                ->withCount('reviews')
                ->where(['added_by' => 'admin', 'featured' => 1]);
        } elseif ($request->has('shop_id') && $request['shop_id'] != 0) {
            $query = Product::active()
                ->withCount('reviews')
                ->where(['added_by' => 'seller', 'featured' => 1])
                ->with(['reviews', 'seller.shop' => function ($query) use ($request) {
                    $query->where('id', $request->shop_id);
                }])
                ->whereHas('seller.shop', function ($query) use ($request) {
                    $query->where('id', $request->shop_id)->whereNotNull('id');
                });
        }

        if ($request['data_from'] == 'featured_deal') {
            $featuredDealID = FlashDeal::where(['deal_type' => 'feature_deal', 'status' => 1])->whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'))->pluck('id')->first();
            $featuredDealProductIDs = $featuredDealID ? FlashDealProduct::where('flash_deal_id', $featuredDealID)->pluck('product_id')->toArray() : [];
            $productListData = ProductManager::getPriorityWiseFeatureDealQuery(query: $productListData->whereIn('id', $featuredDealProductIDs), dataLimit: 'all');
        }

        if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
            $getProductIds = Product::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhereHas('tags', function ($query) use ($value) {
                            $query->where('tag', 'like', "%{$value}%");
                        });
                }
            })->pluck('id');

            $sellers = Shop::where(function ($q) use ($request) {
                $q->orWhere('name', 'like', "%{$request['name']}%");
            })->whereHas('seller', function ($query) {
                return $query->where(['status' => 'approved']);
            })->with('products', function ($query) {
                return $query->active()->where('added_by', 'seller');
            })->get();

            $seller_products = [];
            foreach ($sellers as $seller) {
                if (isset($seller->product) && $seller->product->count() > 0) {
                    $ids = $seller->product->pluck('id');
                    array_push($seller_products, ...$ids);
                }
            }

            $inhouse_product = [];
            $company_name = Helpers::get_business_settings('company_name');

            if (strpos($request['name'], $company_name) !== false) {
                $inhouse_product = Product::active()->withCount('reviews')->Where('added_by', 'admin')->pluck('id');
            }

            $getProductIds = $getProductIds->merge($seller_products)->merge($inhouse_product);

            if ($getProductIds->count() == 0) {
                $getProductIds = Translation::where('translationable_type', 'App\Models\Product')
                    ->where('key', 'name')
                    ->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('value', 'like', "%{$value}%");
                        }
                    })
                    ->pluck('translationable_id');
            }
            $productListData = ProductManager::getPriorityWiseSearchedProductQuery(query: $productListData->whereIn('id', $getProductIds), keyword: $request['name'], dataLimit: 'all', type: 'searched');
        }

        if ($productSortBy) {
            if ($productSortBy == 'latest') {
                $productListData = $productListData->sortByDesc('id');
            } elseif ($productSortBy == 'low-high') {
                $productListData = $productListData->sortBy('unit_price');
            } elseif ($productSortBy == 'high-low') {
                $productListData = $productListData->sortByDesc('unit_price');
            } elseif ($productSortBy == 'a-z') {
                $productListData = $productListData->sortBy('name');
            } elseif ($productSortBy == 'z-a') {
                $productListData = $productListData->sortByDesc('name');
            }
        }

        if ($request['ratings'] != null) {
            $products = $products->map(function ($product) use ($request) {
                $product->rating = $product->rating->pluck('average')[0];
                return $product;
            });
            $products = $products->where('rating', '>=', $request['ratings'])
                ->where('rating', '<', $request['ratings'] + 1);
        }

        $products = $productListData->paginate(20);

        // Colors Start
        $colors_in_shop_merge = [];
        $colors_collection = Product::active()
            ->withCount('reviews')
            ->where('colors', '!=', '[]')
            ->pluck('colors')
            ->unique()
            ->toArray();

        foreach ($colors_collection as $color_json) {
            $color_array = json_decode($color_json, true);
            if ($color_array) {
                $colors_in_shop_merge = array_merge($colors_in_shop_merge, $color_array);
            }
        }
        $colors_in_shop = array_unique($colors_in_shop_merge);
        // Colors End

        $banner = BusinessSetting::where('type', 'banner_product_list_page')->whereJsonContains('value', ['status' => '1'])->first();

        if ($request->ajax()) {
            return response()->json([
                'total_product' => $products->total(),
                'view' => view(VIEW_FILE_NAMES['products__ajax_partials'], [
                    'products' => $products,
                    'product_ids' => $getProductIds
                ])->render(),
            ], 200);
        }

        return view(VIEW_FILE_NAMES['products_view_page'], [
            'products' => $products,
            'tag_category' => $tagCategory,
            'tag_brand' => $tagBrand,
            'activeBrands' => $activeBrands,
            'categories' => $categories,
            'colors_in_shop' => $colors_in_shop,
            'banner' => $banner,
            'product_ids' => $getProductIds
        ]);
    }

    public function theme_all_purpose(Request $request)
    {
        $categories = CategoryManager::getCategoriesWithCountingAndPriorityWiseSorting();

        $request['sort_by'] == null ? $request['sort_by'] == 'latest' : $request['sort_by'];

        $productListData = Product::active()->with(['reviews', 'rating'])->withCount('reviews');

        $product_ids = [];
        if ($request['data_from'] == 'category') {
            $products = $productListData->get();
            $categoryProductIds = [];
            foreach ($products as $product) {
                foreach (json_decode($product['category_ids'], true) as $category) {
                    if ($category['id'] == $request['id']) {
                        $categoryProductIds[] = $product['id'];
                    }
                }
            }
            $productListData = $productListData->whereIn('id', $categoryProductIds);
        }

        if ($request['data_from'] == 'discounted') {
            $productListData = $productListData->where('discount', '!=', 0)->get();
        }

        if ($request->has('search_category_value') && $request['search_category_value'] != 'all') {
            $products = $productListData->get();
            $categoryProductIds = [];
            foreach ($products as $product) {
                foreach (json_decode($product['category_ids'], true) as $category) {
                    if ($category['id'] == $request['search_category_value']) {
                        $categoryProductIds[] = $product['id'];
                    }
                }
            }
            $productListData = $productListData->whereIn('id', $categoryProductIds);
        }

        if ($request['data_from'] == 'brand') {
            $productListData = $productListData->where('brand_id', $request['id']);
        }

        if ($request['data_from'] == 'top-rated') {
            $reviews = Review::select('product_id', DB::raw('AVG(rating) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')->get();
            $reviewProductIds = [];
            foreach ($reviews as $review) {
                $reviewProductIds[] = $review['product_id'];
            }
            $productListData = ProductManager::getPriorityWiseTopRatedProductsQuery(query: $productListData->whereIn('id', $reviewProductIds));
        }

        if ($request['data_from'] == 'best-selling') {
            $details = OrderDetail::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')
                ->get();
            $bestSellingProductIds = [];
            foreach ($details as $detail) {
                $bestSellingProductIds[] = $detail['product_id'];
            }
            $productListData = ProductManager::getPriorityWiseBestSellingProductsQuery(query: $productListData->whereIn('id', $bestSellingProductIds), dataLimit: 'all');
        }

        if ($request['data_from'] == 'most-favorite') {
            $details = Wishlist::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')
                ->get();
            $wishListProductIds = [];
            foreach ($details as $detail) {
                $wishListProductIds[] = $detail['product_id'];
            }
            $productListData = $productListData->whereIn('id', $wishListProductIds);
        }

        if ($request['data_from'] == 'featured') {
            $productListData = Product::with(['reviews'])->active()->withCount('reviews')->where('featured', 1);
            $productListData = ProductManager::getPriorityWiseFeaturedProductsQuery(query: $productListData);
        }

        if ($request['data_from'] == 'featured_deal') {
            $featuredDealID = FlashDeal::where(['deal_type' => 'feature_deal', 'status' => 1])->whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'))->pluck('id')->first();
            $featuredDealProductIDs = $featuredDealID ? FlashDealProduct::where('flash_deal_id', $featuredDealID)->pluck('product_id')->toArray() : [];
            $productListData = ProductManager::getPriorityWiseFeatureDealQuery(query: $productListData->whereIn('id', $featuredDealProductIDs), dataLimit: 'all');
        }

        if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
            $product_ids = Product::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhereHas('tags', function ($query) use ($value) {
                            $query->where('tag', 'like', "%{$value}%");
                        });
                }
            })->pluck('id');

            if ($product_ids->count() == 0) {
                $product_ids = Translation::where('translationable_type', 'App\Models\Product')
                    ->where('key', 'name')
                    ->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('value', 'like', "%{$value}%");
                        }
                    })
                    ->pluck('translationable_id');
            }

            $productListData = ProductManager::getPriorityWiseSearchedProductQuery(query: $productListData->whereIn('id', $product_ids), keyword: $request['name'], dataLimit: 'all', type: 'searched');
        }

        if ($request['sort_by'] == 'latest') {
            $fetched = $productListData->latest();
        } elseif ($request['sort_by'] == 'low-high') {
            $fetched = $productListData->orderBy('unit_price', 'ASC');
        } elseif ($request['sort_by'] == 'high-low') {
            $fetched = $productListData->orderBy('unit_price', 'DESC');
        } elseif ($request['sort_by'] == 'a-z') {
            $fetched = $productListData->orderBy('name', 'ASC');
        } elseif ($request['sort_by'] == 'z-a') {
            $fetched = $productListData->orderBy('name', 'DESC');
        } else {
            $fetched = $productListData->latest();
        }

        if ($request['min_price'] != null || $request['max_price'] != null) {
            $fetched = $fetched->whereBetween('unit_price', [Helpers::convert_currency_to_usd($request['min_price']), Helpers::convert_currency_to_usd($request['max_price'])]);
        }
        $common_query = $fetched;


        $data = [
            'id' => $request['id'],
            'name' => $request['name'],
            'data_from' => $request['data_from'],
        ];
        $products_count = $common_query->count();
        $products = $common_query->paginate(4)->appends($data);

        $ratings = self::getProductsRatingOneToFiveAsArray($common_query);

        // Colors Start
        $colors_in_shop_merge = [];
        $colors_collection = Product::active()
            ->withCount('reviews')
            ->where('colors', '!=', '[]')
            ->pluck('colors')
            ->unique()
            ->toArray();

        foreach ($colors_collection as $color_json) {
            $color_array = json_decode($color_json, true);
            if ($color_array) {
                $colors_in_shop_merge = array_merge($colors_in_shop_merge, $color_array);
            }
        }
        $colors_in_shop = array_unique($colors_in_shop_merge);
        // Colors End
        $banner = \App\Models\BusinessSetting::where('type', 'banner_product_list_page')->whereJsonContains('value', ['status' => '1'])->first();

        if ($request->ajax()) {
            return response()->json([
                'total_product' => $products->total(),
                'view' => view(VIEW_FILE_NAMES['products__ajax_partials'], compact('products', 'product_ids'))->render(),
            ], 200);
        }

        if ($request['data_from'] == 'brand') {
            $brand_data = Brand::active()->find((int)$request['id']);
            if (!$brand_data) {
                Toastr::warning(translate('not_found'));
                return redirect('/');
            }
        }
        return view(VIEW_FILE_NAMES['products_view_page'], compact('products', 'product_ids', 'products_count', 'categories', 'colors_in_shop', 'banner', 'ratings'));
    }

    function getProductsRatingOneToFiveAsArray($productQuery): array
    {
        $rating_1 = 0;
        $rating_2 = 0;
        $rating_3 = 0;
        $rating_4 = 0;
        $rating_5 = 0;

        foreach ($productQuery as $rating) {
            if (isset($rating->rating[0]['average']) && ($rating->rating[0]['average'] > 0 && $rating->rating[0]['average'] < 2)) {
                $rating_1 += 1;
            } elseif (isset($rating->rating[0]['average']) && ($rating->rating[0]['average'] >= 2 && $rating->rating[0]['average'] < 3)) {
                $rating_2 += 1;
            } elseif (isset($rating->rating[0]['average']) && ($rating->rating[0]['average'] >= 3 && $rating->rating[0]['average'] < 4)) {
                $rating_3 += 1;
            } elseif (isset($rating->rating[0]['average']) && ($rating->rating[0]['average'] >= 4 && $rating->rating[0]['average'] < 5)) {
                $rating_4 += 1;
            } elseif (isset($rating->rating[0]['average']) && ($rating->rating[0]['average'] == 5)) {
                $rating_5 += 1;
            }
        }

        return [
            'rating_1' => $rating_1,
            'rating_2' => $rating_2,
            'rating_3' => $rating_3,
            'rating_4' => $rating_4,
            'rating_5' => $rating_5,
        ];
    }
}
