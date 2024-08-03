<?php

namespace App\Http\Controllers\Web;

use App\Contracts\Repositories\OrderDetailRepositoryInterface;
use App\Contracts\Repositories\ProductCompareRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\ProductTagRepositoryInterface;
use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Contracts\Repositories\SellerRepositoryInterface;
use App\Contracts\Repositories\TagRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Tag;
use App\Models\Wishlist;
use App\Repositories\DealOfTheDayRepository;
use App\Repositories\WishlistRepository;
use App\Traits\ProductTrait;
use App\Utils\Helpers;
use App\Utils\ProductManager;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProductDetailsController extends Controller
{
    use ProductTrait;
    public function __construct(
        private readonly ProductRepositoryInterface        $productRepo,
        private readonly WishlistRepository                $wishlistRepo,
        private readonly ReviewRepositoryInterface         $reviewRepo,
        private readonly OrderDetailRepositoryInterface    $orderDetailRepo,
        private readonly DealOfTheDayRepository            $dealOfTheDayRepo,
        private readonly ProductCompareRepositoryInterface $compareRepo,
        private readonly ProductTagRepositoryInterface     $productTagRepo,
        private readonly TagRepositoryInterface            $tagRepo,
        private readonly SellerRepositoryInterface         $sellerRepo,
    )
    {
    }

    /**
     * @param string $slug
     * @return View
     */
    public function index(string $slug): View|RedirectResponse
    {
        $theme_name = theme_root_path();

        return match ($theme_name) {
            'default' => self::getDefaultTheme(slug: $slug),
            'theme_aster' => self::getThemeAster(slug: $slug),
            'theme_fashion' => self::getThemeFashion(slug: $slug),
            'theme_all_purpose' => self::theme_all_purpose($slug),
        };
    }

    public function getDefaultTheme(string $slug): View|RedirectResponse
    {
        $product = $this->productRepo->getFirstWhereActive(params: ['slug' => $slug], relations: ['reviews', 'seller.shop']);
        if ($product) {
            $overallRating = getOverallRating(reviews: $product->reviews);
            $wishlistStatus = $this->wishlistRepo->getListWhereCount(filters: ['product_id' => $product['id'], 'customer_id' => auth('customer')->id()]);
            $productReviews = $this->reviewRepo->getListWhere(
                orderBy: ['id' => 'desc'],
                filters: ['product_id' => $product['id']],
                dataLimit: 2, offset: 1
            );

            $rating = getRating(reviews: $product->reviews);
            $decimalPointSettings = getWebConfig('decimal_point_settings');
            $moreProductFromSeller = $this->productRepo->getWebListWithScope(
                orderBy: ['id' => 'desc'],
                scope: 'active',
                filters: ['added_by' => $product['added_by'] == 'admin' ? 'in_house' : $product['added_by'], 'seller_id' => $product['user_id']],
                whereNotIn: ['id' => [$product['id']]],
                dataLimit: 5,
                offset: 1
            );

            if ($product['added_by'] == 'seller') {
                $productsForReview = $this->productRepo->getWebListWithScope(
                    scope: 'active',
                    filters: ['added_by' => $product['added_by'], 'seller_id' => $product['user_id']],
                    withCount: ['reviews' => 'reviews']
                );
            } else {
                $productsForReview = $this->productRepo->getWebListWithScope(
                    scope: 'active',
                    filters: ['added_by' => 'in_house', 'seller_id' => $product['user_id']],
                    withCount: ['reviews' => 'reviews']
                );
            }

            $totalReviews = 0;
            foreach ($productsForReview as $item) {
                $totalReviews += $item->reviews_count;
            }
            $countOrder = $this->orderDetailRepo->getListWhereCount(filters: ['product_id' => $product['id']]);
            $countWishlist = $this->wishlistRepo->getListWhereCount(filters: ['product_id' => $product['id']]);
            $relatedProducts = $this->productRepo->getWebListWithScope(
                scope: 'active',
                filters: ['category_id' => $product['category_id']],
                whereNotIn: ['id' => [$product['id']]],
                relations: ['reviews'=>'reviews'],
                dataLimit: 12,
                offset: 1
            );
            $dealOfTheDay = $this->dealOfTheDayRepo->getFirstWhere(['product_id' => $product['id'], 'status' => 1]);
            $currentDate = date('Y-m-d');
            $sellerVacationStartDate = ($product['added_by'] == 'seller' && isset($product->seller->shop->vacation_start_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_start_date)) : null;
            $sellerVacationEndDate = ($product['added_by'] == 'seller' && isset($product->seller->shop->vacation_end_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_end_date)) : null;
            $sellerTemporaryClose = ($product['added_by'] == 'seller' && isset($product->seller->shop->temporary_close)) ? $product->seller->shop->temporary_close : false;

            $temporaryClose = getWebConfig('temporary_close');
            $inHouseVacation = getWebConfig('vacation_add');
            $inHouseVacationStartDate = $product['added_by'] == 'admin' ? $inHouseVacation['vacation_start_date'] : null;
            $inHouseVacationEndDate = $product['added_by'] == 'admin' ? $inHouseVacation['vacation_end_date'] : null;
            $inHouseVacationStatus = $product['added_by'] == 'admin' ? $inHouseVacation['status'] : false;
            $inHouseTemporaryClose = $product['added_by'] == 'admin' ? $temporaryClose['status'] : false;

            return view(VIEW_FILE_NAMES['products_details'], compact('product', 'countWishlist', 'countOrder', 'relatedProducts',
                'dealOfTheDay', 'currentDate', 'sellerVacationStartDate', 'sellerVacationEndDate', 'sellerTemporaryClose',
                'inHouseVacationStartDate', 'inHouseVacationEndDate', 'inHouseVacationStatus', 'inHouseTemporaryClose', 'overallRating',
                'wishlistStatus', 'productReviews', 'rating', 'totalReviews', 'productsForReview', 'moreProductFromSeller', 'decimalPointSettings'));
        }

        Toastr::error(translate('not_found'));
        return back();
    }

    public function getThemeAster(string $slug): View|RedirectResponse
    {
        $product = $this->productRepo->getWebFirstWhereActive(
            params: ['slug' => $slug, 'customer_id' => Auth::guard('customer')->user()->id ?? 0],
            relations: ['reviews' => 'reviews', 'seller.shop' => 'seller.shop', 'wishList' => 'wishList', 'compareList' => 'compareList'],
            withCount: ['orderDetails' => 'orderDetails', 'wishList' => 'wishList']
        );

        if ($product != null) {
            $currentDate = date('Y-m-d H:i:s');

            $countOrder = $product['order_details_count'];
            $countWishlist = $product['wish_list_count'];
            $wishlistStatus = $this->wishlistRepo->getCount(params: ['product_id' => $product->id, 'customer_id' => auth('customer')->id()]);
            $compareList = $this->compareRepo->getCount(params: ['product_id' => $product->id, 'customer_id' => auth('customer')->id()]);

            $relatedProducts = $this->productRepo->getWebListWithScope(
                scope: 'active',
                filters: ['category_ids' => $product['category_ids'], 'customer_id' => Auth::guard('customer')->user()->id ?? 0],
                whereNotIn: ['id' => [$product['id']]],
                relations: ['reviews'=>'reviews', 'flashDealProducts.flashDeal'=>'flashDealProducts.flashDeal', 'wishList'=>'wishList','compareList'=>'compareList'],
                withCount: ['reviews' => 'reviews'],
                dataLimit: 12,
                offset: 1
            );
            $relatedProducts?->map(function ($product) use ($currentDate) {
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

            $dealOfTheDay = $this->dealOfTheDayRepo->getFirstWhere(['product_id' => $product['id'], 'status' => 1]);
            $currentDate = date('Y-m-d');
            $sellerVacationStartDate = ($product['added_by'] == 'seller' && isset($product->seller->shop->vacation_start_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_start_date)) : null;
            $sellerVacationEndDate = ($product['added_by'] == 'seller' && isset($product->seller->shop->vacation_end_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_end_date)) : null;
            $sellerTemporaryClose = ($product['added_by'] == 'seller' && isset($product->seller->shop->temporary_close)) ? $product->seller->shop->temporary_close : false;

            $temporaryClose = getWebConfig('temporary_close');
            $inHouseVacation = getWebConfig('vacation_add');
            $inHouseVacationStartDate = $product['added_by'] == 'admin' ? $inHouseVacation['vacation_start_date'] : null;
            $inHouseVacationEndDate = $product['added_by'] == 'admin' ? $inHouseVacation['vacation_end_date'] : null;
            $inHouseVacationStatus = $product['added_by'] == 'admin' ? $inHouseVacation['status'] : false;
            $inHouseTemporaryClose = $product['added_by'] == 'admin' ? $temporaryClose['status'] : false;

            $overallRating = getOverallRating($product['reviews']);

            $rating = getRating($product->reviews);
            $productReviews = $this->reviewRepo->getListWhere(
                orderBy: ['id' => 'desc'],
                filters: ['product_id' => $product['id']],
                dataLimit: 2, offset: 1
            );
            $decimalPointSettings = getWebConfig('decimal_point_settings');
            $moreProductFromSeller = $this->productRepo->getWebListWithScope(
                orderBy: ['id' => 'desc'],
                scope: 'active',
                filters: ['added_by' => $product['added_by'] == 'admin' ? 'in_house' : $product['added_by'], 'seller_id' => $product['user_id']],
                whereNotIn: ['id' => [$product['id']]],
                dataLimit: 5,
                offset: 1
            );

            if ($product['added_by'] == 'seller') {
                $productsForReview = $this->productRepo->getWebListWithScope(
                    scope: 'active',
                    filters: ['added_by' => $product['added_by'], 'seller_id' => $product['user_id']],
                    withCount: ['reviews' => 'reviews']
                );
            } else {
                $productsForReview = $this->productRepo->getWebListWithScope(
                    scope: 'active',
                    filters: ['added_by' => 'in_house', 'seller_id' => $product['user_id']],
                    withCount: ['reviews' => 'reviews']
                );
            }

            $totalReviews = 0;
            foreach ($productsForReview as $item) {
                $totalReviews += $item->reviews_count;
            }

            $productIds = Product::active()->where(['added_by' => $product['added_by']])
                ->where('user_id', $product['user_id'])->pluck('id')->toArray();
            $vendorReviewData = Review::active()->whereIn('product_id', $productIds);
            $ratingCount = $vendorReviewData->count();
            $avgRating = $vendorReviewData->avg('rating');

            $vendorRattingStatusPositive = 0;
            foreach($vendorReviewData->pluck('rating') as $singleRating) {
                ($singleRating >= 4?($vendorRattingStatusPositive++):'');
            }

            $positiveReview = $ratingCount != 0 ? ($vendorRattingStatusPositive*100)/ $ratingCount:0;

            return view(VIEW_FILE_NAMES['products_details'], compact('product', 'wishlistStatus', 'countWishlist',
                'countOrder', 'relatedProducts', 'dealOfTheDay', 'currentDate', 'sellerVacationStartDate', 'sellerVacationEndDate',
                'sellerTemporaryClose', 'inHouseVacationStartDate', 'inHouseVacationEndDate', 'inHouseVacationStatus', 'inHouseTemporaryClose',
                'overallRating', 'decimalPointSettings', 'moreProductFromSeller', 'productsForReview', 'totalReviews', 'rating', 'productReviews',
                'avgRating', 'compareList', 'positiveReview'));
        }

        Toastr::error(translate('not_found'));
        return back();

    }

    public function getThemeFashion($slug): View|RedirectResponse
    {
        $product = $this->productRepo->getWebFirstWhereActive(
            params: ['slug' => $slug, 'customer_id' => Auth::guard('customer')->user()->id ?? 0],
            relations: ['reviews' => 'reviews', 'seller.shop' => 'seller.shop', 'wishList' => 'wishList', 'compareList' => 'compareList'],
            withCount: ['orderDetails' => 'orderDetails', 'wishList' => 'wishList']
        );
        if ($product != null) {
            $tags = $this->productTagRepo->getIds(fieldName: 'tag_id', filters: ['product_id' => $product['id']]);
            $this->tagRepo->incrementVisitCount(whereIn: ['id' => $tags]);

            $currentDate = date('Y-m-d H:i:s');
            $countWishlist = $product['wish_list_count'];
            $wishlistStatus = $this->wishlistRepo->getCount(params: ['product_id' => $product->id, 'customer_id' => auth('customer')->id()]);
            $compareList = $this->compareRepo->getCount(params: ['product_id' => $product->id, 'customer_id' => auth('customer')->id()]);
            $relatedProducts = $this->productRepo->getWebListWithScope(
                scope: 'active',
                filters: ['category_id' => $product['category_id'],'customer_id' => Auth::guard('customer')->user()->id ?? 0],
                whereNotIn: ['id' => [$product['id']]],
                relations: ['reviews'=>'reviews', 'flashDealProducts.flashDeal'=>'flashDealProducts.flashDeal', 'wishList'=>'wishList','compareList'=>'compareList'],
                dataLimit: 'all',
            )->count();
            $sellerVacationStartDate = ($product['added_by'] == 'seller' && isset($product->seller->shop->vacation_start_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_start_date)) : null;
            $sellerVacationEndDate = ($product['added_by'] == 'seller' && isset($product->seller->shop->vacation_end_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_end_date)) : null;
            $sellerTemporaryClose = ($product['added_by'] == 'seller' && isset($product->seller->shop->temporary_close)) ? $product->seller->shop->temporary_close : false;

            $temporaryClose = getWebConfig(name: 'temporary_close');
            $inHouseVacation = getWebConfig(name: 'vacation_add');
            $inHouseVacationStartDate = $product['added_by'] == 'admin' ? $inHouseVacation['vacation_start_date'] : null;
            $inHouseVacationEndDate = $product['added_by'] == 'admin' ? $inHouseVacation['vacation_end_date'] : null;
            $inHouseVacationStatus = $product['added_by'] == 'admin' ? $inHouseVacation['status'] : false;
            $inHouseTemporaryClose = $product['added_by'] == 'admin' ? $temporaryClose['status'] : false;

            $overallRating = getOverallRating($product['reviews']);
            $productReviewsCount = $product->reviews->count();

            $rattingStatusPositive = $productReviewsCount != 0 ? ($product->reviews->where('rating', '>=', 4)->count() * 100) / $productReviewsCount : 0;
            $rattingStatusGood = $productReviewsCount != 0 ? ($product->reviews->where('rating', 3)->count() * 100) / $productReviewsCount : 0;
            $rattingStatusNeutral = $productReviewsCount != 0 ? ($product->reviews->where('rating', 2)->count() * 100) / $productReviewsCount : 0;
            $rattingStatusNegative = $productReviewsCount != 0 ? ($product->reviews->where('rating', '=', 1)->count() * 100) / $productReviewsCount : 0;
            $rattingStatus = [
                'positive' => $rattingStatusPositive,
                'good' => $rattingStatusGood,
                'neutral' => $rattingStatusNeutral,
                'negative' => $rattingStatusNegative,
            ];

            $rating = getRating($product->reviews);
            $productReviews = $this->reviewRepo->getListWhere(
                orderBy: ['id' => 'desc'],
                filters: ['product_id' => $product['id']],
                dataLimit: 2, offset: 1
            );
            $decimalPointSettings = getWebConfig('decimal_point_settings');
            $moreProductFromSeller = $this->productRepo->getWebListWithScope(
                orderBy: ['id' => 'desc'],
                scope: 'active',
                filters: ['added_by' => $product['added_by'] == 'admin' ? 'in_house' : $product['added_by'], 'seller_id' => $product['user_id'],'customer_id' => Auth::guard('customer')->user()->id ?? 0],
                whereNotIn: ['id' => [$product['id']]],
                relations: ['wishList'=>'wishList'],
                dataLimit: 5,
                offset: 1
            );
            if ($product['added_by'] == 'seller') {
                $productsForReview = $this->productRepo->getWebListWithScope(
                    scope: 'active',
                    filters: ['added_by' => $product['added_by'], 'seller_id' => $product['user_id']],
                    withCount: ['reviews' => 'reviews']
                );
            } else {
                $productsForReview = $this->productRepo->getWebListWithScope(
                    scope: 'active',
                    filters: ['added_by' => 'in_house', 'seller_id' => $product['user_id']],
                    withCount: ['reviews' => 'reviews']
                );
            }
            $productsCount = $productsForReview->count();
            $totalReviews = 0;
            foreach ($productsForReview as $item) {
                $totalReviews += $item->reviews_count;
            }

            $productIds = Product::active()->where(['added_by' => $product['added_by']])
                ->where('user_id', $product['user_id'])->pluck('id')->toArray();
            $vendorReviewData = Review::active()->whereIn('product_id', $productIds);
            $ratingCount = $vendorReviewData->count();
            $avgRating = $vendorReviewData->avg('rating');

            $vendorRattingStatusPositive = 0;
            foreach($vendorReviewData->pluck('rating') as $singleRating) {
                ($singleRating >= 4?($vendorRattingStatusPositive++):'');
            }

            $positiveReview = $ratingCount != 0 ? ($vendorRattingStatusPositive*100)/ $ratingCount:0;

            $sellerList = $this->sellerRepo->getListWithScope(
                scope: 'active',
                filters: ['category_id' => $product['category_id']],
                relations: ['shop'=>'shop', 'product.reviews'=>'product.reviews'],
                withCount: ['product'=>'product'],
                dataLimit: 'all',
            );
            $sellerList?->map(function ($seller) {
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
            $newSellers = $sellerList->sortByDesc('id')->take(12);
            $topRatedShops = $sellerList->where('rating_count', '!=', 0)->sortByDesc('average_rating')->take(12);

            $deliveryInfo = self::getProductDeliveryCharge(product: $product, quantity: $product['minimum_order_qty']);
            $productsThisStoreTopRated = $this->productRepo->getWebListWithScope(
                orderBy: ['reviews_count' => 'DESC'],
                scope: 'active',
                filters: ['added_by' => $product['added_by'] == 'admin' ? 'in_house' : $product['added_by'], 'seller_id' => $product['user_id'],'customer_id' => Auth::guard('customer')->user()->id ?? 0],
                whereHas: ['reviews'=>'reviews'],
                relations: ['category'=>'category', 'rating'=>'rating', 'reviews'=>'reviews','wishList'=>'wishList','compareList'=>'compareList'],
                withCount: ['reviews' => 'reviews'],
                withSum: [['relation'=>'orderDetails', 'column'=>'qty', 'whereColumn'=>'delivery_status', 'whereValue'=>'delivered']],
                dataLimit: 12,
                offset: 1
            );

            $productsTopRated = $this->productRepo->getWebListWithScope(
                orderBy: ['reviews_count' => 'DESC'],
                scope: 'active',
                filters: ['category_id' => $product['category_id'], 'customer_id' => Auth::guard('customer')->user()->id ?? 0],
                relations: ['wishList'=>'wishList', 'compareList'=>'compareList'],
                withCount: ['reviews' => 'reviews'],
                dataLimit: 12,
                offset: 1
            );

            $productsLatest = $this->productRepo->getWebListWithScope(
                orderBy: ['id' => 'DESC'],
                scope: 'active',
                filters: ['category_id' => $product['category_id'], 'customer_id' => Auth::guard('customer')->user()->id ?? 0],
                whereNotIn: ['id' => [$product['id']]],
                relations: ['wishList'=>'wishList', 'compareList'=>'compareList'],
                dataLimit: 12,
                offset: 1
            );

            return view(VIEW_FILE_NAMES['products_details'], compact('product', 'wishlistStatus', 'countWishlist',
                'relatedProducts', 'currentDate', 'sellerVacationStartDate', 'sellerVacationEndDate', 'rattingStatus', 'productsLatest',
                'sellerTemporaryClose', 'inHouseVacationStartDate', 'inHouseVacationEndDate', 'inHouseVacationStatus', 'inHouseTemporaryClose', 'positiveReview',
                'overallRating', 'decimalPointSettings', 'moreProductFromSeller', 'productsForReview', 'productsCount', 'totalReviews', 'rating', 'productReviews',
                'avgRating', 'topRatedShops', 'newSellers', 'deliveryInfo', 'productsTopRated', 'productsThisStoreTopRated'));
        }

        Toastr::error(translate('not_found'));
        return back();
    }

    public function theme_all_purpose($slug): View|RedirectResponse
    {
        $product = Product::active()->with(['reviews', 'seller.shop'])->withCount('reviews')->where('slug', $slug)->first();
        if ($product != null) {

            $tags = ProductTag::where('product_id', $product->id)->pluck('tag_id');
            Tag::whereIn('id', $tags)->increment('visit_count');

            $current_date = date('Y-m-d H:i:s');

            $countWishlist = Wishlist::where('product_id', $product->id)->count();
            $wishlist_status = Wishlist::where(['product_id' => $product->id, 'customer_id' => auth('customer')->id()])->count();

            $relatedProducts = Product::active()->with(['reviews', 'flashDealProducts.flashDeal'])->withCount('reviews')->where('category_ids', $product->category_ids)->where('id', '!=', $product->id)->limit(12)->get();
            $relatedProducts?->map(function ($product) use ($current_date) {
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

            $seller_vacation_start_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_start_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_start_date)) : null;
            $seller_vacation_end_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_end_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_end_date)) : null;
            $seller_temporary_close = ($product->added_by == 'seller' && isset($product->seller->shop->temporary_close)) ? $product->seller->shop->temporary_close : false;

            $temporary_close = Helpers::get_business_settings('temporary_close');
            $inhouse_vacation = Helpers::get_business_settings('vacation_add');
            $inhouse_vacation_start_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_start_date'] : null;
            $inhouse_vacation_end_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_end_date'] : null;
            $inHouseVacationStatus = $product->added_by == 'admin' ? $inhouse_vacation['status'] : false;
            $inhouseTemporaryClose = $product->added_by == 'admin' ? $temporary_close['status'] : false;

            $overall_rating = getOverallRating($product->reviews);
            $product_reviews_count = $product->reviews->count();

            $ratting_status_positive = $product_reviews_count != 0 ? ($product->reviews->where('rating', '>=', 4)->count() * 100) / $product_reviews_count : 0;
            $ratting_status_good = $product_reviews_count != 0 ? ($product->reviews->where('rating', 3)->count() * 100) / $product_reviews_count : 0;
            $ratting_status_neutral = $product_reviews_count != 0 ? ($product->reviews->where('rating', 2)->count() * 100) / $product_reviews_count : 0;
            $ratting_status_negative = $product_reviews_count != 0 ? ($product->reviews->where('rating', '=', 1)->count() * 100) / $product_reviews_count : 0;
            $ratting_status = [
                'positive' => $ratting_status_positive,
                'good' => $ratting_status_good,
                'neutral' => $ratting_status_neutral,
                'negative' => $ratting_status_negative,
            ];

            $rating = getRating($product->reviews);
            $reviews_of_product = Review::where('product_id', $product->id)->latest()->paginate(2);
            $decimal_point_settings = \App\Utils\Helpers::get_business_settings('decimal_point_settings');
            $more_product_from_seller = Product::active()->withCount('reviews')->where('added_by', $product->added_by)->where('id', '!=', $product->id)->where('user_id', $product->user_id)->latest()->take(5)->get();
            $more_product_from_seller_count = Product::active()->where('added_by', $product->added_by)->where('id', '!=', $product->id)->where('user_id', $product->user_id)->count();

            if ($product->added_by == 'seller') {
                $products_for_review = Product::active()->where('added_by', $product->added_by)->where('user_id', $product->user_id)->withCount('reviews')->get();
            } else {
                $products_for_review = Product::where('added_by', 'admin')->where('user_id', $product->user_id)->withCount('reviews')->get();
            }

            $total_reviews = 0;
            foreach ($products_for_review as $item) {
                $total_reviews += $item->reviews_count;
            }

            $product_ids = Product::where(['added_by' => $product->added_by, 'user_id' => $product->user_id])->pluck('id');

            $rating_status = Review::whereIn('product_id', $product_ids);
            $rating_count = $rating_status->count();
            $avg_rating = $rating_count != 0 ? $rating_status->avg('rating') : 0;
            $rating_percentage = round(($avg_rating * 100) / 5);

            // more stores start
            $more_seller = Seller::approved()->with(['shop', 'product.reviews'])
                ->withCount(['product' => function ($query) {
                    $query->active();
                }])
                ->inRandomOrder()
                ->take(7)->get();

            $more_seller = $more_seller->map(function ($seller) {
                $review_count = 0;
                $rating = [];
                foreach ($seller->product as $product) {
                    $review_count += $product->reviews_count;
                    foreach ($product->reviews as $reviews) {
                        $rating[] = $reviews['rating'];
                    }
                }
                $seller['reviews_count'] = $review_count;
                $seller['rating'] = collect($rating)->average() ?? 0;
                return $seller;
            });
            //end more stores

            // new stores
            $new_seller = Seller::approved()->with(['shop', 'product.reviews'])
                ->withCount(['product' => function ($query) {
                    $query->active();
                }])
                ->latest()
                ->take(7)->get();

            $new_seller = $new_seller->map(function ($seller) {
                $review_count = 0;
                $rating = [];
                foreach ($seller->product as $product) {
                    $review_count += $product->reviews_count;
                    foreach ($product->reviews as $reviews) {
                        $rating[] = $reviews['rating'];
                    }
                }
                $seller['reviews_count'] = $review_count;
                $seller['rating'] = collect($rating)->average() ?? 0;
                return $seller;
            });
            //end new stores

            $delivery_info = ProductManager::get_products_delivery_charge($product, $product->minimum_order_qty);

            // top_rated products
            $products_top_rated = Product::with(['rating', 'reviews'])->active()
                ->withCount(['reviews'])->orderBy('reviews_count', 'DESC')
                ->take(12)->get();

            $products_this_store_top_rated = Product::with(['rating', 'reviews'])->active()
                ->where(['added_by' => $product->added_by, 'user_id' => $product->user_id])
                ->withCount(['reviews'])->orderBy('reviews_count', 'DESC')
                ->take(12)->get();

            $products_latest = Product::active()->with(['reviews', 'rating'])->latest()->take(12)->get();

            return view(VIEW_FILE_NAMES['products_details'], compact('product', 'wishlist_status', 'countWishlist',
                'relatedProducts', 'current_date', 'seller_vacation_start_date', 'seller_vacation_end_date', 'ratting_status', 'products_latest',
                'seller_temporary_close', 'inhouse_vacation_start_date', 'inhouse_vacation_end_date', 'inHouseVacationStatus', 'inhouseTemporaryClose',
                'overall_rating', 'decimal_point_settings', 'more_product_from_seller', 'products_for_review', 'total_reviews', 'rating', 'reviews_of_product',
                'avg_rating', 'rating_percentage', 'more_seller', 'new_seller', 'delivery_info', 'products_top_rated', 'products_this_store_top_rated', 'more_product_from_seller_count'));
        }

        Toastr::error(translate('not_found'));
        return back();
    }
}
