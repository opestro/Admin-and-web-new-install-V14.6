<?php

use App\Enums\ViewPaths\Vendor\Auth;
use App\Enums\ViewPaths\Vendor\Cart;
use App\Enums\ViewPaths\Vendor\CategoryShippingCost;
use App\Enums\ViewPaths\Vendor\Chatting;
use App\Enums\ViewPaths\Vendor\Coupon;
use App\Enums\ViewPaths\Vendor\Customer;
use App\Enums\ViewPaths\Vendor\Dashboard;
use App\Enums\ViewPaths\Vendor\DeliveryMan;
use App\Enums\ViewPaths\Vendor\DeliveryManWallet;
use App\Enums\ViewPaths\Vendor\DeliveryManWithdraw;
use App\Enums\ViewPaths\Vendor\EmergencyContact;
use App\Enums\ViewPaths\Vendor\ForgotPassword;
use App\Enums\ViewPaths\Vendor\Notification;
use App\Enums\ViewPaths\Vendor\POS;
use App\Enums\ViewPaths\Vendor\POSOrder;
use App\Enums\ViewPaths\Vendor\Product;
use App\Enums\ViewPaths\Vendor\Profile;
use App\Enums\ViewPaths\Vendor\Refund;
use App\Enums\ViewPaths\Vendor\Review;
use App\Enums\ViewPaths\Vendor\ShippingMethod;
use App\Enums\ViewPaths\Vendor\ShippingType;
use App\Enums\ViewPaths\Vendor\Shop;
use App\Enums\ViewPaths\Vendor\Withdraw;
use App\Http\Controllers\Vendor\Auth\ForgotPasswordController;
use App\Http\Controllers\Vendor\Auth\LoginController;
use App\Enums\ViewPaths\Vendor\Order;
use App\Http\Controllers\Vendor\Auth\RegisterController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\ChattingController;
use App\Http\Controllers\Vendor\Coupon\CouponController;
use App\Http\Controllers\Vendor\CustomerController;
use App\Http\Controllers\Vendor\DeliveryMan\DeliveryManController;
use App\Http\Controllers\Vendor\DeliveryMan\DeliveryManWalletController;
use App\Http\Controllers\Vendor\DeliveryMan\DeliveryManWithdrawController;
use App\Http\Controllers\Vendor\DeliveryMan\EmergencyContactController;
use App\Http\Controllers\Vendor\NotificationController;
use App\Http\Controllers\Vendor\POS\CartController;
use App\Http\Controllers\Vendor\POS\POSController;
use App\Http\Controllers\Vendor\POS\POSOrderController;
use App\Http\Controllers\Vendor\Product\ProductController;
use App\Http\Controllers\Vendor\ProfileController;
use App\Http\Controllers\Vendor\RefundController;
use App\Http\Controllers\Vendor\ReviewController;
use App\Http\Controllers\Vendor\Shipping\CategoryShippingCostController;
use App\Http\Controllers\Vendor\Shipping\ShippingMethodController;
use App\Http\Controllers\Vendor\Shipping\ShippingTypeController;
use App\Http\Controllers\Vendor\ShopController;
use App\Http\Controllers\Vendor\SystemController;
use App\Http\Controllers\Vendor\WithdrawController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\Order\OrderController;
use App\Http\Controllers\Vendor\TransactionReportController;
use App\Http\Controllers\Vendor\ProductReportController;
use App\Http\Controllers\Vendor\OrderReportController;

Route::group(['prefix' => 'vendor', 'as' => 'vendor.'], function () {
    /* authentication */
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::controller(LoginController::class)->group(function () {
            Route::get(Auth::VENDOR_LOGIN[URI],'getLoginView');
            Route::get(Auth::RECAPTURE[URI].'/{tmp}', 'generateReCaptcha')->name('recaptcha');
            Route::post(Auth::VENDOR_LOGIN[URI], 'login')->name('login');
            Route::get(Auth::VENDOR_LOGOUT[URI], 'logout')->name('logout');
        });
        Route::group(['prefix' => 'forgot-password', 'as' => 'forgot-password.'], function () {
            Route::controller(ForgotPasswordController::class)->group(function () {
                Route::get(ForgotPassword::INDEX[URI],'index')->name('index');
                Route::post(ForgotPassword::INDEX[URI],'getPasswordResetRequest');
                Route::get(ForgotPassword::OTP_VERIFICATION[URI],'getOTPVerificationView')->name('otp-verification');
                Route::post(ForgotPassword::OTP_VERIFICATION[URI],'submitOTPVerificationCode');
                Route::get(ForgotPassword::RESET_PASSWORD[URI],'getPasswordResetView')->name('reset-password');
                Route::post(ForgotPassword::RESET_PASSWORD[URI],'resetPassword');
            });
        });
        Route::group(['prefix' => 'registration', 'as' => 'registration.'], function () {
            Route::controller(RegisterController::class)->group(function () {
                Route::get(Auth::VENDOR_REGISTRATION[URI],'index')->name('index');
                Route::post(Auth::VENDOR_REGISTRATION[URI],'add');
            });
        });
    });
    /* end authentication */
    Route::group(['middleware' => ['seller']], function () {
        Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
            Route::controller(DashboardController::class)->group(function () {
                Route::get(Dashboard::INDEX[URI], 'index')->name('index');
                Route::get(Dashboard::ORDER_STATUS[URI] . '/{type}', 'getOrderStatus')->name('order-status');
                Route::get(Dashboard::EARNING_STATISTICS[URI], 'getEarningStatistics')->name('earning-statistics');
                Route::post(Dashboard::WITHDRAW_REQUEST[URI], 'getWithdrawRequest')->name('withdraw-request');
                Route::get(Dashboard::WITHDRAW_REQUEST[URI], 'getMethodList')->name('method-list');
            });
        });
        Route::group(['prefix' => 'pos', 'as' => 'pos.'], function () {
            Route::controller(POSController::class)->group(function () {
                Route::get(POS::INDEX[URI], 'index')->name('index');
                Route::any(POS::CHANGE_CUSTOMER[URI], 'changeCustomer')->name('change-customer');
                Route::post(POS::UPDATE_DISCOUNT[URI], 'updateDiscount')->name('update-discount');
                Route::post(POS::COUPON_DISCOUNT[URI], 'getCouponDiscount')->name('coupon-discount');
                Route::get(POS::QUICK_VIEW[URI], 'getQuickView')->name('quick-view');
                Route::get(POS::SEARCH[URI], 'getSearchedProductsView')->name('search-product');
            });
            Route::controller(CartController::class)->group(function () {
                Route::post(Cart::VARIANT[URI], 'getVariantPrice')->name('get-variant-price');
                Route::post(Cart::QUANTITY_UPDATE[URI], 'updateQuantity')->name('quantity-update');
                Route::get(Cart::GET_CART_IDS[URI], 'getCartIds')->name('get-cart-ids');
                Route::get(Cart::CLEAR_CART_IDS[URI], 'clearSessionCartIds')->name('clear-cart-ids');
                Route::post(Cart::ADD[URI], 'addToCart')->name('add-to-cart');
                Route::post(Cart::REMOVE[URI], 'removeCart')->name('cart-remove');
                Route::any(Cart::CART_EMPTY[URI],'emptyCart')->name('cart-empty');
                Route::any(Cart::CHANGE_CART[URI], 'changeCart')->name('change-cart');
                Route::get(Cart::NEW_CART_ID[URI], 'addNewCartId')->name('new-cart-id');
            });
            Route::controller(POSOrderController::class)->group(function () {
                Route::post(POSOrder::ORDER_DETAILS[URI].'/{id}', 'index')->name('order-details');
                Route::post(POSOrder::ORDER_PLACE[URI], 'placeOrder')->name('order-place');
                Route::any(POSOrder::CANCEL_ORDER[URI],'cancelOrder')->name('cancel-order');
                Route::any(POSOrder::HOLD_ORDERS[URI],'getAllHoldOrdersView')->name('view-hold-orders');
            });
        });
        Route::group(['prefix' => 'refund', 'as' => 'refund.'], function () {
            Route::controller(RefundController::class)->group(function () {
                Route::get(Refund::INDEX[URI] . '/{status}', 'index')->name('index');
                Route::get(Refund::DETAILS[URI] . '/{id}', 'getDetailsView')->name('details');
                Route::post(Refund::UPDATE_STATUS[URI], 'updateStatus')->name('update-status');
                Route::get(Refund::EXPORT[URI].'/{status}', 'exportList')->name('export');
            });
        });
        /* product */
        Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
            Route::controller(ProductController::class)->group(function () {
                Route::get(Product::LIST[URI]. '/{type}', 'index')->name('list');
                Route::get(Product::ADD[URI], 'getAddView')->name('add');
                Route::post(Product::ADD[URI], 'add');
                Route::get(Product::GET_CATEGORIES[URI], 'getCategories')->name('get-categories');
                Route::post(Product::SKU_COMBINATION[URI], 'getSkuCombinationView')->name('sku-combination');
                Route::post(Product::UPDATE_STATUS[URI], 'updateStatus')->name('status-update');
                Route::get(Product::EXPORT_EXCEL[URI]. '/{type}', 'exportList')->name('export-excel');
                Route::get(Product::VIEW[URI] . '/{id}', 'getView')->name('view');
                Route::get(Product::BARCODE_VIEW[URI] . '/{id}', 'getBarcodeView')->name('barcode');
                Route::delete(Product::DELETE[URI] . '/{id}', 'delete')->name('delete');
                Route::get(Product::STOCK_LIMIT[URI], 'getStockLimitListView')->name('stock-limit-list');
                Route::post(Product::UPDATE_QUANTITY[URI], 'updateQuantity')->name('update-quantity');
                Route::get(Product::UPDATE[URI] . '/{id}', 'getUpdateView')->name('update');
                Route::post(Product::UPDATE[URI] . '/{id}', 'update');
                Route::get(Product::DELETE_IMAGE[URI], 'deleteImage')->name('delete-image');
                Route::get(Product::GET_VARIATIONS[URI], 'getVariations')->name('get-variations');
                Route::get(Product::BULK_IMPORT[URI], 'getBulkImportView')->name('bulk-import');
                Route::post(Product::BULK_IMPORT[URI], 'importBulkProduct');
                Route::get(Product::SEARCH[URI], 'getSearchedProductsView')->name('search-product');
                Route::get(Product::PRODUCT_GALLERY[URI], 'getProductGalleryView')->name('product-gallery');
                Route::get(Product::STOCK_LIMIT_STATUS[URI], 'getStockLimitStatus')->name('stock-limit-status');
            });
        });
        Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
            Route::controller(OrderController::class)->group(function () {
                Route::get(Order::LIST[URI] . '/{status}', 'index')->name('list');
                Route::get(Order::CUSTOMERS[URI], 'getCustomers')->name('customers');
                Route::get(Order::EXPORT_EXCEL[URI].'/{status}', 'exportList')->name('export-excel');
                Route::get(Order::GENERATE_INVOICE[URI].'/{id}', 'generateInvoice')->name('generate-invoice');
                Route::get(Order::VIEW[URI].'/{id}', 'getView')->name('details');
                Route::post(Order::UPDATE_ADDRESS[URI], 'updateAddress')->name('address-update');// update address from order details
                Route::post(Order::PAYMENT_STATUS[URI], 'updatePaymentStatus')->name('payment-status');
                Route::post(Order::UPDATE_DELIVERY_INFO[URI],'updateDeliverInfo')->name('update-deliver-info');
                Route::get(Order::ADD_DELIVERY_MAN[URI].'/{order_id}/{d_man_id}', 'addDeliveryMan')->name('add-delivery-man');
                Route::post(Order::UPDATE_AMOUNT_DATE[URI], 'updateAmountDate')->name('amount-date-update');
                Route::post(Order::DIGITAL_FILE_UPLOAD_AFTER_SELL[URI], 'uploadDigitalFileAfterSell')->name('digital-file-upload-after-sell');
                Route::post(Order::UPDATE_STATUS[URI], 'updateStatus')->name('status');

            });
        }); //end order

        Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
            Route::controller(CustomerController::class)->group(function () {
                Route::get(Customer::LIST[URI],'getList')->name('list');
                Route::post(Customer::ADD[URI],'add')->name('add');
            });
        });

        Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
            Route::controller(ReviewController::class)->group(function () {
                Route::get(Review::INDEX[URI], 'index')->name('index');
                Route::get(Review::UPDATE_STATUS[URI] . '/{id}/{status}', 'updateStatus')->name('update-status');
                Route::get(Review::EXPORT[URI], 'exportList')->name('export')->middleware('actch')->middleware('actch');
            });
        });

        Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
            Route::controller(CouponController::class)->group(function () {
                Route::get(Coupon::INDEX[URI], 'index')->name('index')->middleware('actch');
                Route::post(Coupon::ADD[URI], 'add')->name('add');
                Route::get(Coupon::UPDATE[URI] . '/{id}', 'getUpdateView')->name('update')->middleware('actch');
                Route::post(Coupon::UPDATE[URI] . '/{id}', 'update');
                Route::get(Coupon::UPDATE_STATUS[URI] . '/{id}/{status}', 'updateStatus')->name('update-status');
                Route::delete(Coupon::DELETE[URI] . '/{id}', 'delete')->name('delete');
                Route::get(Coupon::QUICK_VIEW[URI], 'getQuickView')->name('quick-view');
                Route::get(Coupon::EXPORT[URI], 'exportList')->name('export')->middleware('actch');

            });
        });
        Route::group(['prefix' => 'messages', 'as' => 'messages.'], function () {
            Route::controller(ChattingController::class)->group(function () {
                Route::get(Chatting::INDEX[URI] . '/{type}', 'index')->name('index');
                Route::get(Chatting::MESSAGE[URI], 'getMessageByUser')->name('message');
                Route::post(Chatting::MESSAGE[URI], 'addVendorMessage');
                Route::get(Chatting::NEW_NOTIFICATION[URI], 'getNewNotification')->name('new-notification');
            });
        });
        Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
            Route::post(Notification::INDEX[URI], [NotificationController::class, 'getNotificationModalView'])->name('index');
        });
        /* deliveryMan */
        Route::group(['prefix' => 'delivery-man', 'as' => 'delivery-man.'], function () {
            Route::controller(DeliveryManController::class)->group(function () {
                Route::get(DeliveryMan::INDEX[URI], 'index')->name('index');
                Route::post(DeliveryMan::INDEX[URI], 'add');
                Route::get(DeliveryMan::LIST[URI], 'getListView')->name('list');
                Route::get(DeliveryMan::EXPORT[URI], 'exportList')->name('export');
                Route::get(DeliveryMan::UPDATE[URI] . '/{id}', 'getUpdateView')->name('update');
                Route::post(DeliveryMan::UPDATE[URI] . '/{id}', 'update');
                Route::post(DeliveryMan::UPDATE_STATUS[URI] . '/{id}', 'updateStatus')->name('update-status');
                Route::delete(DeliveryMan::DELETE[URI] . '/{id}', 'delete')->name('delete');
                Route::get(DeliveryMan::RATING[URI] . '/{id}', 'getRatingView')->name('rating');
            });
            Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function () {
                Route::controller(DeliveryManWalletController::class)->group(function () {
                    Route::get(DeliveryManWallet::INDEX[URI].'/{id}', 'index')->name('index');
                    Route::get(DeliveryManWallet::ORDER_HISTORY[URI].'/{id}','getOrderHistory')->name('order-history');
                    Route::get(DeliveryManWallet::ORDER_STATUS_HISTORY[URI].'/{order}','getOrderStatusHistory')->name('order-status-history');
                    Route::get(DeliveryManWallet::EARNING[URI].'/{id}','getEarningListView')->name('earning');
                    Route::get(DeliveryManWallet::CASH_COLLECT[URI].'/{id}', 'getCashCollectView')->name('cash-collect');
                    Route::post(DeliveryManWallet::CASH_COLLECT[URI].'/{id}', 'collectCash');
                });
            });

            Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.'], function () {
                Route::controller(DeliveryManWithdrawController::class)->group(function () {
                    Route::get(DeliveryManWithdraw::INDEX[URI],'index')->name('index');
                    Route::post(DeliveryManWithdraw::INDEX[URI],'getFiltered');
                    Route::get(DeliveryManWithdraw::DETAILS[URI].'/{withdrawId}','getDetails')->name('details');
                    Route::post(DeliveryManWithdraw::UPDATE_STATUS[URI].'/{withdrawId}', 'updateStatus')->name('update-status');
                    Route::any(DeliveryManWithdraw::EXPORT[URI],'exportList')->name('export');
                });
            });
            Route::group(['prefix' => 'emergency-contact', 'as' => 'emergency-contact.'], function () {
                Route::controller(EmergencyContactController::class)->group(function () {
                    Route::get(EmergencyContact::INDEX[URI], 'index')->name('index');
                    Route::post(EmergencyContact::INDEX[URI], 'add');
                    Route::get(EmergencyContact::UPDATE[URI].'/{id}', 'getUpdateView')->name('update');
                    Route::post(EmergencyContact::UPDATE[URI].'/{id}', 'update');
                    Route::patch(EmergencyContact::INDEX[URI], 'updateStatus');
                    Route::delete(EmergencyContact::INDEX[URI], 'delete');
                });
            });
        });

        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::controller(ProfileController::class)->group(function () {
                Route::get(Profile::INDEX[URI], 'index')->name('index');
                Route::get(Profile::UPDATE[URI] . '/{id}', 'getUpdateView')->name('update');
                Route::post(Profile::UPDATE[URI] . '/{id}', 'update');
                Route::patch(Profile::UPDATE[URI] . '/{id}', 'updatePassword');
                Route::get(Profile::BANK_INFO_UPDATE[URI] . '/{id}', 'getBankInfoUpdateView')->name('update-bank-info');
                Route::post(Profile::BANK_INFO_UPDATE[URI] . '/{id}', 'updateBankInfo');
            });
        });

        Route::group(['prefix' => 'shop', 'as' => 'shop.'], function () {
            Route::controller(ShopController::class)->group(function () {
                Route::get(Shop::INDEX[URI], 'index')->name('index');
                Route::get(Shop::UPDATE[URI] . '/{id}', 'getUpdateView')->name('update');
                Route::post(Shop::UPDATE[URI] . '/{id}', 'update');
                Route::POST(Shop::VACATION[URI] . '/{id}', 'updateVacation')->name('update-vacation');
                Route::POST(Shop::TEMPORARY_CLOSE[URI] . '/{id}', 'closeShopTemporary')->name('close-shop-temporary');
                Route::POST(Shop::ORDER_SETTINGS[URI] . '/{id}', 'updateOrderSettings')->name('update-order-settings');
            });
        });
        /*business setting */
        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
            Route::group(['prefix' => 'shipping-method', 'as' => 'shipping-method.'], function () {
                Route::controller(ShippingMethodController::class)->group(function () {
                    Route::get(ShippingMethod::INDEX[URI], 'index')->name('index');
                    Route::post(ShippingMethod::INDEX[URI], 'add');
                    Route::get(ShippingMethod::UPDATE[URI] . '/{id}', 'getUpdateView')->name('update');
                    Route::post(ShippingMethod::UPDATE[URI] . '/{id}', 'update');
                    Route::post(ShippingMethod::UPDATE_STATUS[URI], 'updateStatus')->name('update-status');
                    Route::post(ShippingMethod::DELETE[URI], 'delete')->name('delete');
                });
            });

            Route::group(['prefix' => 'shipping-type', 'as' => 'shipping-type.'], function () {
                Route::post(ShippingType::INDEX[URI], [ShippingTypeController::class, 'addOrUpdate'])->name('index');
            });
            Route::group(['prefix' => 'category-wise-shipping-cost', 'as' => 'category-wise-shipping-cost.'], function () {
                Route::post(CategoryShippingCost::INDEX[URI], [CategoryShippingCostController::class, 'index'])->name('index');
            });
            Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.'], function () {
                Route::controller(WithdrawController::class)->group(function () {
                    Route::get(Withdraw::INDEX[URI], 'index')->name('index');
                    Route::post(Withdraw::INDEX[URI], 'getListByStatus');
                    Route::get(Withdraw::CLOSE_REQUEST[URI] . '/{id}', 'closeWithdrawRequest')->name('close');
                    Route::get(Withdraw::EXPORT[URI], 'exportList')->name('export-withdraw-list');
                });
            });
        });
        /*business setting */

        Route::controller(SystemController::class)->group(function () {
            Route::get('/get-order-data', 'getOrderData')->name('get-order-data');
        });

        Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
            Route::controller(ProductReportController::class)->group(function (){
                Route::get('all-product', 'all_product')->name('all-product');
                Route::get('all-product-excel', 'allProductExportExcel')->name('all-product-excel');

                Route::get('stock-product-report', 'stock_product_report')->name('stock-product-report');
                Route::get('product-stock-export', 'productStockExport')->name('product-stock-export');
            });

            Route::controller(OrderReportController::class)->group(function (){
                Route::get('order-report', 'order_report')->name('order-report');
                Route::get('order-report-excel', 'orderReportExportExcel')->name('order-report-excel');
                Route::get('order-report-pdf', 'exportOrderReportInPDF')->name('order-report-pdf');
            });

            Route::any('set-date', 'App\Http\Controllers\Vendor\ReportController@set_date')->name('set-date');
        });

        Route::group(['prefix' => 'transaction', 'as' => 'transaction.'], function () {
            Route::controller(TransactionReportController::class)->group(function () {
                Route::get('order-list', 'order_transaction_list')->name('order-list');
                Route::get('pdf-order-wise-transaction', 'pdf_order_wise_transaction')->name('pdf-order-wise-transaction');
                Route::get('order-transaction-export-excel', 'orderTransactionExportExcel')->name('order-transaction-export-excel');
                Route::get('order-transaction-summary-pdf', 'order_transaction_summary_pdf')->name('order-transaction-summary-pdf');
                Route::get('expense-list', 'expense_transaction_list')->name('expense-list');
                Route::get('pdf-order-wise-expense-transaction', 'pdf_order_wise_expense_transaction')->name('pdf-order-wise-expense-transaction');
                Route::get('expense-transaction-summary-pdf', 'expense_transaction_summary_pdf')->name('expense-transaction-summary-pdf');
                Route::get('expense-transaction-export-excel', 'expenseTransactionExportExcel')->name('expense-transaction-export-excel');
            });
        });
    });
});
