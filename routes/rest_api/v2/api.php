<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Old Seller Mobile APP API Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'RestAPI\v2', 'prefix' => 'v2', 'middleware' => ['api_lang']], function () {
    Route::group(['prefix' => 'seller', 'namespace' => 'seller'], function () {

        Route::get('seller-info', 'SellerController@seller_info');
        Route::get('account-delete','SellerController@account_delete');
        Route::get('seller-delivery-man', 'SellerController@seller_delivery_man');
        Route::get('shop-product-reviews', 'SellerController@shop_product_reviews');
        Route::get('shop-product-reviews-status','SellerController@shop_product_reviews_status');
        Route::put('seller-update', 'SellerController@seller_info_update');
        Route::get('monthly-earning', 'SellerController@monthly_earning');
        Route::get('monthly-commission-given', 'SellerController@monthly_commission_given');
        Route::put('cm-firebase-token', 'SellerController@update_cm_firebase_token');

        Route::get('shop-info', 'SellerController@shop_info');
        Route::get('transactions', 'SellerController@transaction');
        Route::put('shop-update', 'SellerController@shop_info_update');

        Route::post('balance-withdraw', 'SellerController@withdraw_request');
        Route::delete('close-withdraw-request', 'SellerController@close_withdraw_request');

        Route::group(['prefix' => 'brands'], function () {
            Route::get('/', 'BrandController@getBrands');
        });

        Route::group(['prefix' => 'products'], function () {
            Route::post('upload-images', 'ProductController@upload_images');
            Route::post('upload-digital-product', 'ProductController@upload_digital_product');
            Route::post('add', 'ProductController@add_new');
            Route::get('list', 'ProductController@list');
            Route::get('stock-out-list', 'ProductController@stock_out_list');
            Route::get('status-update','ProductController@status_update');
            Route::get('edit/{id}', 'ProductController@edit');
            Route::put('update/{id}', 'ProductController@update');
            Route::delete('delete/{id}', 'ProductController@delete');
            Route::get('barcode/generate', 'ProductController@barcode_generate');
        });

        Route::group(['prefix' => 'orders'], function () {
            Route::get('list', 'OrderController@list');
            Route::get('/{id}', 'OrderController@details');
            Route::put('order-detail-status/{id}', 'OrderController@order_detail_status');
            Route::put('assign-delivery-man', 'OrderController@assign_delivery_man');
            Route::put('order-wise-product-upload', 'OrderController@digital_file_upload_after_sell');
            Route::put('delivery-charge-date-update', 'OrderController@amount_date_update');

            Route::post('assign-third-party-delivery','OrderController@assign_third_party_delivery');
            Route::post('update-payment-status','OrderController@update_payment_status');
        });
        Route::group(['prefix' => 'refund'], function () {
            Route::get('list', 'RefundController@list');
            Route::get('refund-details', 'RefundController@refund_details');
            Route::post('refund-status-update', 'RefundController@refund_status_update');

        });

        Route::group(['prefix' => 'shipping'], function () {
            Route::get('get-shipping-method', 'shippingController@get_shipping_type');
            Route::get('selected-shipping-method', 'shippingController@selected_shipping_type');
            Route::get('all-category-cost','shippingController@all_category_cost');
            Route::post('set-category-cost','shippingController@set_category_cost');
        });

        Route::group(['prefix' => 'shipping-method'], function () {
            Route::get('list', 'ShippingMethodController@list');
            Route::post('add', 'ShippingMethodController@store');
            Route::get('edit/{id}', 'ShippingMethodController@edit');
            Route::put('status', 'ShippingMethodController@status_update');
            Route::put('update/{id}', 'ShippingMethodController@update');
            Route::delete('delete/{id}', 'ShippingMethodController@delete');
        });

        Route::group(['prefix' => 'messages'], function () {
            Route::get('list/{type}', 'ChatController@list');
            Route::get('get-message/{type}/{id}', 'ChatController@get_message');
            Route::post('send/{type}', 'ChatController@send_message');
            Route::get('search/{type}', 'ChatController@search');
        });

        Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
            Route::post('login', 'LoginController@login');

            Route::post('forgot-password', 'ForgotPasswordController@reset_password_request');
            Route::post('verify-otp', 'ForgotPasswordController@otp_verification_submit');
            Route::put('reset-password', 'ForgotPasswordController@reset_password_submit');
        });

        Route::group(['prefix' => 'registration', 'namespace' => 'auth'], function () {
            Route::post('/', 'RegisterController@store');
        });
    });
    Route::post('ls-lib-update', 'LsLibController@lib_update');

    Route::group(['prefix' => 'delivery-man', 'namespace' => 'delivery_man'], function () {

        Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
            Route::post('login', 'LoginController@login');
            Route::post('forgot-password', 'LoginController@reset_password_request');
            Route::post('verify-otp', 'LoginController@otp_verification_submit');
            Route::post('reset-password', 'LoginController@reset_password_submit');
        });

        Route::group(['middleware' => ['delivery_man_auth']], function () {
            Route::put('language-change', 'DeliveryManController@language_change');
            Route::put('is-online', 'DeliveryManController@is_online');
            Route::get('info', 'DeliveryManController@info');
            Route::post('distance-api', 'DeliveryManController@distance_api');
            Route::get('current-orders', 'DeliveryManController@get_current_orders');
            Route::get('all-orders', 'DeliveryManController@get_all_orders');
            Route::post('record-location-data', 'DeliveryManController@record_location_data');
            Route::get('order-delivery-history', 'DeliveryManController@get_order_history');
            Route::put('update-order-status', 'DeliveryManController@update_order_status');
            Route::put('update-expected-delivery', 'DeliveryManController@update_expected_delivery');
            Route::put('update-payment-status', 'DeliveryManController@order_payment_status_update');
            Route::put('order-update-is-pause', 'DeliveryManController@order_update_is_pause');
            Route::get('order-details', 'DeliveryManController@get_order_details');
            Route::get('last-location', 'DeliveryManController@get_last_location');
            Route::put('update-fcm-token', 'DeliveryManController@update_fcm_token');

            Route::get('delivery-wise-earned', 'DeliveryManController@delivery_wise_earned');
            Route::get('order-list-by-date', 'DeliveryManController@order_list_date_filter');
            Route::get('search', 'DeliveryManController@search');
            Route::get('profile-dashboard-counts', 'DeliveryManController@profile_dashboard_counts');
            Route::post('change-status', 'DeliveryManController@change_status');
            Route::put('update-info', 'DeliveryManController@update_info');
            Route::put('bank-info', 'DeliveryManController@bank_info');
            Route::get('review-list', 'DeliveryManController@review_list');
            Route::put('save-review', 'DeliveryManController@is_saved');
            Route::get('collected_cash_history', 'DeliveryManController@collected_cash_history');
            Route::get('emergency-contact-list', 'DeliveryManController@emergency_contact_list');
            Route::get('notifications', 'DeliveryManController@get_all_notification');
            Route::post('verify-order-delivery-otp', 'DeliveryManController@verify_order_delivery_otp');
            Route::post('resend-verification-code', 'DeliveryManController@resend_verification_code');
            Route::post('order-delivery-verification', 'DeliveryManController@order_delivery_verification');

            Route::post('withdraw-request', 'WithdrawController@withdraw_request');
            Route::get('withdraw-list-by-approved', 'WithdrawController@withdraw_list_by_approved');


            Route::group(['prefix' => 'messages'], function (){
                Route::get('list/{type}', 'ChatController@list');
                Route::get('get-message/{type}/{id}', 'ChatController@get_message');
                Route::post('send-message/{type}', 'ChatController@send_message');
                Route::get('search/{type}', 'ChatController@search');
            });
        });

    });
});

