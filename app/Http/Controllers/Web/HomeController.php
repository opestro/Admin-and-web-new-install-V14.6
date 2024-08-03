<?php

namespace App\Http\Controllers\Web;

use App\Models\Shop;
use App\Traits\EmailTemplateTrait;
use App\Traits\InHouseTrait;
use App\Utils\BrandManager;
use App\Utils\CategoryManager;
use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\DealOfTheDay;
use App\Models\MostDemanded;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Review;
use App\Utils\ProductManager;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use InHouseTrait,EmailTemplateTrait;

    public function __construct(
        private Product      $product,
        private Order        $order,
        private OrderDetail  $order_details,
        private Category     $category,
        private Seller       $seller,
        private Review       $review,
        private DealOfTheDay $deal_of_the_day,
        private Banner       $banner,
        private MostDemanded $most_demanded,
    )
    {
    }


    public function index()
    {
        $themeName = theme_root_path();
        return match ($themeName) {
            'default' => self::default_theme(),
            'theme_aster' => self::theme_aster(),
            'theme_fashion' => self::theme_fashion(),
            'theme_all_purpose' => self::theme_all_purpose(),
        };
    }

    public function default_theme(): View
    {
        $categories = CategoryManager::getCategoriesWithCountingAndPriorityWiseSorting();
        $userId = Auth::guard('customer')->user() ? Auth::guard('customer')->id() : 0;
        $flashDeal = ProductManager::getPriorityWiseFlashDealsProductsQuery(userId: $userId);

        $theme_name = theme_root_path();
        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;
        $home_categories = Category::where('home_status', true)->priority()->get();
        $home_categories->map(function ($data) {
            $id = '"' . $data['id'] . '"';
            $homeCategoriesProducts = Product::active()
                ->withCount('reviews')
                ->where('category_ids', 'like', "%{$id}%");
            $data['products'] = ProductManager::getPriorityWiseCategoryWiseProductsQuery(query: $homeCategoriesProducts, dataLimit: 12);
        });
        $current_date = date('Y-m-d H:i:s');

        $topVendorsList = Shop::active()
            ->withCount(['products' => function ($query) {
                $query->active();
            }])
            ->with('seller', function ($query) {
                $query->with('product', function ($query) {
                    $query->active()->with('reviews', function ($query) {
                        $query->active();
                    });
                })
                    ->withCount(['orders']);
            })
            ->get()
            ->each(function ($shop) {
                $shop->orders_count = $shop->seller->orders_count;
                $productReviews = $shop->seller->product->pluck('reviews')->collapse();
                $shop->average_rating = $productReviews->avg('rating');
                $shop->review_count = $productReviews->count();
                $shop->total_rating = $productReviews->sum('rating');

                $positiveReviewsCount = $productReviews->where('rating', '>=', 4)->count();
                $shop->positive_review = ($shop->review_count !== 0) ? ($positiveReviewsCount * 100) / $shop->review_count : 0;

                $currentDate = date('Y-m-d');
                $startDate = date('Y-m-d', strtotime($shop['vacation_start_date']));
                $endDate = date('Y-m-d', strtotime($shop['vacation_end_date']));
                $shop->is_vacation_mode_now = $shop['vacation_status'] && ($currentDate >= $shop['vacation_start_date']) && ($currentDate <= $shop['vacation_end_date']) ? 1 : 0;
            })->take(12);


        $inhouseProducts = Product::active()->with(['reviews', 'rating'])->withCount('reviews')->where(['added_by' => 'admin'])->get();
        $inhouseProductCount = $inhouseProducts->count();

        $inhouseReviewData = Review::active()->whereIn('product_id', $inhouseProducts->pluck('id'));
        $inhouseReviewDataCount = $inhouseReviewData->count();
        $inhouseRattingStatusPositive = 0;
        foreach ($inhouseReviewData->pluck('rating') as $singleRating) {
            ($singleRating >= 4 ? ($inhouseRattingStatusPositive++) : '');
        }

        $inhouseShop = $this->getInHouseShopObject();
        $inhouseShop->id = 0;
        $inhouseShop->products_count = $inhouseProductCount;
        $inhouseShop->total_rating = $inhouseReviewDataCount;
        $inhouseShop->review_count = $inhouseReviewDataCount;
        $inhouseShop->average_rating = $inhouseReviewData->avg('rating');
        $inhouseShop->positive_review = $inhouseReviewDataCount != 0 ? ($inhouseRattingStatusPositive * 100) / $inhouseReviewDataCount : 0;
        $inhouseShop->orders_count = Order::where(['seller_is' => 'admin'])->count();
        $topVendorsList = $topVendorsList->prepend($inhouseShop);

        $topVendorsList = ProductManager::getPriorityWiseTopVendorQuery($topVendorsList);

        $featuredProductsList = ProductManager::getPriorityWiseFeaturedProductsQuery(query: $this->product->active(), dataLimit: 12);

        $latest_products = $this->product->with(['reviews'])->active()->orderBy('id', 'desc')->take(8)->get();
        $newArrivalProducts = ProductManager::getPriorityWiseNewArrivalProductsQuery(query: $this->product->active(), dataLimit: 8);

        $brands = Brand::active()->take(15)->get();

        $bestSellProduct = $this->order_details->with('product.reviews')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(6)
            ->get();

        $topRated = Review::with('product')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('AVG(rating) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(6)
            ->get();

        if ($bestSellProduct->count() == 0) {
            $bestSellProduct = $latest_products;
        }

        if ($topRated->count() == 0) {
            $topRated = $bestSellProduct;
        }

        $deal_of_the_day = DealOfTheDay::join('products', 'products.id', '=', 'deal_of_the_days.product_id')->select('deal_of_the_days.*', 'products.unit_price')->where('products.status', 1)->where('deal_of_the_days.status', 1)->first();
        $main_banner = $this->banner->where(['banner_type' => 'Main Banner', 'theme' => $theme_name, 'published' => 1])->latest()->get();
        $main_section_banner = $this->banner->where(['banner_type' => 'Main Section Banner', 'theme' => $theme_name, 'published' => 1])->orderBy('id', 'desc')->latest()->first();

        $recommendedProduct = $this->product->active()->inRandomOrder()->first();
        $footer_banner = $this->banner->where('banner_type', 'Footer Banner')->where('theme', theme_root_path())->where('published', 1)->orderBy('id', 'desc')->get();

        return view(VIEW_FILE_NAMES['home'],
            compact(
                'flashDeal', 'featuredProductsList', 'topRated', 'bestSellProduct', 'latest_products', 'categories', 'brands',
                'deal_of_the_day', 'topVendorsList', 'home_categories', 'brand_setting', 'main_banner', 'main_section_banner',
                'current_date', 'recommendedProduct', 'footer_banner', 'newArrivalProducts'
            )
        );
    }

    public function theme_aster(): View
    {
        $categories = CategoryManager::getCategoriesWithCountingAndPriorityWiseSorting(dataLimit: 11);
        $userId = Auth::guard('customer')->user() ? Auth::guard('customer')->id() : 0;
        $flashDeal = ProductManager::getPriorityWiseFlashDealsProductsQuery(userId: $userId);

        $theme_name = theme_root_path();
        $current_date = date('Y-m-d H:i:s');

        $home_categories = $this->category
            ->where('home_status', true)
            ->priority()->get();

        $home_categories->map(function ($data) {
            $current_date = date('Y-m-d H:i:s');
            $homeCategoriesProducts = Product::active()
                ->with([
                    'flashDealProducts' => function ($query) {
                        return $query->with(['flashDeal']);
                    },
                    'wishList' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compareList' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    }
                ])
                ->withCount('reviews')
                ->where('category_id', $data['id']);

            $data['products'] = ProductManager::getPriorityWiseCategoryWiseProductsQuery(query: $homeCategoriesProducts, dataLimit: 12);

            $data['products']?->map(function ($product) use ($current_date) {
                $flash_deal_status = 0;
                if (count($product->flashDealProducts) > 0) {
                    $flash_deal = $product->flashDealProducts[0]->flashDeal;
                    if ($flash_deal) {
                        $start_date = date('Y-m-d H:i:s', strtotime($flash_deal->start_date));
                        $end_date = date('Y-m-d H:i:s', strtotime($flash_deal->end_date));
                        $flash_deal_status = $flash_deal->status == 1 && (($current_date >= $start_date) && ($current_date <= $end_date)) ? 1 : 0;
                    }
                }
                $product['flash_deal_status'] = $flash_deal_status;
                return $product;
            });
        });

        // Start Order Based on Top Seller
        $topVendorsList = Shop::active()
            ->withCount(['products' => function ($query) {
                $query->active();
            }])
            ->with(['products' => function ($query) {
                $query->active();
            }])
            ->with('seller', function ($query) {
                $query->with('product', function ($query) {
                    $query->active()->with('reviews', function ($query) {
                        $query->active();
                    });
                })->with('coupon')->withCount(['orders']);
            })
            ->get()
            ->each(function ($shop) {
                $shop->products = Arr::random($shop->products->toArray(), count($shop->products) < 3 ? count($shop->products) : 3);
                $shop->orders_count = $shop->seller->orders_count;
                $shop->coupon_list = $shop?->seller?->coupon ?? null;

                $productReviews = $shop->seller->product->pluck('reviews')->collapse();
                $shop->average_rating = $productReviews->avg('rating');
                $shop->review_count = $productReviews->count();
                $shop->total_rating = $productReviews->sum('rating');

                $positiveReviewsCount = $productReviews->where('rating', '>=', 4)->count();
                $shop->positive_review = ($shop->review_count !== 0) ? ($positiveReviewsCount * 100) / $shop->review_count : 0;

                $currentDate = date('Y-m-d');
                $startDate = date('Y-m-d', strtotime($shop['vacation_start_date']));
                $endDate = date('Y-m-d', strtotime($shop['vacation_end_date']));
                $shop->is_vacation_mode_now = $shop['vacation_status'] && ($currentDate >= $shop['vacation_start_date']) && ($currentDate <= $shop['vacation_end_date']) ? 1 : 0;
            })->take(12);

        $inhouseCoupon = Coupon::where(['added_by' => 'admin', 'coupon_bearer' => 'inhouse', 'status' => 1])
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'))->get();

        $inhouseProducts = Product::active()->with(['reviews', 'rating'])->withCount('reviews')->where(['added_by' => 'admin'])->get();
        $inhouseProductCount = $inhouseProducts->count();

        $inhouseReviewData = Review::active()->whereIn('product_id', $inhouseProducts->pluck('id'));
        $inhouseReviewDataCount = $inhouseReviewData->count();
        $inhouseRattingStatusPositive = 0;
        foreach ($inhouseReviewData->pluck('rating') as $singleRating) {
            ($singleRating >= 4 ? ($inhouseRattingStatusPositive++) : '');
        }

        $inhouseShop = $this->getInHouseShopObject();
        $inhouseShop->id = 0;
        $inhouseShop->products_count = $inhouseProductCount;
        $inhouseShop->coupon_list = $inhouseCoupon;
        $inhouseShop->total_rating = $inhouseReviewDataCount;
        $inhouseShop->review_count = $inhouseReviewDataCount;
        $inhouseShop->average_rating = $inhouseReviewData->avg('rating');
        $inhouseShop->positive_review = $inhouseReviewDataCount != 0 ? ($inhouseRattingStatusPositive * 100) / $inhouseReviewDataCount : 0;
        $inhouseShop->orders_count = Order::where(['seller_is' => 'admin'])->count();
        $inhouseShop->products = Arr::random($inhouseProducts->toArray(), $inhouseProductCount < 3 ? $inhouseProductCount : 3);;
        $topVendorsList = $topVendorsList->prepend($inhouseShop);
        $topVendorsList = ProductManager::getPriorityWiseTopVendorQuery($topVendorsList);
        // End Order Based on Top Seller

        // Find what you need
        $findWhatYouNeedCategoriesData = $this->category->where('parent_id', 0)
            ->with(['childes' => function ($query) {
                $query->with(['subCategoryProduct' => function ($query) {
                    return $query->active();
                }]);
            }])
            ->with(['product' => function ($query) {
                return $query->active();
            }])
            ->get();

        $findWhatYouNeedCategoriesData = CategoryManager::getPriorityWiseCategorySortQuery(query: $findWhatYouNeedCategoriesData);

        $findWhatYouNeedCategoriesData->map(function ($category) {
            $category->product_count = $category->product->count();
            unset($category->product);
            $category->childes?->map(function ($sub_category) {
                $sub_category->subCategoryProduct_count = $sub_category->subCategoryProduct->count();
                unset($sub_category->subCategoryProduct);
            });
            return $category;
        });
        $findWhatYouNeedCategories = $findWhatYouNeedCategoriesData->toArray();

        $get_categories = [];
        foreach ($findWhatYouNeedCategories as $category) {
            $slice = array_slice($category['childes'], 0, 4);
            $category['childes'] = $slice;
            $get_categories[] = $category;
        }

        $final_category = [];
        foreach ($get_categories as $category) {
            if (count($category['childes']) > 0) {
                $final_category[] = $category;
            }
        }
        $category_slider = array_chunk($final_category, 4);
        // End find what you need

        // More stores
        $more_seller = $this->seller->approved()->with(['shop', 'product.reviews'])
            ->withCount(['product' => function ($query) {
                $query->active();
            }])
            ->inRandomOrder()
            ->take(7)->get();
        // End more stores

        //feature products finding based on selling
        $featuredProductsList = $this->product->active()->with([
            'seller.shop',
            'flashDealProducts.flashDeal',
            'wishList' => function ($query) {
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            },
            'compareList' => function ($query) {
                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
            }
        ])
        ->where('featured', 1)
        ->withCount(['orderDetails']);
        $featuredProductsList = ProductManager::getPriorityWiseFeaturedProductsQuery(query: $featuredProductsList, dataLimit: 10);

        $featuredProductsList?->map(function ($product) use ($current_date) {
            $flash_deal_status = 0;
            $flash_deal_end_date = 0;
            if (count($product->flashDealProducts) > 0) {
                $flash_deal = $product->flashDealProducts[0]->flashDeal;
                if ($flash_deal) {
                    $start_date = date('Y-m-d H:i:s', strtotime($flash_deal->start_date));
                    $end_date = date('Y-m-d H:i:s', strtotime($flash_deal->end_date));
                    $flash_deal_status = $flash_deal->status == 1 && (($current_date >= $start_date) && ($current_date <= $end_date)) ? 1 : 0;
                    $flash_deal_end_date = $flash_deal->end_date;
                }
            }
            $product['flash_deal_status'] = $flash_deal_status;
            $product['flash_deal_end_date'] = $flash_deal_end_date;
            return $product;
        });

        $latest_products = $this->product->with([
            'seller.shop',
            'flashDealProducts.flashDeal',
            'wishList' => function ($query) {
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            },
            'compareList' => function ($query) {
                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
            }
        ])
        ->active()->orderBy('id', 'desc')
        ->take(10)
        ->get();
        $latest_products?->map(function ($product) use ($current_date) {
            $flash_deal_status = 0;
            $flash_deal_end_date = 0;
            if (count($product->flashDealProducts) > 0) {
                $flash_deal = $product->flashDealProducts[0]->flashDeal;
                if ($flash_deal) {
                    $start_date = date('Y-m-d H:i:s', strtotime($flash_deal->start_date));
                    $end_date = date('Y-m-d H:i:s', strtotime($flash_deal->end_date));
                    $flash_deal_status = $flash_deal->status == 1 && (($current_date >= $start_date) && ($current_date <= $end_date)) ? 1 : 0;
                    $flash_deal_end_date = $flash_deal->end_date;
                }
            }
            $product['flash_deal_status'] = $flash_deal_status;
            $product['flash_deal_end_date'] = $flash_deal_end_date;
            return $product;
        });

        $featured_deals = ProductManager::getPriorityWiseFeatureDealQuery(Product::active(), dataLimit: 'all');

        //best sell product
        $bestSellProduct = Product::active()->with([
            'reviews', 'rating', 'seller.shop',
            'flashDealProducts.flashDeal',
            'wishList' => function ($query) {
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            },
            'compareList' => function ($query) {
                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
            }
        ])->withCount(['reviews']);

        $orderDetails = OrderDetail::with('product')
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->get();
        $getOrderedProductIds = [];
        foreach ($orderDetails as $detail) {
            $getOrderedProductIds[] = $detail['product_id'];
        }
        $bestSellProduct = ProductManager::getPriorityWiseBestSellingProductsQuery(query: $bestSellProduct->whereIn('id', $getOrderedProductIds), dataLimit: 10);

        $bestSellProduct?->map(function ($product) use ($current_date) {
            $flash_deal_status = 0;
            $flash_deal_end_date = 0;
            if (isset($product->flashDealProducts) && count($product->flashDealProducts) > 0) {
                $flash_deal = $product->flashDealProducts[0]->flashDeal;
                if ($flash_deal) {
                    $start_date = date('Y-m-d H:i:s', strtotime($flash_deal->start_date));
                    $end_date = date('Y-m-d H:i:s', strtotime($flash_deal->end_date));
                    $flash_deal_status = $flash_deal->status == 1 && (($current_date >= $start_date) && ($current_date <= $end_date)) ? 1 : 0;
                    $flash_deal_end_date = $flash_deal->end_date;
                }
            }
            $product['flash_deal_status'] = $flash_deal_status;
            $product['flash_deal_end_date'] = $flash_deal_end_date;
            return $product;
        });

        // Just for you portion
        if (auth('customer')->check()) {
            $orders = $this->order->where(['customer_id' => auth('customer')->id()])->with(['details'])->get();

            if ($orders) {
                $orders = $orders?->map(function ($order) {
                    $order_details = $order->details->map(function ($detail) {
                        $product = json_decode($detail->product_details);
                        $category = json_decode($product->category_ids)[0]->id;
                        $detail['category_id'] = $category;
                        return $detail;
                    });
                    try {
                        $order['id'] = $order_details[0]->id;
                        $order['category_id'] = $order_details[0]->category_id;
                    } catch (\Throwable $th) {

                    }

                    return $order;
                });

                $orderCategories = [];
                foreach ($orders as $order) {
                    $orderCategories[] = ($order['category_id']);;
                }
                $ids = array_unique($orderCategories);


                $just_for_you = $this->product->with([
                    'wishList' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compareList' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    }
                ])->active()
                    ->where(function ($query) use ($ids) {
                        foreach ($ids as $id) {
                            $query->orWhere('category_ids', 'like', "%{$id}%");
                        }
                    })
                    ->inRandomOrder()
                    ->take(8)
                    ->get();
            } else {
                $just_for_you = $this->product->with([
                    'wishList' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compareList' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    }
                ])->active()->inRandomOrder()->take(8)->get();
            }
        } else {
            $just_for_you = $this->product->with([
                'wishList' => function ($query) {
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compareList' => function ($query) {
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])->active()->inRandomOrder()->take(8)->get();
        }
        // end just for you

        $topRated = $this->review->with([
            'product.seller.shop',
            'product.wishList' => function ($query) {
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            },
            'product.compareList' => function ($query) {
                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
            }
        ])
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('AVG(rating) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(10)
            ->get();

        if ($bestSellProduct->count() == 0) {
            $bestSellProduct = $latest_products;
        }

        if ($topRated->count() == 0) {
            $topRated = $bestSellProduct;
        }

        $deal_of_the_day = $this->deal_of_the_day->join('products', 'products.id', '=', 'deal_of_the_days.product_id')->select('deal_of_the_days.*', 'products.unit_price')->where('products.status', 1)->where('deal_of_the_days.status', 1)->first();
        $random_product = $this->product->active()->inRandomOrder()->first();

        $banner_list = ['Main Banner', 'Footer Banner', 'Sidebar Banner', 'Main Section Banner', 'Top Side Banner'];
        $banners = $this->banner->whereIn('banner_type', $banner_list)->where(['published' => 1, 'theme' => $theme_name])->orderBy('id', 'desc')->latest('created_at')->get();

        $main_banner = [];
        $footer_banner = [];
        $sidebar_banner = [];
        $main_section_banner = [];
        $top_side_banner = [];
        foreach ($banners as $banner) {
            if ($banner->banner_type == 'Main Banner') {
                $main_banner[] = $banner;
            } elseif ($banner->banner_type == 'Footer Banner') {
                $footer_banner[] = $banner->toArray();
            } elseif ($banner->banner_type == 'Sidebar Banner') {
                $sidebar_banner[] = $banner;
            } elseif ($banner->banner_type == 'Main Section Banner') {
                $main_section_banner[] = $banner;
            } elseif ($banner->banner_type == 'Top Side Banner') {
                $top_side_banner[] = $banner;
            }
        }
        $sidebar_banner = $sidebar_banner ? $sidebar_banner[0] : [];
        $main_section_banner = $main_section_banner ? $main_section_banner[0] : [];
        $top_side_banner = $top_side_banner ? $top_side_banner[0] : [];
        $footer_banner = $footer_banner ? array_slice($footer_banner, 0, 2) : [];

        $decimal_point = Helpers::get_business_settings('decimal_point_settings');
        $decimal_point_settings = !empty($decimal_point) ? $decimal_point : 0;
        $user = Helpers::get_customer();

        //order again
        $order_again = $user != 'offline' ?
            $this->order->with('details.product')->where(['order_status' => 'delivered', 'customer_id' => $user->id])->latest()->take(8)->get()
            : [];

        $random_coupon = Coupon::with('seller')
            ->where(['status' => 1])
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'))
            ->inRandomOrder()->take(3)->get();

        return view(VIEW_FILE_NAMES['home'],
            compact(
                'flashDeal', 'topRated', 'bestSellProduct', 'latest_products', 'featuredProductsList', 'deal_of_the_day', 'topVendorsList',
                'home_categories', 'main_banner', 'footer_banner', 'random_product', 'decimal_point_settings', 'just_for_you', 'more_seller',
                'final_category', 'category_slider', 'order_again', 'sidebar_banner', 'main_section_banner', 'random_coupon', 'top_side_banner',
                'featured_deals', 'categories'
            )
        );
    }

    public function theme_fashion(): View
    {
        $activeBrands = BrandManager::getActiveBrandWithCountingAndPriorityWiseSorting();
        $categories = CategoryManager::getCategoriesWithCountingAndPriorityWiseSorting();
        $userId = Auth::guard('customer')->user() ? Auth::guard('customer')->id() : 0;
        $flashDeal = ProductManager::getPriorityWiseFlashDealsProductsQuery(userId: $userId);

        $theme_name = theme_root_path();
        $currentDate = date('Y-m-d H:i:s');

        // Start Order Based on Top Seller
        $topVendorsList = Shop::active()
            ->withCount(['products' => function ($query) {
                $query->active();
            }])
            ->with('seller', function ($query) {
                $query->with('product', function ($query) {
                    $query->active()->with('reviews', function ($query) {
                        $query->active();
                    });
                })
                    ->withCount(['orders']);
            })
            ->get()
            ->each(function ($shop) {
                $shop->orders_count = $shop->seller->orders_count;

                $productReviews = $shop->seller->product->pluck('reviews')->collapse();
                $shop->average_rating = $productReviews->avg('rating');
                $shop->review_count = $productReviews->count();
                $shop->total_rating = $productReviews->sum('rating');

                $positiveReviewsCount = $productReviews->where('rating', '>=', 4)->count();
                $shop->positive_review = ($shop->review_count !== 0) ? ($positiveReviewsCount * 100) / $shop->review_count : 0;

                $currentDate = date('Y-m-d');
                $startDate = date('Y-m-d', strtotime($shop['vacation_start_date']));
                $endDate = date('Y-m-d', strtotime($shop['vacation_end_date']));
                $shop->is_vacation_mode_now = $shop['vacation_status'] && ($currentDate >= $shop['vacation_start_date']) && ($currentDate <= $shop['vacation_end_date']) ? 1 : 0;
            })->take(12);

        $inhouseProducts = Product::active()->with(['reviews', 'rating'])->withCount('reviews')->where(['added_by' => 'admin'])->get();
        $inhouseProductCount = $inhouseProducts->count();

        $inhouseReviewData = Review::active()->whereIn('product_id', $inhouseProducts->pluck('id'));
        $inhouseReviewDataCount = $inhouseReviewData->count();
        $inhouseRattingStatusPositive = 0;
        foreach ($inhouseReviewData->pluck('rating') as $singleRating) {
            ($singleRating >= 4 ? ($inhouseRattingStatusPositive++) : '');
        }

        $inhouseShop = $this->getInHouseShopObject();
        $inhouseShop->id = 0;
        $inhouseShop->products_count = $inhouseProductCount;
        $inhouseShop->total_rating = $inhouseReviewDataCount;
        $inhouseShop->review_count = $inhouseReviewDataCount;
        $inhouseShop->average_rating = $inhouseReviewData->avg('rating');
        $inhouseShop->positive_review = $inhouseReviewDataCount != 0 ? ($inhouseRattingStatusPositive * 100) / $inhouseReviewDataCount : 0;
        $inhouseShop->orders_count = Order::where(['seller_is' => 'admin'])->count();
        $topVendorsList = $topVendorsList->prepend($inhouseShop);
        $topVendorsList = ProductManager::getPriorityWiseTopVendorQuery($topVendorsList);
        // End Order Based on Top Seller

        /*
         * Top rated store and new seller
         */
        $seller_list = $this->seller->approved()->with(['shop', 'product.reviews'])
            ->withCount(['product' => function ($query) {
                $query->active();
            }])->get();
        $seller_list?->map(function ($seller) {
            $rating = 0;
            $count = 0;
            foreach ($seller->product as $item) {
                foreach ($item->reviews as $review) {
                    $rating += $review->rating;
                    $count++;
                }
            }
            $avg_rating = $rating / ($count == 0 ? 1 : $count);
            $rating_count = $count;
            $seller['average_rating'] = $avg_rating;
            $seller['rating_count'] = $rating_count;

            $product_count = $seller->product->count();
            $random_product = Arr::random($seller->product->toArray(), $product_count < 3 ? $product_count : 3);
            $seller['product'] = $random_product;
            return $seller;
        });
        $newSellers = $seller_list->sortByDesc('id')->take(12);
        $topRatedShops = $seller_list->where('rating_count', '!=', 0)->sortByDesc('average_rating')->take(12);

        /*
         * End Top Rated store and new seller
         */

        // latest product
        $latest_products = $this->product->withSum('orderDetails', 'qty', function ($query) {
            $query->where('delivery_status', 'delivered');
        })->with(['category', 'reviews', 'flashDealProducts.flashDeal', 'wishList' => function ($query) {
            return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
        }])
            ->active()->orderBy('id', 'desc')
            ->paginate(20);

        $latest_products?->map(function ($product) use ($currentDate) {
            $flash_deal_status = 0;
            $flash_deal_end_date = 0;
            if (count($product->flashDealProducts) > 0) {
                $flash_deal = $product->flashDealProducts[0]->flashDeal;
                if ($flash_deal) {
                    $start_date = date('Y-m-d H:i:s', strtotime($flash_deal->start_date));
                    $end_date = date('Y-m-d H:i:s', strtotime($flash_deal->end_date));
                    $flash_deal_status = $flash_deal->status == 1 && (($currentDate >= $start_date) && ($currentDate <= $end_date)) ? 1 : 0;
                    $flash_deal_end_date = $flash_deal->end_date;
                }
            }
            $product['flash_deal_status'] = $flash_deal_status;
            $product['flash_deal_end_date'] = $flash_deal_end_date;
            return $product;
        });
        //end latest product

        // All product Section
        $all_products = $this->product->withSum('orderDetails', 'qty', function ($query) {
                $query->where('delivery_status', 'delivered');
            })->with(['category', 'reviews', 'flashDealProducts.flashDeal', 'wishList' => function ($query) {
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            }])
            ->active()
            ->orderBy('order_details_sum_qty', 'DESC')
            ->paginate(20);

        $all_products?->map(function ($product) use ($currentDate) {
            $flash_deal_status = 0;
            $flash_deal_end_date = 0;
            if (count($product->flashDealProducts) > 0) {
                $flash_deal = $product->flashDealProducts[0]->flashDeal;
                if ($flash_deal) {
                    $start_date = date('Y-m-d H:i:s', strtotime($flash_deal->start_date));
                    $end_date = date('Y-m-d H:i:s', strtotime($flash_deal->end_date));
                    $flash_deal_status = $flash_deal->status == 1 && (($currentDate >= $start_date) && ($currentDate <= $end_date)) ? 1 : 0;
                    $flash_deal_end_date = $flash_deal->end_date;
                }
            }
            $product['flash_deal_status'] = $flash_deal_status;
            $product['flash_deal_end_date'] = $flash_deal_end_date;
            return $product;
        });


        /**
         *  Start Deal of the day and random product and banner
         */
        $deal_of_the_day = $this->deal_of_the_day->with(['product' => function ($query) {
            $query->active();
        }])->where('status', 1)->first();
        $random_product = $this->product->active()->inRandomOrder()->first();

        $main_banner = Banner::where(['banner_type' => 'Main Banner', 'published' => 1, 'theme' => $theme_name])->latest()->get();

        $promo_banner_left = Banner::where(['banner_type' => 'Promo Banner Left', 'published' => 1, 'theme' => $theme_name])->first();
        $promo_banner_middle_top = Banner::where(['banner_type' => 'Promo Banner Middle Top', 'published' => 1, 'theme' => $theme_name])->first();
        $promo_banner_middle_bottom = Banner::where(['banner_type' => 'Promo Banner Middle Bottom', 'published' => 1, 'theme' => $theme_name])->first();
        $promo_banner_right = Banner::where(['banner_type' => 'Promo Banner Right', 'published' => 1, 'theme' => $theme_name])->first();
        $promo_banner_bottom = Banner::where(['banner_type' => 'Promo Banner Bottom', 'published' => 1, 'theme' => $theme_name])->first();

        $sidebar_banner = Banner::where(['banner_type' => 'Sidebar Banner', 'published' => 1, 'theme' => $theme_name])->latest()->first();
        $top_side_banner = Banner::where(['banner_type' => 'Top Side Banner', 'published' => 1, 'theme' => $theme_name])->orderBy('id', 'desc')->latest()->first();

        /**
         * end
         */
        $decimal_point_settings = !empty(getWebConfig(name: 'decimal_point_settings')) ? getWebConfig(name: 'decimal_point_settings') : 0;
        $user = Helpers::get_customer();

        // theme fashion -- Shop Again From Your Recent Store
        $recent_order_shops = $user != 'offline' ?
            $this->product->with('seller.shop')
                ->whereHas('seller.orders', function ($query) {
                    $query->where(['customer_id' => auth('customer')->id(), 'seller_is' => 'seller']);
                })->active()
                ->inRandomOrder()->take(12)->get()
            : [];
        //end theme fashion -- Shop Again From Your Recent Store

        $most_searching_product = Product::active()->with(['category', 'wishList' => function ($query) {
            return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
        }])->withCount('reviews')->withSum('tags', 'visit_count')->orderBy('tags_sum_visit_count', 'desc')->get();

        $allCategories = Category::withCount(['product' => function ($query) {
                $query->active();
            }])->with(['childes' => function ($sub_query) {
                $sub_query->with(['childes' => function ($sub_sub_query) {
                    $sub_sub_query->withCount(['subSubCategoryProduct'])->where('position', 2);
                }])->withCount(['subCategoryProduct'])->where('position', 1);
            }, 'childes.childes'])
            ->where('position', 0);

        $mostVisitedCategories = CategoryManager::getPriorityWiseCategorySortQuery(query: $allCategories->get());

        $colors_in_shop = ProductManager::getProductsColorsArray();

        $most_searching_product = $most_searching_product->take(10);

        $allProductSectionOrders = $this->order->where(['order_type' => 'default_type']);
        $all_products_info = [
            'total_products' => $this->product->active()->count(),
            'total_orders' => $allProductSectionOrders->count(),
            'total_delivery' => $allProductSectionOrders->where(['payment_status' => 'paid', 'order_status' => 'delivered'])->count(),
            'total_reviews' => $this->review->where('product_id', '!=', 0)->whereNull('delivery_man_id')->count(),
        ];


        // start most demanded product
        $most_demanded_product = $this->most_demanded->where('status', 1)->with(['product' => function ($query) {
            $query->withCount('wishList', 'orderDetails', 'orderDelivered', 'reviews');
        }])->whereHas('product', function ($query) {
            return $query->active();
        })->first();
        // end most demanded product

        // Featured products
        $featuredProductsList = $this->product->active()->where('featured', 1)
            ->with(['wishList' => function ($query) {
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            }])->withCount(['reviews']);

        $featuredProductsList = ProductManager::getPriorityWiseFeaturedProductsQuery(query: $featuredProductsList);

        $featured_deals = ProductManager::getPriorityWiseFeatureDealQuery($this->product->active(), dataLimit: 4);

        return view(VIEW_FILE_NAMES['home'],
            compact(
                'activeBrands', 'latest_products', 'deal_of_the_day', 'topVendorsList', 'topRatedShops', 'main_banner', 'mostVisitedCategories',
                'random_product', 'decimal_point_settings', 'newSellers', 'sidebar_banner', 'top_side_banner', 'recent_order_shops',
                'categories', 'colors_in_shop', 'all_products_info', 'most_searching_product', 'most_demanded_product', 'featuredProductsList', 'promo_banner_left',
                'promo_banner_middle_top', 'promo_banner_middle_bottom', 'promo_banner_right', 'promo_banner_bottom', 'currentDate', 'all_products',
                'featured_deals', 'flashDeal'
            )
        );
    }

    public function theme_all_purpose()
    {
        $user = Helpers::get_customer();
        $main_banner = $this->banner->where('banner_type', 'Main Banner')->where('published', 1)->latest()->get();
        $footer_banner = $this->banner->where('banner_type', 'Footer Banner')->where('published', 1)->latest()->take(2)->get();
        // Start best-selling product end
        $best_sellling_products = $this->order_details->with([
            'product.reviews', 'product.flashDealProducts.flashDeal',
            'product.seller.shop', 'product.wishList' => function ($query) {
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            },
            'product.compareList' => function ($query) {
                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
            }
        ])
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(8)
            ->get();
        // end best selling product

        $category_ids = $this->product->withSum('tags', 'visit_count')->orderBy('tags_sum_visit_count', 'desc')->pluck('category_id')->unique();
        $categories = $this->category->withCount(['product' => function ($query) {
            $query->active();
        }])->whereIn('id', $category_ids)->orderBy('product_count', 'desc')->take(18)->get();
        // Popular Departments

        // start latest product
        $latest_products_count = $this->product->active()->count();
        $latest_products = $this->product->with(['category'])->active()->latest()->take(8)->get();
        // end of latest product

        // start Just for you
        if (auth('customer')->check()) {
            $orders = $this->order->with('details.product')->where(['customer_id' => auth('customer')->id()])->with(['details'])->get();
            if ($orders) {
                $order_details = $orders?->flatMap(function ($order) {
                    return $order?->details->map(function ($detail) {
                        $detail['category_id'] = $detail?->product?->category_id;
                        return $detail;
                    });
                });

                $just_for_you = $this->product->with('rating')->active()
                    ->whereIn('category_id', $order_details->pluck('category_id')->unique())
                    ->inRandomOrder()
                    ->take(4)
                    ->get();
            } else {
                $just_for_you = $this->product->with('rating')->active()->inRandomOrder()->take(4)->get();
            }
        } else {
            $just_for_you = $this->product->with('rating')->active()->inRandomOrder()->take(4)->get();
        }
        // end just for you

        // start deal of the day
        $deal_of_the_day = $this->deal_of_the_day->join('products', 'products.id', '=', 'deal_of_the_days.product_id')
            ->select('deal_of_the_days.*', 'products.unit_price')->where('products.status', 1)
            ->where('deal_of_the_days.status', 1)->first();
        // end of deal of the day

        // start dicounted products
        $discounted_products = $this->product->active()->with(['wishList' => function ($query) {
            return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
        },
            'compareList' => function ($query) {
                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
            }
        ])
            ->where('discount', '!=', 0)->get();

        $discount_up_to_10 = $discounted_products->filter(function ($product) {
            if ($product->discount_type === 'percent' && $product->discount <= 10) {
                return $product;
            } elseif ($product->discount_type === 'flat' && ($product->discount * 100) / $product->unit_price <= 10) {
                return $product;
            }
        });
        $discount_up_to_50 = $discounted_products->filter(function ($product) {
            if ($product->discount_type === 'percent' && $product->discount > 10 && $product->discount <= 50) {
                return $product;
            } elseif ($product->discount_type === 'flat' && (($product->discount * 100) / $product->unit_price) > 10 && (($product->discount * 100) / $product->unit_price) <= 50) {
                return $product;
            }
        });
        $discount_up_to_80 = $discounted_products->filter(function ($product) {
            if ($product->discount_type === 'percent' && $product->discount > 50 && $product->discount <= 80) {
                return $product;
            } elseif ($product->discount_type === 'flat' && (($product->discount * 100) / $product->unit_price) > 50 && (($product->discount * 100) / $product->unit_price) <= 80) {
                return $product;
            }
        });
        // end discounted product

        //featured deal product start
        $featured_deals = $this->product->with([
            'seller.shop', 'category',
            'flashDealProducts.featureDeal',
            'flashDealProducts.flashDeal' => function ($query) {
                return $query->whereDate('start_date', '<=', date('Y-m-d'))
                    ->whereDate('end_date', '>=', date('Y-m-d'));
            }, 'wishList' => function ($query) {
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            }, 'compareList' => function ($query) {
                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
            }
        ])->whereHas('flashDealProducts.featureDeal', function ($query) {
            $query->whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'));
        })->get();
        //featured deal product end

        // start order again
        $order_again = $user != 'offline' ?
            $this->order->with('details.product')->where(['order_status' => 'delivered', 'customer_id' => $user->id])->get()
            : [];
        if (!empty($order_again)) {
            $order_again_products = $order_again?->flatMap(function ($query) {
                return $query->details->pluck('product')->unique();
            })->filter()->take(10);
        } else {
            $order_again_products = [];
        }
        // end order again

        // start top-rated branch
        $reviews = $this->review->with([
            'product.brand',
        ])->whereHas('product.brand', function ($query) {
            $query->where('status', 1);
        })
            ->take(20)
            ->get();
        if (!empty($reviews)) {
            $top_rated_brands = $reviews->map(function ($query) {
                return $query->product->brand;
            });
        } else {
            $top_rated_brands = [];
        }
        // end of top-rated brand

        // start top-rated store
        $top_sellers = $this->seller->approved()->with(['shop', 'orders', 'product.reviews'])
            ->whereHas('orders', function ($query) {
                $query->where('seller_is', 'seller');
            })
            ->withCount(['orders', 'product' => function ($query) {
                $query->active();
            }])->orderBy('orders_count', 'DESC')->take(4)->get();

        $top_sellers->map(function ($seller) {
            $seller->product->map(function ($product) {
                $product['avarage_rating'] = $product?->reviews->pluck('rating')->avg();
                $product['rating_count'] = $product->reviews->count();
            });
            $seller['avg_rating'] = $seller?->product->pluck('avarage_rating')->filter()->avg();
            $seller['total_rating'] = $seller->product->pluck('rating_count')->filter()->count();
        });
        // end top reated store

        // start other store
        $more_sellers = $this->seller->approved()->with(['shop', 'product.reviews'])
            ->withCount(['product' => function ($query) {
                $query->active();
            }])
            ->inRandomOrder()->take(8)->get();

        $more_sellers->map(function ($seller) {
            $seller->product->map(function ($product) {
                $product['avarage_rating'] = $product?->reviews->pluck('rating')->avg();
                $product['rating_count'] = $product->reviews->count();
            });
            $seller['avg_rating'] = $seller?->product->pluck('avarage_rating')->filter()->avg();
            $seller['total_rating'] = $seller->product->pluck('rating_count')->filter()->count();
        });

        // end other store


        // start category wise product
        $category_wise_products = $this->category
            ->where('home_status', true)
            ->with(['product' => function ($query) {
                $query->with(['category', 'reviews', 'rating',
                    'wishList' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compareList' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    }
                ])->inRandomOrder()->take(12)->get();
            }])->priority()->get();
        // end category wise product

        return view(VIEW_FILE_NAMES['home'], compact('main_banner', 'footer_banner', 'categories', 'best_sellling_products',
            'discounted_products', 'featured_deals', 'just_for_you', 'deal_of_the_day', 'order_again_products', 'top_rated_brands', 'top_sellers',
            'more_sellers', 'latest_products_count', 'latest_products', 'category_wise_products'));
    }


}
