<?php

use App\Http\Controllers\RestAPI\v1\BrandController;
use App\Http\Controllers\RestAPI\v1\CartController;
use App\Http\Controllers\RestAPI\v1\CategoryController;
use App\Http\Controllers\RestAPI\v1\DealController;
use App\Http\Controllers\RestAPI\v1\FlashDealController;
use App\Http\Controllers\RestAPI\v1\OrderController;
use App\Http\Controllers\RestAPI\v1\ProductController;
use App\Http\Controllers\RestAPI\v1\SellerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['namespace' => 'RestAPI\v1', 'prefix' => 'v1', 'middleware' => ['api_lang']], function () {

    Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
        Route::post('register', 'PassportAuthController@register');
        Route::post('login', 'PassportAuthController@login');
        Route::get('logout', 'PassportAuthController@logout')->middleware('auth:api');

        Route::post('check-phone', 'PhoneVerificationController@check_phone');
        Route::post('resend-otp-check-phone', 'PhoneVerificationController@resend_otp_check_phone');
        Route::post('verify-phone', 'PhoneVerificationController@verify_phone');

        Route::post('check-email', 'EmailVerificationController@check_email');
        Route::post('resend-otp-check-email', 'EmailVerificationController@resend_otp_check_email');
        Route::post('verify-email', 'EmailVerificationController@verify_email');

        Route::post('forgot-password', 'ForgotPassword@reset_password_request');
        Route::post('verify-otp', 'ForgotPassword@otp_verification_submit');
        Route::put('reset-password', 'ForgotPassword@reset_password_submit');

        Route::post('social-login', 'SocialAuthController@social_login');
        Route::post('update-phone', 'SocialAuthController@update_phone');
    });

    Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@configuration');
    });

    Route::group(['prefix' => 'shipping-method', 'middleware' => 'apiGuestCheck'], function () {
        Route::get('detail/{id}', 'ShippingMethodController@get_shipping_method_info');
        Route::get('by-seller/{id}/{seller_is}', 'ShippingMethodController@shipping_methods_by_seller');
        Route::post('choose-for-order', 'ShippingMethodController@choose_for_order');
        Route::get('chosen', 'ShippingMethodController@chosen_shipping_methods');

        Route::get('check-shipping-type', 'ShippingMethodController@check_shipping_type');
    });

    Route::group(['prefix' => 'cart', 'middleware' => 'apiGuestCheck'], function () {
        Route::controller(CartController::class)->group(function () {
            Route::get('/', 'cart');
            Route::post('add', 'add_to_cart');
            Route::put('update', 'update_cart');
            Route::delete('remove', 'remove_from_cart');
            Route::delete('remove-all', 'remove_all_from_cart');
            Route::post('select-cart-items', 'updateCheckedCartItems');
        });

    });

    Route::group(['prefix' => 'customer/order', 'middleware' => 'apiGuestCheck'], function () {
        Route::get('get-order-by-id', 'CustomerController@get_order_by_id');
    });

    Route::get('faq', 'GeneralController@faq');

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', 'NotificationController@list');
        Route::get('/seen', 'NotificationController@notification_seen')->middleware('auth:api');
    });

    Route::group(['prefix' => 'attributes'], function () {
        Route::get('/', 'AttributeController@get_attributes');
    });

    Route::group(['prefix' => 'flash-deals'], function () {
        Route::controller(FlashDealController::class)->group(function () {
            Route::get('/', 'getFlashDeal');
            Route::get('products/{deal_id}', 'getFlashDealProducts');
        });
    });

    Route::group(['prefix' => 'deals'], function () {
        Route::controller(DealController::class)->group(function () {
            Route::get('featured', 'getFeaturedDealProducts');
        });
    });

    Route::group(['prefix' => 'dealsoftheday'], function () {
        Route::get('deal-of-the-day', 'DealOfTheDayController@get_deal_of_the_day_product');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::controller(ProductController::class)->group(function () {
            Route::get('reviews/{product_id}', 'get_product_reviews');
            Route::get('rating/{product_id}', 'get_product_rating');
            Route::get('counter/{product_id}', 'counter');
            Route::get('shipping-methods', 'get_shipping_methods');
            Route::get('social-share-link/{product_id}', 'social_share_link');
            Route::post('reviews/submit', 'submit_product_review')->middleware('auth:api');
            Route::put('review/update', 'updateProductReview')->middleware('auth:api');
            Route::get('review/{product_id}/{order_id}', 'getProductReviewByOrder')->middleware('auth:api');
            Route::delete('review/delete-image', 'deleteReviewImage')->middleware('auth:api');
        });
    });

    Route::group(['middleware' => 'apiGuestCheck'], function () {
        Route::group(['prefix' => 'products'], function () {
            Route::controller(ProductController::class)->group(function () {
                Route::get('latest', 'get_latest_products');
                Route::get('new-arrival', 'getNewArrivalProducts');
                Route::get('featured', 'getFeaturedProductsList');
                Route::get('top-rated', 'getTopRatedProducts');
                Route::any('search', 'get_searched_products');
                Route::post('filter', 'product_filter');
                Route::any('suggestion-product', 'get_suggestion_product');
                Route::get('details/{slug}', 'get_product');
                Route::get('related-products/{product_id}', 'get_related_products');
                Route::get('best-sellings', 'getBestSellingProducts');
                Route::get('home-categories', 'get_home_categories');
                Route::get('discounted-product', 'get_discounted_product');
                Route::get('most-demanded-product', 'get_most_demanded_product');
                Route::get('shop-again-product', 'get_shop_again_product')->middleware('auth:api');
                Route::get('just-for-you', 'just_for_you');
                Route::get('most-searching', 'getMostSearchingProductsList');
            });
        });

        Route::group(['prefix' => 'seller'], function () {
            Route::get('{seller_id}/products', 'SellerController@get_seller_products');
            Route::get('{seller_id}/seller-best-selling-products', 'SellerController@get_seller_best_selling_products');
            Route::get('{seller_id}/seller-featured-product', 'SellerController@get_sellers_featured_product');
            Route::get('{seller_id}/seller-recommended-products', 'SellerController@get_sellers_recommended_products');
        });

        Route::group(['prefix' => 'categories'], function () {
            Route::controller(CategoryController::class)->group(function () {
                Route::get('/', 'get_categories');
                Route::get('products/{category_id}', 'get_products');
                Route::get('/find-what-you-need', 'find_what_you_need');
            });
        });

        Route::group(['prefix' => 'brands'], function () {
            Route::controller(BrandController::class)->group(function () {
                Route::get('/', 'get_brands');
                Route::get('products/{brand_id}', 'get_products');
            });
        });

        Route::group(['prefix' => 'customer'], function () {
            Route::put('cm-firebase-token', 'CustomerController@update_cm_firebase_token');

            Route::get('get-restricted-country-list', 'CustomerController@get_restricted_country_list');
            Route::get('get-restricted-zip-list', 'CustomerController@get_restricted_zip_list');

            Route::group(['prefix' => 'address'], function () {
                Route::post('add', 'CustomerController@add_new_address');
                Route::get('list', 'CustomerController@address_list');
                Route::delete('/', 'CustomerController@delete_address');
            });

            Route::group(['prefix' => 'order'], function () {
                Route::controller(OrderController::class)->group(function () {
                    Route::get('place', 'place_order');
                    Route::get('offline-payment-method-list', 'offline_payment_method_list');
                    Route::post('place-by-offline-payment', 'placeOrderByOfflinePayment');
                });
                Route::get('details', 'CustomerController@get_order_details');
            });
        });
    });

    Route::group(['prefix' => 'customer', 'middleware' => 'auth:api'], function () {
        Route::get('info', 'CustomerController@info');
        Route::put('update-profile', 'CustomerController@update_profile');
        Route::get('account-delete/{id}', 'CustomerController@account_delete');

        Route::group(['prefix' => 'address'], function () {
            Route::get('get/{id}', 'CustomerController@get_address');
            Route::post('update', 'CustomerController@update_address');
        });

        Route::group(['prefix' => 'support-ticket'], function () {
            Route::post('create', 'CustomerController@create_support_ticket');
            Route::get('get', 'CustomerController@get_support_tickets');
            Route::get('conv/{ticket_id}', 'CustomerController@get_support_ticket_conv');
            Route::post('reply/{ticket_id}', 'CustomerController@reply_support_ticket');
            Route::get('close/{id}', 'CustomerController@support_ticket_close');
        });

        Route::group(['prefix' => 'compare'], function () {
            Route::get('list', 'CompareController@list');
            Route::post('product-store', 'CompareController@compare_product_store');
            Route::delete('clear-all', 'CompareController@clear_all');
            Route::get('product-replace', 'CompareController@compare_product_replace');
        });

        Route::group(['prefix' => 'wish-list'], function () {
            Route::get('/', 'CustomerController@wish_list');
            Route::post('add', 'CustomerController@add_to_wishlist');
            Route::delete('remove', 'CustomerController@remove_from_wishlist');
        });

        Route::group(['prefix' => 'order'], function () {
            Route::get('place-by-wallet', 'OrderController@place_order_by_wallet');
            Route::get('refund', 'OrderController@refund_request');
            Route::post('refund-store', 'OrderController@store_refund');
            Route::get('refund-details', 'OrderController@refund_details');
            Route::get('list', 'CustomerController@get_order_list');
            Route::post('again', 'OrderController@order_again');

            Route::controller(ProductController::class)->group(function () {
                Route::post('deliveryman-reviews/submit', 'submit_deliveryman_review')->middleware('auth:api');
            });
        });

        // Chatting
        Route::group(['prefix' => 'chat'], function () {
            Route::get('list/{type}', 'ChatController@list');
            Route::get('get-messages/{type}/{id}', 'ChatController@get_message');
            Route::post('send-message/{type}', 'ChatController@send_message');
            Route::post('seen-message/{type}', 'ChatController@seen_message');
            Route::get('search/{type}', 'ChatController@search');
        });

        //wallet
        Route::group(['prefix' => 'wallet'], function () {
            Route::get('list', 'UserWalletController@list');
            Route::get('bonus-list', 'UserWalletController@bonus_list');
        });
        //loyalty
        Route::group(['prefix' => 'loyalty'], function () {
            Route::get('list', 'UserLoyaltyController@list');
            Route::post('loyalty-exchange-currency', 'UserLoyaltyController@loyalty_exchange_currency');
        });
    });

    Route::group(['prefix' => 'customer', 'middleware' => 'apiGuestCheck'], function () {
        Route::group(['prefix' => 'order'], function () {
            Route::get('digital-product-download/{id}', 'OrderController@digital_product_download');
            Route::get('digital-product-download-otp-verify', 'OrderController@digital_product_download_otp_verify');
            Route::post('digital-product-download-otp-resend', 'OrderController@digital_product_download_otp_resend');
        });
    });

    Route::group(['prefix' => 'digital-payment', 'middleware' => 'apiGuestCheck'], function () {
        Route::post('/', [PaymentController::class, 'payment']);
    });

    Route::group(['prefix' => 'add-to-fund', 'middleware' => 'auth:api'], function () {
        Route::post('/', [PaymentController::class, 'customer_add_to_fund_request']);
    });

    Route::group(['prefix' => 'order'], function () {
        Route::get('track', 'OrderController@track_by_order_id');
        Route::get('cancel-order', 'OrderController@order_cancel');
        Route::post('track-order', 'OrderController@track_order');
    });

    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', 'BannerController@get_banners');
    });

    Route::group(['prefix' => 'seller'], function () {
        Route::controller(SellerController::class)->group(function (){
            Route::get('/', 'get_seller_info');
            Route::get('list/{type}', 'getSellerList');
            Route::get('more', 'more_sellers');
        });
    });

    Route::group(['prefix' => 'coupon', 'middleware' => 'auth:api'], function () {
        Route::get('apply', 'CouponController@apply');
    });
    Route::get('coupon/list', 'CouponController@list')->middleware('auth:api');
    Route::get('coupon/applicable-list', 'CouponController@applicable_list')->middleware('auth:api');
    Route::get('coupons/{seller_id}/seller-wise-coupons', 'CouponController@get_seller_wise_coupon');

    Route::get('get-guest-id', 'GeneralController@get_guest_id');

    //map api
    Route::group(['prefix' => 'mapapi'], function () {
        Route::get('place-api-autocomplete', 'MapApiController@place_api_autocomplete');
        Route::get('distance-api', 'MapApiController@distance_api');
        Route::get('place-api-details', 'MapApiController@place_api_details');
        Route::get('geocode-api', 'MapApiController@geocode_api');
    });

    Route::post('contact-us', 'GeneralController@contact_store');
    Route::put('customer/language-change', 'CustomerController@language_change')->middleware('auth:api');
});
