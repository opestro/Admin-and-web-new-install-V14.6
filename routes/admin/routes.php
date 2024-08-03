<?php

use App\Enums\ViewPaths\Admin\AddonSetup;
use App\Enums\ViewPaths\Admin\AllPagesBanner;
use App\Enums\ViewPaths\Admin\Attribute;
use App\Enums\ViewPaths\Admin\Banner;
use App\Enums\ViewPaths\Admin\Brand;
use App\Enums\ViewPaths\Admin\BusinessSettings;
use App\Enums\ViewPaths\Admin\Cart;
use App\Enums\ViewPaths\Admin\Category;
use App\Enums\ViewPaths\Admin\Chatting;
use App\Enums\ViewPaths\Admin\Contact;
use App\Enums\ViewPaths\Admin\Coupon;
use App\Enums\ViewPaths\Admin\Currency;
use App\Enums\ViewPaths\Admin\Customer;
use App\Enums\ViewPaths\Admin\CustomerWallet;
use App\Enums\ViewPaths\Admin\CustomRole;
use App\Enums\ViewPaths\Admin\Dashboard;
use App\Enums\ViewPaths\Admin\DatabaseSetting;
use App\Enums\ViewPaths\Admin\DealOfTheDay;
use App\Enums\ViewPaths\Admin\DeliveryMan;
use App\Enums\ViewPaths\Admin\DeliveryManCash;
use App\Enums\ViewPaths\Admin\DeliverymanWithdraw;
use App\Enums\ViewPaths\Admin\DeliveryRestriction;
use App\Enums\ViewPaths\Admin\EmailTemplate;
use App\Enums\ViewPaths\Admin\EmergencyContact;
use App\Enums\ViewPaths\Admin\Employee;
use App\Enums\ViewPaths\Admin\EnvironmentSettings;
use App\Enums\ViewPaths\Admin\FeatureDeal;
use App\Enums\ViewPaths\Admin\FeaturesSection;
use App\Enums\ViewPaths\Admin\FileManager;
use App\Enums\ViewPaths\Admin\FlashDeal;
use App\Enums\ViewPaths\Admin\GoogleMapAPI;
use App\Enums\ViewPaths\Admin\HelpTopic;
use App\Enums\ViewPaths\Admin\InhouseProductSale;
use App\Enums\ViewPaths\Admin\InhouseShop;
use App\Enums\ViewPaths\Admin\InvoiceSettings;
use App\Enums\ViewPaths\Admin\Language;
use App\Enums\ViewPaths\Admin\Mail;
use App\Enums\ViewPaths\Admin\MostDemanded;
use App\Enums\ViewPaths\Admin\Notification;
use App\Enums\ViewPaths\Admin\OfflinePaymentMethod;
use App\Enums\ViewPaths\Admin\Order;
use App\Enums\ViewPaths\Admin\Pages;
use App\Enums\ViewPaths\Admin\PaymentMethod;
use App\Enums\ViewPaths\Admin\POS;
use App\Enums\ViewPaths\Admin\POSOrder;
use App\Enums\ViewPaths\Admin\PrioritySetup;
use App\Enums\ViewPaths\Admin\Product;
use App\Enums\ViewPaths\Admin\Profile;
use App\Enums\ViewPaths\Admin\PushNotification;
use App\Enums\ViewPaths\Admin\Recaptcha;
use App\Enums\ViewPaths\Admin\RefundRequest;
use App\Enums\ViewPaths\Admin\RefundTransaction;
use App\Enums\ViewPaths\Admin\Review;
use App\Enums\ViewPaths\Admin\ShippingMethod;
use App\Enums\ViewPaths\Admin\ShippingType;
use App\Enums\ViewPaths\Admin\SiteMap;
use App\Enums\ViewPaths\Admin\SMSModule;
use App\Enums\ViewPaths\Admin\SocialLoginSettings;
use App\Enums\ViewPaths\Admin\SocialMedia;
use App\Enums\ViewPaths\Admin\SocialMediaChat;
use App\Enums\ViewPaths\Admin\SoftwareUpdate;
use App\Enums\ViewPaths\Admin\SubCategory;
use App\Enums\ViewPaths\Admin\SubSubCategory;
use App\Enums\ViewPaths\Admin\SupportTicket;
use App\Enums\ViewPaths\Admin\ThemeSetup;
use App\Enums\ViewPaths\Admin\Vendor;
use App\Enums\ViewPaths\Admin\VendorRegistrationReason;
use App\Enums\ViewPaths\Admin\VendorRegistrationSetting;
use App\Enums\ViewPaths\Admin\WithdrawalMethod;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CategoryShippingCostController;
use App\Http\Controllers\Admin\ChattingController;
use App\Http\Controllers\Admin\Customer\CustomerController;
use App\Http\Controllers\Admin\Customer\CustomerLoyaltyController;
use App\Http\Controllers\Admin\Customer\CustomerWalletController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Deliveryman\DeliveryManCashCollectController;
use App\Http\Controllers\Admin\Deliveryman\DeliveryManController;
use App\Http\Controllers\Admin\Deliveryman\DeliverymanWithdrawController;
use App\Http\Controllers\Admin\Deliveryman\EmergencyContactController;
use App\Http\Controllers\Admin\EmailTemplatesController;
use App\Http\Controllers\Admin\Employee\CustomRoleController;
use App\Http\Controllers\Admin\Employee\EmployeeController;
use App\Http\Controllers\Admin\HelpAndSupport\ContactController;
use App\Http\Controllers\Admin\HelpAndSupport\HelpTopicController;
use App\Http\Controllers\Admin\HelpAndSupport\SupportTicketController;
use App\Http\Controllers\Admin\InhouseProductSaleController;
use App\Http\Controllers\Admin\Notification\NotificationController;
use App\Http\Controllers\Admin\Notification\PushNotificationSettingsController;
use App\Http\Controllers\Admin\Order\OrderController;
use App\Http\Controllers\Admin\Order\RefundController;
use App\Http\Controllers\Admin\OrderReportController;
use App\Http\Controllers\Admin\Payment\OfflinePaymentMethodController;
use App\Http\Controllers\Admin\POS\CartController;
use App\Http\Controllers\Admin\POS\POSController;
use App\Http\Controllers\Admin\POS\POSOrderController;
use App\Http\Controllers\Admin\Product\AttributeController;
use App\Http\Controllers\Admin\Product\BrandController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\ReviewController;
use App\Http\Controllers\Admin\Product\SubCategoryController;
use App\Http\Controllers\Admin\Product\SubSubCategoryController;
use App\Http\Controllers\Admin\ProductReportController;
use App\Http\Controllers\Admin\ProductStockReportController;
use App\Http\Controllers\Admin\ProductWishlistReportController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Promotion\AllPagesBannerController;
use App\Http\Controllers\Admin\Promotion\BannerController;
use App\Http\Controllers\Admin\Promotion\CouponController;
use App\Http\Controllers\Admin\Promotion\DealOfTheDayController;
use App\Http\Controllers\Admin\Promotion\FeaturedDealController;
use App\Http\Controllers\Admin\Promotion\FlashDealController;
use App\Http\Controllers\Admin\Promotion\MostDemandedController;
use App\Http\Controllers\Admin\Report\RefundTransactionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\Settings\AddonController;
use App\Http\Controllers\Admin\Settings\BusinessSettingsController;
use App\Http\Controllers\Admin\Settings\CurrencyController;
use App\Http\Controllers\Admin\Settings\DatabaseSettingController;
use App\Http\Controllers\Admin\Settings\DeliverymanSettingsController;
use App\Http\Controllers\Admin\Settings\DeliveryRestrictionController;
use App\Http\Controllers\Admin\Settings\EnvironmentSettingsController;
use App\Http\Controllers\Admin\Settings\FeaturesSectionController;
use App\Http\Controllers\Admin\Settings\FileManagerController;
use App\Http\Controllers\Admin\Settings\InhouseShopController;
use App\Http\Controllers\Admin\Settings\InvoiceSettingsController;
use App\Http\Controllers\Admin\Settings\LanguageController;
use App\Http\Controllers\Admin\Settings\OrderSettingsController;
use App\Http\Controllers\Admin\Settings\PagesController;
use App\Http\Controllers\Admin\Settings\PrioritySetupController;
use App\Http\Controllers\Admin\Settings\SiteMapController;
use App\Http\Controllers\Admin\Settings\SocialMediaSettingsController;
use App\Http\Controllers\Admin\Settings\SoftwareUpdateController;
use App\Http\Controllers\Admin\Settings\ThemeController;
use App\Http\Controllers\Admin\Settings\VendorRegistrationReasonController;
use App\Http\Controllers\Admin\Settings\VendorRegistrationSettingController;
use App\Http\Controllers\Admin\Settings\VendorSettingsController;
use App\Http\Controllers\Admin\Shipping\ShippingMethodController;
use App\Http\Controllers\Admin\Shipping\ShippingTypeController;
use App\Http\Controllers\Admin\ThirdParty\GoogleMapAPIController;
use App\Http\Controllers\Admin\ThirdParty\MailController;
use App\Http\Controllers\Admin\ThirdParty\PaymentMethodController;
use App\Http\Controllers\Admin\ThirdParty\RecaptchaController;
use App\Http\Controllers\Admin\ThirdParty\SMSModuleController;
use App\Http\Controllers\Admin\ThirdParty\SocialLoginSettingsController;
use App\Http\Controllers\Admin\ThirdParty\SocialMediaChatController;
use App\Http\Controllers\Admin\TransactionReportController;
use App\Http\Controllers\Admin\Vendor\VendorController;
use App\Http\Controllers\Admin\Vendor\WithdrawalMethodController;
use App\Http\Controllers\Admin\VendorProductSaleReportController;
use App\Http\Controllers\SharedController;
use Illuminate\Support\Facades\Route;


Route::post('change-language', [SharedController::class,'changeLanguage'])->name('change-language');

Route::group(['prefix' => 'login'], function () {
    Route::get('{loginUrl}', [LoginController::class, 'index']);
    Route::get('recaptcha/{tmp}', [LoginController::class, 'generateReCaptcha'])->name('recaptcha');
    Route::post('/', [LoginController::class, 'login'])->name('login');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['admin']], function () {

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get(Dashboard::VIEW[URI], 'index')->name('index');
            Route::post(Dashboard::ORDER_STATUS[URI], 'getOrderStatus')->name('order-status');
            Route::get(Dashboard::EARNING_STATISTICS[URI], 'getEarningStatistics')->name('earning-statistics');
            Route::get(Dashboard::ORDER_STATISTICS[URI], 'getOrderStatistics')->name('order-statistics');
        });
    });

    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::group(['prefix' => 'pos', 'as' => 'pos.','middleware'=>['module:pos_management']], function () {
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
            Route::post(Cart::QUANTITY_UPDATE[URI], 'updateQuantity')->name('update-quantity');
            Route::get(Cart::GET_CART_IDS[URI], 'getCartIds')->name('get-cart-ids');
            Route::get(Cart::CLEAR_CART_IDS[URI], 'clearSessionCartIds')->name('clear-cart-ids');
            Route::post(Cart::ADD[URI], 'addToCart')->name('add-to-cart');
            Route::post(Cart::REMOVE[URI], 'removeCart')->name('remove-cart');
            Route::any(Cart::CART_EMPTY[URI],'emptyCart')->name('empty-cart');
            Route::any(Cart::CHANGE_CART[URI], 'changeCart')->name('change-cart');
            Route::get(Cart::NEW_CART_ID[URI], 'addNewCartId')->name('new-cart-id');
        });
        Route::controller(POSOrderController::class)->group(function () {
            Route::post(POSOrder::ORDER_DETAILS[URI].'/{id}', 'index')->name('order-details');
            Route::post(POSOrder::ORDER_PLACE[URI], 'placeOrder')->name('place-order');
            Route::any(POSOrder::CANCEL_ORDER[URI],'cancelOrder')->name('cancel-order');
            Route::any(POSOrder::HOLD_ORDERS[URI],'getAllHoldOrdersView')->name('view-hold-orders');
        });
    });
    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get(Profile::INDEX[URI], 'index')->name('index');
            Route::get(Profile::UPDATE[URI] . '/{id}', 'getUpdateView')->name('update');
            Route::post(Profile::UPDATE[URI] . '/{id}', 'update');
            Route::patch(Profile::UPDATE[URI] . '/{id}', 'updatePassword');
        });
    });
    Route::group(['prefix' => 'products', 'as' => 'products.', 'middleware' => ['module:product_management']], function () {
        Route::controller(ProductController::class)->group(function () {
            Route::get(Product::LIST[URI] . '/{type}', 'index')->name('list');
            Route::get(Product::ADD[URI], 'getAddView')->name('add');
            Route::post(Product::ADD[URI], 'add')->name('store');
            Route::get(Product::VIEW[URI].'/{addedBy}/{id}', 'getView')->name('view');
            Route::post(Product::SKU_COMBINATION[URI], 'getSkuCombinationView')->name('sku-combination');
            Route::post(Product::FEATURED_STATUS[URI], 'updateFeaturedStatus')->name('featured-status');
            Route::get(Product::GET_CATEGORIES[URI], 'getCategories')->name('get-categories');
            Route::post(Product::UPDATE_STATUS[URI], 'updateStatus')->name('status-update');
            Route::get(Product::BARCODE_VIEW[URI] . '/{id}', 'getBarcodeView')->name('barcode');
            Route::get(Product::EXPORT_EXCEL[URI] . '/{type}', 'exportList')->name('export-excel');
            Route::get(Product::STOCK_LIMIT[URI] . '/{type}', 'getStockLimitListView')->name('stock-limit-list');
            Route::delete(Product::DELETE[URI] . '/{id}', 'delete')->name('delete');
            Route::get(Product::UPDATE[URI] . '/{id}', 'getUpdateView')->name('update');
            Route::post(Product::UPDATE[URI] . '/{id}', 'update');
            Route::get(Product::DELETE_IMAGE[URI], 'deleteImage')->name('delete-image');
            Route::get(Product::GET_VARIATIONS[URI], 'getVariations')->name('get-variations');
            Route::post(Product::UPDATE_QUANTITY[URI], 'updateQuantity')->name('update-quantity');
            Route::get(Product::BULK_IMPORT[URI], 'getBulkImportView')->name('bulk-import');
            Route::post(Product::BULK_IMPORT[URI], 'importBulkProduct');
            Route::get(Product::UPDATED_PRODUCT_LIST[URI], 'updatedProductList')->name('updated-product-list');
            Route::post(Product::UPDATED_SHIPPING[URI], 'updatedShipping')->name('updated-shipping');
            Route::post(Product::DENY[URI], 'deny')->name('deny');
            Route::post(Product::APPROVE_STATUS[URI], 'approveStatus')->name('approve-status');
            Route::get(Product::SEARCH[URI], 'getSearchedProductsView')->name('search-product');
            Route::get(Product::PRODUCT_GALLERY[URI], 'getProductGalleryView')->name('product-gallery');
            Route::get(Product::STOCK_LIMIT_STATUS[URI] . '/{type}', 'getStockLimitStatus')->name('stock-limit-status');

        });
    });

    Route::group(['prefix' => 'orders', 'as' => 'orders.','middleware'=>['module:order_management']], function () {
        Route::controller(OrderController::class)->group(function () {
            Route::get(Order::LIST[URI] . '/{status}', 'index')->name('list');
            Route::get(Order::EXPORT_EXCEL[URI].'/{status}', 'exportList')->name('export-excel');
            Route::get(Order::GENERATE_INVOICE[URI].'/{id}', 'generateInvoice')->name('generate-invoice')->withoutMiddleware(['module:order_management']);
            Route::get(Order::VIEW[URI].'/{id}', 'getView')->name('details');
            Route::post(Order::UPDATE_ADDRESS[URI], 'updateAddress')->name('address-update');// update address from order details
            Route::post(Order::UPDATE_DELIVERY_INFO[URI],'updateDeliverInfo')->name('update-deliver-info');
            Route::get(Order::ADD_DELIVERY_MAN[URI].'/{order_id}/{d_man_id}', 'addDeliveryMan')->name('add-delivery-man');
            Route::post(Order::UPDATE_AMOUNT_DATE[URI], 'updateAmountDate')->name('amount-date-update');
            Route::get(Order::CUSTOMERS[URI], 'getCustomers')->name('customers');
            Route::post(Order::PAYMENT_STATUS[URI], 'updatePaymentStatus')->name('payment-status');
            Route::get(Order::IN_HOUSE_ORDER_FILTER[URI], 'filterInHouseOrder')->name('inhouse-order-filter');
            Route::post(Order::DIGITAL_FILE_UPLOAD_AFTER_SELL[URI], 'uploadDigitalFileAfterSell')->name('digital-file-upload-after-sell');
            Route::post(Order::UPDATE_STATUS[URI], 'updateStatus')->name('status');
            Route::get(Order::GET_DATA[URI], 'getOrderData')->name('get-order-data');
        });
    });

    // Attribute
    Route::group(['prefix' => 'attribute', 'as' => 'attribute.','middleware'=>['module:product_management']], function () {
        Route::controller(AttributeController::class)->group(function (){
            Route::get(Attribute::LIST[URI], 'index')->name('view');
            Route::post(Attribute::STORE[URI], 'add')->name('store');
            Route::get(Attribute::UPDATE[URI].'/{id}', 'getUpdateView')->name('update');
            Route::post(Attribute::UPDATE[URI].'/{id}', 'update');
            Route::post(Attribute::DELETE[URI], 'delete')->name('delete');
        });
    });

    // Brand
    Route::group(['prefix' => 'brand', 'as' => 'brand.','middleware'=>['module:product_management']], function () {
        Route::controller(BrandController::class)->group(function (){
            Route::get(Brand::LIST[URI], 'index')->name('list');
            Route::get(Brand::ADD[URI], 'getAddView')->name('add-new');
            Route::post(Brand::ADD[URI], 'add');
            Route::get(Brand::UPDATE[URI].'/{id}', 'getUpdateView')->name('update');
            Route::post(Brand::UPDATE[URI].'/{id}', 'update');
            Route::post(Brand::DELETE[URI], 'delete')->name('delete');
            Route::get(Brand::EXPORT[URI], 'exportList')->name('export');
            Route::post(Brand::STATUS[URI], 'updateStatus')->name('status-update');
        });
    });

    // Category
    Route::group(['prefix' => 'category', 'as' => 'category.','middleware'=>['module:product_management']], function () {
        Route::controller(CategoryController::class)->group(function (){
            Route::get(Category::LIST[URI], 'index')->name('view');
            Route::post(Category::ADD[URI], 'add')->name('store');
            Route::get(Category::UPDATE[URI], 'getUpdateView')->name('update');
            Route::post(Category::UPDATE[URI], 'update');
            Route::post(Category::DELETE[URI], 'delete')->name('delete');
            Route::post(Category::STATUS[URI], 'updateStatus')->name('status');
            Route::get(Category::EXPORT[URI], 'getExportList')->name('export');

        });
    });

    // Sub Category
    Route::group(['prefix' => 'sub-category', 'as' => 'sub-category.','middleware'=>['module:product_management']], function () {
        Route::controller(SubCategoryController::class)->group(function (){
            Route::get(SubCategory::LIST[URI], 'index')->name('view');
            Route::post(SubCategory::ADD[URI], 'add')->name('store');
            Route::get(SubCategory::UPDATE[URI].'/{id}', 'getUpdateView')->name('update');
            Route::post(SubCategory::UPDATE[URI].'/{id}', 'update');
            Route::post(SubCategory::DELETE[URI], 'delete')->name('delete');
            Route::get(SubCategory::EXPORT[URI], 'getExportList')->name('export');
        });
    });

    // Sub Sub Category
    Route::group(['prefix' => 'sub-sub-category', 'as' => 'sub-sub-category.','middleware'=>['module:product_management']], function () {
        Route::controller(SubSubCategoryController::class)->group(function (){
            Route::get(SubSubCategory::LIST[URI], 'index')->name('view');
            Route::post(SubSubCategory::ADD[URI], 'add')->name('store');
            Route::get(SubSubCategory::UPDATE[URI].'/{id}', 'getUpdateView')->name('update');
            Route::post(SubSubCategory::UPDATE[URI].'/{id}', 'update');
            Route::post(SubSubCategory::DELETE[URI], 'delete')->name('delete');
            Route::post(SubSubCategory::GET_SUB_CATEGORY[URI], 'getSubCategory')->name('getSubCategory');
            Route::get(SubSubCategory::EXPORT[URI], 'getExportList')->name('export');
        });
    });

    // Banner
    Route::group(['prefix' => 'banner', 'as' => 'banner.','middleware'=>['module:promotion_management']], function () {
        Route::controller(BannerController::class)->group(function (){
            Route::get(Banner::LIST[URI], 'index')->name('list');
            Route::post(Banner::ADD[URI], 'add')->name('store');
            Route::post(Banner::DELETE[URI], 'delete')->name('delete');
            Route::post(Banner::STATUS[URI], 'updateStatus')->name('status');
            Route::get(Banner::UPDATE[URI].'/{id}', 'getUpdateView')->name('update');
            Route::post(Banner::UPDATE[URI].'/{id}', 'update');
        });
    });

    // Customer Routes, Customer wallet Routes, Customer Loyalty Routes
    Route::group(['prefix' => 'customer', 'as' => 'customer.','middleware'=>['module:user_section']], function () {
        Route::controller(CustomerController::class)->group(function (){
            Route::get(Customer::LIST[URI], 'getListView')->name('list');
            Route::get(Customer::VIEW[URI].'/{user_id}', 'getView')->name('view');
            Route::get(Customer::ORDER_LIST_EXPORT[URI].'/{user_id}', 'exportOrderList')->name('order-list-export');
            Route::post(Customer::UPDATE[URI], 'updateStatus')->name('status-update');
            Route::delete(Customer::DELETE[URI],'delete')->name('delete');
            Route::get(Customer::SUBSCRIBER_LIST[URI], 'getSubscriberListView')->name('subscriber-list');
            Route::get(Customer::SUBSCRIBER_EXPORT[URI], 'exportSubscribersList')->name('subscriber-list.export');
            Route::get(Customer::EXPORT[URI], 'exportList')->name('export');
            Route::get(Customer::SEARCH[URI],'getCustomerList')->name('customer-list-search');
            Route::get(Customer::SEARCH_WITHOUT_ALL_CUSTOMER[URI],'getCustomerListWithoutAllCustomerName')->name('customer-list-without-all-customer');
            Route::post(Customer::ADD[URI],'add')->name('add');
        });

        Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function () {
            Route::controller(CustomerWalletController::class)->group(function (){
                Route::get(CustomerWallet::REPORT[URI], 'index')->name('report');
                Route::post(CustomerWallet::ADD[URI], 'addFund')->name('add-fund');
                Route::get(CustomerWallet::EXPORT[URI], 'exportList')->name('export');
                Route::get(CustomerWallet::BONUS_SETUP[URI], 'getBonusSetupView')->name('bonus-setup');
                Route::post(CustomerWallet::BONUS_SETUP[URI], 'addBonusSetup');
                Route::post(CustomerWallet::BONUS_SETUP_UPDATE[URI], 'update')->name('bonus-setup-update');
                Route::post(CustomerWallet::BONUS_SETUP_STATUS[URI], 'updateStatus')->name('bonus-setup-status');
                Route::get(CustomerWallet::BONUS_SETUP_EDIT[URI].'/{id}', 'getUpdateView')->name('bonus-setup-edit');
                Route::delete(CustomerWallet::BONUS_SETUP_DELETE[URI], 'deleteBonus')->name('bonus-setup-delete');
            });
        });

        Route::group(['prefix' => 'loyalty', 'as' => 'loyalty.'], function () {
            Route::controller(CustomerLoyaltyController::class)->group(function (){
                Route::get(Customer::LOYALTY_REPORT[URI], 'index')->name('report');
                Route::get(Customer::LOYALTY_EXPORT[URI], 'exportList')->name('export');
            });
        });

    });

    Route::group(['prefix' => 'report', 'as' => 'report.', 'middleware' => ['module:report']], function () {
        Route::controller(InhouseProductSaleController::class)->group(function () {
            Route::get(InhouseProductSale::VIEW[URI], 'index')->name('inhouse-product-sale');
        });
    });

    Route::group(['middleware'=>['module:system_settings']],function () {
        Route::group(['prefix' => 'customer', 'as' => 'customer.'],function () {
            Route::controller(CustomerController::class)->group(function (){
                Route::get(Customer::SETTINGS[URI], 'getCustomerSettingsView')->name('customer-settings');
                Route::post(Customer::SETTINGS[URI], 'update');
            });
        });
    });

    Route::group(['prefix' => 'vendors', 'as' => 'vendors.','middleware'=>['module:user_section']], function () {
        Route::controller(VendorController::class)->group(function (){
            Route::get(Vendor::LIST[URI], 'index')->name('vendor-list');
            Route::get(Vendor::ADD[URI], 'getAddView')->name('add');
            Route::POST(Vendor::ADD[URI], 'add');
            Route::get(Vendor::ORDER_LIST[URI].'/{vendor_id}', 'getOrderListView')->name('order-list');
            Route::get(Vendor::ORDER_LIST_EXPORT[URI].'/{vendor_id}', 'exportOrderList')->name('order-list-export');
            Route::post(Vendor::STATUS[URI], 'updateStatus')->name('updateStatus');
            Route::get(Vendor::EXPORT[URI], 'exportList')->name('export');
            Route::get(Vendor::PRODUCT_LIST[URI].'/{vendor_id}', 'getProductListView')->name('product-list');

            Route::post(Vendor::SALES_COMMISSION_UPDATE[URI].'/{id}', 'updateSalesCommission')->name('sales-commission-update');
            Route::get(Vendor::ORDER_DETAILS[URI].'/{order_id}/{vendor_id}', 'getOrderDetailsView')->name('order-details');
            Route::get(Vendor::VIEW[URI].'/{id}/{tab?}', 'getView')->name('view');
            Route::post(Vendor::UPDATE_SETTING[URI].'/{id}', 'updateSetting')->name('update-setting');

            Route::get(Vendor::WITHDRAW_LIST[URI], 'getWithdrawListView')->name('withdraw_list');
            Route::get(Vendor::WITHDRAW_LIST_EXPORT[URI], 'exportWithdrawList')->name('withdraw-list-export-excel');
            Route::get(Vendor::WITHDRAW_VIEW[URI].'/{withdrawId}/{vendorId}', 'getWithdrawView')->name('withdraw_view');
            Route::post(Vendor::WITHDRAW_STATUS[URI].'/{id}', 'withdrawStatus')->name('withdraw_status');
        });

        Route::group(['prefix' => 'withdraw-method', 'as' => 'withdraw-method.'], function () {
            Route::controller(WithdrawalMethodController::class)->group(function (){
                Route::get(WithdrawalMethod::LIST[URI], 'index')->name('list');
                Route::get(WithdrawalMethod::ADD[URI], 'getAddView')->name('add');
                Route::post(WithdrawalMethod::ADD[URI], 'add');
                Route::delete(WithdrawalMethod::DELETE[URI].'/{id}', 'delete')->name('delete');
                Route::post(WithdrawalMethod::DEFAULT_STATUS[URI], 'updateDefaultStatus')->name('default-status');
                Route::post(WithdrawalMethod::STATUS[URI], 'updateStatus')->name('status-update');
                Route::get(WithdrawalMethod::UPDATE[URI].'/{id}', 'getUpdateView')->name('edit');
                Route::post(WithdrawalMethod::UPDATE[URI], 'update')->name('update');
            });
        });
    });

    Route::group(['prefix' => 'employee', 'as' => 'employee.'], function () {
        Route::controller(EmployeeController::class)->group(function (){
            Route::get(Employee::LIST[URI], 'index')->name('list');
            Route::get(Employee::ADD[URI], 'getAddView')->name('add-new');
            Route::post(Employee::ADD[URI], 'add')->name('add-new');
            Route::get(Employee::EXPORT[URI], 'exportList')->name('export');
            Route::get(Employee::VIEW[URI].'/{id}', 'getView')->name('view');
            Route::get(Employee::UPDATE[URI].'/{id}', 'getUpdateView')->name('update');
            Route::post(Employee::UPDATE[URI].'/{id}', 'update');
            Route::post(Employee::STATUS[URI], 'updateStatus')->name('status');
        });
    });

    Route::group(['prefix' => 'custom-role', 'as' => 'custom-role.','middleware'=>['module:user_section']], function () {
        Route::controller(CustomRoleController::class)->group(function (){
            Route::get(CustomRole::ADD[URI], 'index')->name('create');
            Route::post(CustomRole::ADD[URI], 'add')->name('store');
            Route::get(CustomRole::UPDATE[URI].'/{id}', 'getUpdateView')->name('update');
            Route::post(CustomRole::UPDATE[URI].'/{id}', 'update');
            Route::post(CustomRole::STATUS[URI],'updateStatus')->name('employee-role-status');
            Route::post(CustomRole::DELETE[URI], 'delete')->name('delete');
            Route::get(CustomRole::EXPORT[URI], 'exportList')->name('export');
        });
    });

    /*  report */
    Route::group(['prefix' => 'report', 'as' => 'report.' ,'middleware'=>['module:report']], function () {
        Route::group(['prefix' => 'transaction', 'as' => 'transaction.'], function () {
            Route::controller(RefundTransactionController::class)->group(function (){
                Route::get(RefundTransaction::INDEX[URI], 'index')->name('refund-transaction-list');
                Route::get(RefundTransaction::EXPORT[URI], 'exportRefundTransaction')->name('refund-transaction-export');
                Route::get(RefundTransaction::GENERATE_PDF[URI], 'getRefundTransactionPDF')->name('refund-transaction-summary-pdf');
            });
        });
    });

    Route::group(['prefix' => 'report', 'as' => 'report.' ,'middleware'=>['module:report']], function () {
        Route::controller(ReportController::class)->group(function (){
            Route::get('earning', 'earning_index')->name('earning');
            Route::get('admin-earning', 'admin_earning')->name('admin-earning');
            Route::get('admin-earning-excel-export', 'exportAdminEarning')->name('admin-earning-excel-export');
            Route::post('admin-earning-duration-download-pdf', 'admin_earning_duration_download_pdf')->name('admin-earning-duration-download-pdf');
            Route::get('vendor-earning', 'vendorEarning')->name('vendor-earning');
            Route::get('vendor-earning-excel-export', 'exportVendorEarning')->name('vendor-earning-excel-export');
            Route::any('set-date', 'set_date')->name('set-date');
        });

        Route::controller(OrderReportController::class)->group(function (){
            Route::get('order', 'order_list')->name('order');
            Route::get('order-report-excel', 'orderReportExportExcel')->name('order-report-excel');
            Route::get('order-report-pdf', 'exportOrderReportInPDF')->name('order-report-pdf');
        });

        Route::controller(ProductReportController::class)->group(function (){
            Route::get('all-product', 'all_product')->name('all-product');
            Route::get('all-product-excel', 'allProductExportExcel')->name('all-product-excel');
        });

        Route::controller(VendorProductSaleReportController::class)->group(function (){
            Route::get('vendor-report', 'vendorReport')->name('vendor-report');
            Route::get('vendor-report-export', 'exportVendorReport')->name('vendor-report-export');
        });
    });

    Route::group(['prefix' => 'transaction', 'as' => 'transaction.' ,'middleware'=>['module:report']], function () {
        Route::controller(TransactionReportController::class)->group(function (){
            Route::get('order-transaction-list', 'order_transaction_list')->name('order-transaction-list');
            Route::get('pdf-order-wise-transaction', 'pdf_order_wise_transaction')->name('pdf-order-wise-transaction');
            Route::get('order-transaction-export-excel', 'orderTransactionExportExcel')->name('order-transaction-export-excel');
            Route::get('order-transaction-summary-pdf', 'order_transaction_summary_pdf')->name('order-transaction-summary-pdf');
            Route::get('expense-transaction-list', 'expense_transaction_list')->name('expense-transaction-list');
            Route::get('pdf-order-wise-expense-transaction', 'pdf_order_wise_expense_transaction')->name('pdf-order-wise-expense-transaction');
            Route::get('expense-transaction-export-excel', 'expenseTransactionExportExcel')->name('expense-transaction-export-excel');
            Route::get('expense-transaction-summary-pdf', 'expense_transaction_summary_pdf')->name('expense-transaction-summary-pdf');

            Route::get('wallet-bonus', 'wallet_bonus')->name('wallet-bonus');
        });
    });

    Route::group(['prefix' => 'stock', 'as' => 'stock.' ,'middleware'=>['module:report']], function () {
        Route::controller(ProductStockReportController::class)->group(function (){
            //product stock report
            Route::get('product-stock', 'index')->name('product-stock');
            Route::get('product-stock-export', 'export')->name('product-stock-export');
            Route::post('ps-filter', 'filter')->name('ps-filter');
        });

        Route::controller(ProductWishlistReportController::class)->group(function (){
            //product in wishlist report
            Route::get('product-in-wishlist', 'index')->name('product-in-wishlist');
            Route::get('wishlist-product-export', 'export')->name('wishlist-product-export');
        });
    });

    /*  end report */
    // Reviews
    Route::group(['prefix' => 'reviews', 'as' => 'reviews.','middleware'=>['module:user_section']], function () {
        Route::controller(ReviewController::class)->group(function (){
            Route::get(Review::LIST[URI], 'index')->name('list')->middleware('actch');
            Route::get(Review::STATUS[URI], 'updateStatus')->name('status');
            Route::get(Review::EXPORT[URI], 'exportList')->name('export')->middleware('actch');
            Route::get(Review::SEARCH[URI],'getCustomerList')->name('customer-list-search');
            Route::any(Review::SEARCH_PRODUCT[URI], 'search')->name('search-product');
        });
    });

    // Coupon
    Route::group(['prefix' => 'coupon', 'as' => 'coupon.','middleware'=>['module:promotion_management']], function () {
        Route::controller(CouponController::class)->group(function (){
            Route::get(Coupon::ADD[URI], 'getAddListView')->name('add')->middleware('actch');
            Route::post(Coupon::ADD[URI], 'add');
            Route::get(Coupon::EXPORT[URI], 'exportList')->name('export')->middleware('actch');
            Route::get(Coupon::QUICK_VIEW[URI], 'quickView')->name('quick-view-details');
            Route::get(Coupon::UPDATE[URI].'/{id}', 'getUpdateView')->name('update')->middleware('actch');
            Route::post(Coupon::UPDATE[URI].'/{id}', 'update');
            Route::get(Coupon::STATUS[URI].'/{id}/{status}', 'updateStatus')->name('status');
            Route::post(Coupon::VENDOR_LIST[URI], 'getVendorList')->name('ajax-get-vendor');
            Route::delete(Coupon::DELETE[URI].'/{id}', 'delete')->name('delete');
        });
    });

    Route::group(['prefix' => 'deal', 'as' => 'deal.','middleware'=>['module:promotion_management']], function () {
        Route::controller(FlashDealController::class)->group(function (){
            Route::get(FlashDeal::LIST[URI], 'index')->name('flash');
            Route::post(FlashDeal::LIST[URI], 'add');
            Route::get(FlashDeal::UPDATE[URI].'/{id}', 'getUpdateView')->name('update');
            Route::post(FlashDeal::UPDATE[URI].'/{id}', 'update')->name('update');
            Route::post(FlashDeal::STATUS[URI], 'updateStatus')->name('status-update');
            Route::post(FlashDeal::DELETE[URI], 'delete')->name('delete-product');
            Route::get(FlashDeal::ADD_PRODUCT[URI].'/{deal_id}', 'getAddProductView')->name('add-product');
            Route::post(FlashDeal::ADD_PRODUCT[URI].'/{deal_id}', 'addProduct');
            Route::any(FlashDeal::SEARCH[URI], 'search')->name('search-product');
        });

        Route::controller(DealOfTheDayController::class)->group(function (){
            Route::get(DealOfTheDay::LIST[URI], 'index')->name('day');
            Route::post(DealOfTheDay::LIST[URI], 'add');
            Route::post(DealOfTheDay::STATUS[URI], 'updateStatus')->name('day-status-update');
            Route::get(DealOfTheDay::UPDATE[URI].'/{id}', 'getUpdateView')->name('day-update');
            Route::post(DealOfTheDay::UPDATE[URI].'/{id}', 'update');
            Route::post(DealOfTheDay::DELETE[URI], 'delete')->name('day-delete');
        });

        Route::controller(FeaturedDealController::class)->group(function (){
            Route::get(FeatureDeal::LIST[URI], 'index')->name('feature');
            Route::get(FeatureDeal::UPDATE[URI].'/{id}', 'getUpdateView')->name('edit');
            Route::post(FeatureDeal::UPDATE[URI], 'update')->name('featured-update');
            Route::post(FeatureDeal::STATUS[URI], 'updateStatus')->name('feature-status');
        });
    });

    /** notification and push notification */
    Route::group(['prefix' => 'push-notification', 'as' => 'push-notification.','middleware'=>['module:promotion_management']], function () {
        Route::controller(PushNotificationSettingsController::class)->group(function (){
            Route::get(PushNotification::INDEX[URI], 'index')->name('index');
            Route::post(PushNotification::UPDATE[URI], 'updatePushNotificationMessage')->name('update');
            Route::get(PushNotification::FIREBASE_CONFIGURATION[URI], 'getFirebaseConfigurationView')->name('firebase-configuration');
            Route::post(PushNotification::FIREBASE_CONFIGURATION[URI], 'getFirebaseConfigurationUpdate');
        });
    });
    Route::group(['prefix' => 'notification', 'as' => 'notification.','middleware'=>['module:promotion_management']], function () {
        Route::controller(NotificationController::class)->group(function (){
            Route::get(Notification::INDEX[URI], 'index')->name('index');
            Route::post(Notification::INDEX[URI], 'add');
            Route::get(Notification::UPDATE[URI].'/{id}', 'getUpdateView')->name('update');
            Route::post(Notification::UPDATE[URI].'/{id}', 'update');
            Route::post(Notification::DELETE[URI], 'delete')->name('delete');
            Route::post(Notification::UPDATE_STATUS[URI], 'updateStatus')->name('update-status');
            Route::post(Notification::RESEND_NOTIFICATION[URI], 'resendNotification')->name('resend-notification');
        });
    });
    /* end notification */
    Route::group(['prefix' => 'support-ticket', 'as' => 'support-ticket.','middleware'=>['module:support_section']], function () {
        Route::controller(SupportTicketController::class)->group(function (){
            Route::get(SupportTicket::LIST[URI], 'index')->name('view');
            Route::post(SupportTicket::STATUS[URI], 'updateStatus')->name('status');
            Route::get(SupportTicket::VIEW[URI].'/{id}', 'getView')->name('singleTicket');
            Route::post(SupportTicket::VIEW[URI].'/{id}', 'reply')->name('replay');
        });
    });
    Route::group(['prefix' => 'messages', 'as' => 'messages.'], function () {
        Route::controller(ChattingController::class)->group(function () {
            Route::get(Chatting::INDEX[URI] . '/{type}', 'index')->name('index');
            Route::get(Chatting::MESSAGE[URI], 'getMessageByUser')->name('message');
            Route::post(Chatting::MESSAGE[URI], 'addAdminMessage');
            Route::get(Chatting::NEW_NOTIFICATION[URI], 'getNewNotification')->name('new-notification');
        });
    });

    Route::group(['prefix' => 'contact', 'as' => 'contact.','middleware'=>['module:support_section']], function () {
        Route::controller(ContactController::class)->group(function (){
            Route::get(Contact::LIST[URI], 'index')->name('list');
            Route::get(Contact::VIEW[URI].'/{id}', 'getView')->name('view');
            Route::post(Contact::FILTER[URI], 'getListByFilter')->name('filter');
            Route::post(Contact::DELETE[URI], 'delete')->name('delete');
            Route::post(Contact::UPDATE[URI].'/{id}', 'update')->name('update');
            Route::post(Contact::ADD[URI], 'add')->name('store');
            Route::post(Contact::SEND_MAIL[URI].'/{id}', 'sendMail')->name('send-mail');
        });
    });

    Route::group(['prefix' => 'delivery-man', 'as' => 'delivery-man.', 'middleware'=>['module:user_section']], function () {
        Route::controller(DeliveryManController::class)->group(function (){
            Route::get(DeliveryMan::LIST[URI], 'index')->name('list');
            Route::get(DeliveryMan::ADD[URI], 'getAddView')->name('add');
            Route::post(DeliveryMan::ADD[URI], 'add');
            Route::post(DeliveryMan::STATUS[URI], 'updateStatus')->name('status-update');
            Route::get(DeliveryMan::EXPORT[URI], 'exportList')->name('export');
            Route::get(DeliveryMan::UPDATE[URI].'/{id}', 'getUpdateView')->name('edit');
            Route::post(DeliveryMan::UPDATE[URI].'/{id}', 'update')->name('update');
            Route::delete(DeliveryMan::DELETE[URI].'/{id}', 'delete')->name('delete');
            Route::get(DeliveryMan::EARNING_STATEMENT_OVERVIEW[URI].'/{id}', 'getEarningOverview')->name('earning-statement-overview');
            Route::get(DeliveryMan::EARNING_OVERVIEW[URI].'/{id}', 'getOrderWiseEarningView')->name('order-wise-earning');
            Route::post(DeliveryMan::ORDER_WISE_EARNING_LIST_BY_FILTER[URI].'/{id}', 'getOrderWiseEarningListByFilter')->name('order-wise-earning-list-by-filter');
            Route::get(DeliveryMan::ORDER_HISTORY_LOG[URI].'/{id}', 'getOrderHistoryList')->name('order-history-log');
            Route::get(DeliveryMan::ORDER_HISTORY_LOG_EXPORT[URI].'/{id}', 'getOrderHistoryListExport')->name('order-history-log-export');
            Route::get(DeliveryMan::RATING[URI].'/{id}', 'getRatingView')->name('rating');
            Route::get(DeliveryMan::ORDER_HISTORY[URI].'/{order}', 'getOrderStatusHistory')->name('ajax-order-status-history');
        });

        Route::controller(DeliveryManCashCollectController::class)->group(function (){
            Route::get(DeliveryManCash::LIST[URI].'/{id}', 'index')->name('collect-cash');
            Route::post(DeliveryManCash::ADD[URI].'/{id}', 'getCashReceive')->name('cash-receive');
        });

        Route::controller(DeliverymanWithdrawController::class)->group(function (){
            Route::get(DeliverymanWithdraw::LIST[URI], 'index')->name('withdraw-list');
            Route::post(DeliveryManWithdraw::LIST[URI],'getFiltered');
            Route::get(DeliverymanWithdraw::EXPORT_LIST[URI], 'exportList')->name('withdraw-list-export');
            Route::get(DeliverymanWithdraw::VIEW[URI].'/{withdraw_id}', 'getView')->name('withdraw-view');
            Route::post(DeliverymanWithdraw::UPDATE[URI].'/{id}', 'updateStatus')->name('withdraw-update-status');
        });
        Route::group(['prefix' => 'emergency-contact', 'as' => 'emergency-contact.'], function (){
            Route::controller(EmergencyContactController::class)->group(function (){
                Route::get(EmergencyContact::LIST[URI], 'index')->name('index');
                Route::post(EmergencyContact::ADD[URI], 'add')->name('add');
                Route::get(EmergencyContact::UPDATE[URI].'/{id}', 'getUpdateView')->name('update');
                Route::post(EmergencyContact::UPDATE[URI].'/{id}', 'update');
                Route::post(EmergencyContact::STATUS[URI], 'updateStatus')->name('ajax-status-change');
                Route::delete(EmergencyContact::DELETE[URI], 'delete')->name('destroy');
            });
        });

    });

    Route::group(['prefix' => 'most-demanded', 'as' => 'most-demanded.','middleware'=>['module:promotion_management']], function () {
        Route::controller(MostDemandedController::class)->group(function (){
            Route::get(MostDemanded::LIST[URI], 'index')->name('index');
            Route::post(MostDemanded::ADD[URI], 'add')->name('store');
            Route::get(MostDemanded::UPDATE[URI].'/{id}', 'getUpdateView')->name('edit');
            Route::post(MostDemanded::UPDATE[URI].'/{id}', 'update')->name('update');
            Route::post(MostDemanded::DELETE[URI], 'delete')->name('delete');
            Route::post(MostDemanded::STATUS[URI], 'updateStatus')->name('status-update');
        });
    });

    Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
        Route::controller(AllPagesBannerController::class)->group(function (){
            Route::get(AllPagesBanner::LIST[URI], 'index')->name('all-pages-banner');
            Route::post(AllPagesBanner::ADD[URI], 'add')->name('all-pages-banner-store');
            Route::get(AllPagesBanner::UPDATE[URI].'/{id}', 'getUpdateView')->name('all-pages-banner-edit');
            Route::post(AllPagesBanner::UPDATE[URI], 'update')->name('all-pages-banner-update');
            Route::post(AllPagesBanner::STATUS[URI], 'updateStatus')->name('all-pages-banner-status');
            Route::post(AllPagesBanner::DELETE[URI], 'delete')->name('all-pages-banner-delete');
        });
    });

    Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
        Route::group(['middleware'=>['module:system_settings']],function (){
            Route::controller(PagesController::class)->group(function (){
                Route::get(Pages::TERMS_CONDITION[URI], 'index')->name('terms-condition');
                Route::post(Pages::TERMS_CONDITION[URI], 'updateTermsCondition')->name('update-terms');

                Route::get(Pages::PRIVACY_POLICY[URI], 'getPrivacyPolicyView')->name('privacy-policy');
                Route::post(Pages::PRIVACY_POLICY[URI], 'updatePrivacyPolicy')->name('privacy-policy');

                Route::get(Pages::ABOUT_US[URI], 'getAboutUsView')->name('about-us');
                Route::post(Pages::ABOUT_US[URI], 'updateAboutUs')->name('about-update');

                Route::get(Pages::VIEW[URI].'/{page}', 'getPageView')->name('page');
                Route::post(Pages::VIEW[URI].'/{page}', 'updatePage')->name('page-update');
            });

            Route::controller(SocialMediaSettingsController::class)->group(function (){
                Route::get(SocialMedia::VIEW[URI], 'index')->name('social-media');
                Route::get(SocialMedia::LIST[URI], 'getList')->name('fetch');
                Route::post(SocialMedia::ADD[URI], 'add')->name('social-media-store');
                Route::post(SocialMedia::GET_UPDATE[URI], 'getUpdate')->name('social-media-edit');
                Route::post(SocialMedia::UPDATE[URI], 'update')->name('social-media-update');
                Route::post(SocialMedia::DELETE[URI], 'delete')->name('social-media-delete');
                Route::post(SocialMedia::STATUS[URI], 'updateStatus')->name('social-media-status-update');
            });

            Route::controller(BusinessSettingsController::class)->group(function (){
                Route::post(BusinessSettings::MAINTENANCE_MODE[URI], 'updateSystemMode')->name('maintenance-mode');

                Route::get(BusinessSettings::COOKIE_SETTINGS[URI], 'getCookieSettingsView')->name('cookie-settings');
                Route::post(BusinessSettings::COOKIE_SETTINGS[URI], 'updateCookieSetting');

                Route::get(BusinessSettings::OTP_SETUP[URI], 'getOtpSetupView')->name('otp-setup');
                Route::post(BusinessSettings::OTP_SETUP[URI], 'updateOtpSetup');

                Route::get(BusinessSettings::ANALYTICS_INDEX[URI], 'getAnalyticsView')->name('analytics-index');
                Route::post(BusinessSettings::ANALYTICS_UPDATE[URI], 'updateAnalytics')->name('analytics-update');
            });

            Route::controller(RecaptchaController::class)->group(function (){
                Route::get(Recaptcha::VIEW[URI], 'index')->name('captcha');
                Route::post(Recaptcha::VIEW[URI], 'update');
            });

            Route::controller(GoogleMapAPIController::class)->group(function (){
                Route::get(GoogleMapAPI::VIEW[URI], 'index')->name('map-api');
                Route::post(GoogleMapAPI::VIEW[URI], 'update');
            });

            Route::controller(FeaturesSectionController::class)->group(function (){
                Route::get(FeaturesSection::VIEW[URI], 'index')->name('features-section');
                Route::post(FeaturesSection::UPDATE[URI], 'update')->name('features-section.submit');
                Route::post(FeaturesSection::DELETE[URI], 'delete')->name('features-section.icon-remove');

                Route::get(FeaturesSection::COMPANY_RELIABILITY[URI], 'getCompanyReliabilityView')->name('company-reliability');
                Route::post(FeaturesSection::COMPANY_RELIABILITY[URI], 'updateCompanyReliability');
            });
        });

        Route::group(['prefix' => 'language', 'as' => 'language.','middleware'=>['module:system_settings']], function () {
            Route::controller(LanguageController::class)->group(function (){
                Route::get(Language::LIST[URI], 'index')->name('index');
                Route::post(Language::ADD[URI], 'add')->name('add-new');
                Route::post(Language::STATUS[URI], 'updateStatus')->name('update-status');
                Route::get(Language::DEFAULT_STATUS[URI], 'updateDefaultStatus')->name('update-default-status');
                Route::post(Language::UPDATE[URI], 'update')->name('update');
                Route::get(Language::DELETE[URI].'/{lang}', 'delete')->name('delete');
                Route::get(Language::TRANSLATE_VIEW[URI].'/{lang}', 'getTranslateView')->name('translate');
                Route::get(Language::TRANSLATE_LIST[URI].'/{lang}', 'getTranslateList')->name('translate.list');
                Route::post(Language::TRANSLATE_ADD[URI].'/{lang}', 'updateTranslate')->name('translate-submit');
                Route::post(Language::TRANSLATE_REMOVE[URI].'/{lang}', 'deleteTranslateKey')->name('remove-key');
                Route::any(Language::TRANSLATE_AUTO[URI].'/{lang}', 'getAutoTranslate')->name('auto-translate');
            });
        });

        Route::group(['prefix' => 'invoice-settings', 'as' => 'invoice-settings.', 'middleware' =>['module:system_settings']], function (){
            Route::controller(InvoiceSettingsController::class)->group(function (){
                Route::get(InvoiceSettings::VIEW[URI], 'index')->name('index');
                Route::post(InvoiceSettings::VIEW[URI], 'update')->name('update');
            });
        });

        Route::group(['prefix' => 'web-config', 'as' => 'web-config.','middleware'=>['module:system_settings']], function () {
            Route::controller(BusinessSettingsController::class)->group(function (){
                Route::get(BusinessSettings::INDEX[URI], 'index')->name('index')->middleware('actch');
                Route::post(BusinessSettings::INDEX[URI],'updateSettings')->name('update');

                Route::get(BusinessSettings::APP_SETTINGS[URI], 'getAppSettingsView')->name('app-settings');
                Route::post(BusinessSettings::APP_SETTINGS[URI], 'updateAppSettings');

                Route::get(BusinessSettings::LOGIN_URL_SETUP[URI], 'getLoginSetupView')->name('login-url-setup');
                Route::post(BusinessSettings::LOGIN_URL_SETUP[URI], 'updateLoginSetupView');
            });

            Route::controller(EnvironmentSettingsController::class)->group(function (){
                Route::get(EnvironmentSettings::VIEW[URI], 'index')->name('environment-setup');
                Route::post(EnvironmentSettings::VIEW[URI], 'update');
            });

            Route::controller(SiteMapController::class)->group(function (){
                Route::get(SiteMap::VIEW[URI],'index')->name('mysitemap');
                Route::get(SiteMap::DOWNLOAD[URI],'getFile')->name('mysitemap-download');
            });

            Route::controller(DatabaseSettingController::class)->group(function (){
                Route::get(DatabaseSetting::VIEW[URI], 'index')->name('db-index');
                Route::post(DatabaseSetting::DELETE[URI], 'delete')->name('clean-db');
            });

            Route::group(['prefix' => 'theme', 'as' => 'theme.'], function () {
                Route::controller(ThemeController::class)->group(function (){
                    Route::get(ThemeSetup::VIEW[URI], 'index')->name('setup');
                    Route::post(ThemeSetup::UPLOAD[URI], 'upload')->name('install');
                    Route::post(ThemeSetup::ACTIVE[URI], 'activation')->name('activation');
                    Route::post(ThemeSetup::STATUS[URI], 'publish')->name('publish');
                    Route::post(ThemeSetup::DELETE[URI], 'delete')->name('delete');
                    Route::post(ThemeSetup::NOTIFY_VENDOR[URI], 'notifyAllTheVendors')->name('notify-all-the-vendors');
                });
            });

        });

        Route::group(['prefix' => 'vendor-registration-settings', 'as' => 'vendor-registration-settings.','middleware'=>['module:system_settings']], function () {
            Route::controller(VendorRegistrationSettingController::class)->group(function (){
                Route::get(VendorRegistrationSetting::INDEX[URI], 'index')->name('index');
                Route::post(VendorRegistrationSetting::INDEX[URI], 'updateHeaderSection');
                Route::get(VendorRegistrationSetting::WITH_US[URI], 'getSellWithUsView')->name('with-us');
                Route::post(VendorRegistrationSetting::WITH_US[URI], 'updateSellWithUsSection');
                Route::get(VendorRegistrationSetting::BUSINESS_PROCESS[URI], 'getBusinessProcessView')->name('business-process');
                Route::post(VendorRegistrationSetting::BUSINESS_PROCESS[URI], 'updateBusinessProcess');
                Route::get(VendorRegistrationSetting::DOWNLOAD_APP[URI], 'getDownloadAppView')->name('download-app');
                Route::post(VendorRegistrationSetting::DOWNLOAD_APP[URI], 'updateDownloadAppSection');
                Route::get(VendorRegistrationSetting::FAQ[URI], 'getFAQView')->name('faq');
            });
        });
        Route::group(['prefix' => 'vendor-registration-reason', 'as' => 'vendor-registration-reason.','middleware'=>['module:system_settings']], function () {
            Route::controller(VendorRegistrationReasonController::class)->group(function (){
                Route::post(VendorRegistrationReason::ADD[URI], 'add')->name('add');
                Route::get(VendorRegistrationReason::UPDATE[URI], 'getUpdateView')->name('update');
                Route::post(VendorRegistrationReason::UPDATE[URI], 'update');
                Route::post(VendorRegistrationReason::UPDATE_STATUS[URI], 'updateStatus')->name('update-status');
                Route::post(VendorRegistrationReason::DELETE[URI], 'delete')->name('delete');
            });
        });
    });

    Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {

        Route::group(['middleware'=>['module:system_settings']],function () {
            Route::controller(SMSModuleController::class)->group(function (){
                Route::get(SMSModule::VIEW[URI], 'index')->name('sms-module');
                Route::put(SMSModule::UPDATE[URI], 'update')->name('addon-sms-set');
            });
        });


        Route::group(['prefix' => 'shipping-method', 'as' => 'shipping-method.','middleware'=>['module:system_settings']], function () {
            Route::controller(ShippingMethodController::class)->group(function (){
                Route::get(ShippingMethod::INDEX[URI], 'index')->name('index');
                Route::post(ShippingMethod::INDEX[URI], 'add');
                Route::get(ShippingMethod::UPDATE[URI] . '/{id}', 'getUpdateView')->name('update');
                Route::post(ShippingMethod::UPDATE[URI] . '/{id}', 'update');
                Route::post(ShippingMethod::UPDATE_STATUS[URI], 'updateStatus')->name('update-status');
                Route::post(ShippingMethod::DELETE[URI], 'delete')->name('delete');
                Route::post(ShippingMethod::UPDATE_SHIPPING_RESPONSIBILITY[URI], 'updateShippingResponsibility')->name('update-shipping-responsibility');
            });
        });

        Route::group(['prefix' => 'shipping-type', 'as' => 'shipping-type.'], function () {
            Route::post(ShippingType::INDEX[URI], [ShippingTypeController::class, 'addOrUpdate'])->name('index');
        });

        Route::group(['prefix' => 'category-shipping-cost', 'as' => 'category-shipping-cost.','middleware'=>['module:system_settings']], function () {
            Route::controller(CategoryShippingCostController::class)->group(function (){
                Route::post('store', 'add')->name('store');
            });
        });

        Route::group(['prefix' => 'mail', 'as' => 'mail.','middleware'=>['module:system_settings']], function () {
            Route::controller(MailController::class)->group(function (){
                Route::get(Mail::VIEW[URI], 'index')->name('index');
                Route::post(Mail::UPDATE[URI], 'update')->name('update');
                Route::post(Mail::UPDATE_SENDGRID[URI], 'updateSendGrid')->name('update-sendgrid');
                Route::post(Mail::SEND[URI], 'send')->name('send');
            });
        });

        Route::group(['prefix' => 'order-settings', 'as' => 'order-settings.','middleware'=>['module:system_settings']], function () {
            Route::controller(OrderSettingsController::class)->group(function (){
                Route::get(BusinessSettings::ORDER_VIEW[URI], 'index')->name('index');
                Route::post(BusinessSettings::ORDER_UPDATE[URI],'update')->name('update-order-settings');
            });
        });

        Route::group(['prefix' => 'vendor-settings', 'as' => 'vendor-settings.','middleware'=>['module:system_settings']], function () {
            Route::controller(VendorSettingsController::class)->group(function (){
                Route::get(BusinessSettings::VENDOR_VIEW[URI], 'index')->name('index')->middleware('actch');
                Route::post(BusinessSettings::VENDOR_SETTINGS_UPDATE[URI], 'update')->name('update-vendor-settings');
            });
        });

        Route::group(['prefix' => 'delivery-man-settings', 'as' => 'delivery-man-settings.', 'middleware' =>['module:system_settings']], function (){
            Route::controller(DeliverymanSettingsController::class)->group(function (){
                Route::get(BusinessSettings::DELIVERYMAN_VIEW[URI], 'index')->name('index');
                Route::post(BusinessSettings::DELIVERYMAN_VIEW_UPDATE[URI], 'update')->name('update');
            });
        });

        Route::group(['prefix' => 'payment-method', 'as' => 'payment-method.','middleware'=>['module:system_settings']], function () {
            Route::controller(PaymentMethodController::class)->group(function () {
                Route::get(PaymentMethod::LIST[URI], 'index')->name('index')->middleware('actch');
                Route::get(PaymentMethod::PAYMENT_OPTION[URI], 'getPaymentOptionView')->name('payment-option');
                Route::post(PaymentMethod::PAYMENT_OPTION[URI], 'updatePaymentOption');
                Route::put(PaymentMethod::UPDATE_CONFIG[URI], 'UpdatePaymentConfig')->name('addon-payment-set');
            });
        });
        Route::group(['prefix' => 'offline-payment-method', 'as' => 'offline-payment-method.','middleware'=>['module:system_settings']], function () {
            Route::controller(OfflinePaymentMethodController::class)->group(function (){
                Route::get(OfflinePaymentMethod::INDEX[URI], 'index')->name('index')->middleware('actch');
                Route::get(OfflinePaymentMethod::ADD[URI], 'getAddView')->name('add')->middleware('actch');
                Route::post(OfflinePaymentMethod::ADD[URI], 'add')->middleware('actch');
                Route::get(OfflinePaymentMethod::UPDATE[URI].'/{id}', 'getUpdateView')->name('update')->middleware('actch');
                Route::post(OfflinePaymentMethod::UPDATE[URI].'/{id}', 'update')->middleware('actch');
                Route::post(OfflinePaymentMethod::DELETE[URI], 'delete')->name('delete')->middleware('actch');
                Route::post(OfflinePaymentMethod::UPDATE_STATUS[URI], 'updateStatus')->name('update-status')->middleware('actch');
            });
        });


        Route::group(['prefix' => 'delivery-restriction', 'as' => 'delivery-restriction.', 'middleware' =>['module:system_settings']], function (){
            Route::controller(DeliveryRestrictionController::class)->group(function () {
                Route::get(DeliveryRestriction::VIEW[URI], 'index')->name('index');
                Route::post(DeliveryRestriction::ADD[URI], 'add')->name('add-delivery-country');
                Route::delete(DeliveryRestriction::DELETE[URI], 'delete')->name('delivery-country-delete');
                Route::post(DeliveryRestriction::ZIPCODE_ADD[URI], 'addZipCode')->name('add-zip-code');
                Route::delete(DeliveryRestriction::ZIPCODE_DELETE[URI], 'deleteZipCode')->name('zip-code-delete');
                Route::post(DeliveryRestriction::COUNTRY_RESTRICTION[URI], 'countryRestrictionStatusChange')->name('country-restriction-status-change');
                Route::post(DeliveryRestriction::ZIPCODE_RESTRICTION[URI], 'zipcodeRestrictionStatusChange')->name('zipcode-restriction-status-change');
            });
        });

        Route::group(['prefix' => 'email-templates', 'as' => 'email-templates.', 'middleware' =>['module:system_settings']], function (){
            Route::controller(EmailTemplatesController::class)->group(function (){
                Route::get('index', 'index')->name('index');
                Route::get(EmailTemplate::VIEW[URI].'/{type}'.'/{tab}', 'getView')->name('view');
                Route::post(EmailTemplate::UPDATE[URI].'/{type}'.'/{tab}', 'update')->name('update');
                Route::post(EmailTemplate::UPDATE_STATUS[URI].'/{type}'.'/{tab}', 'updateStatus')->name('update-status');

            });
        });

        Route::group(['prefix' => 'priority-setup', 'as' => 'priority-setup.','middleware'=>['module:system_settings']], function () {
            Route::controller(PrioritySetupController::class)->group(function (){
                Route::get(PrioritySetup::INDEX[URI], 'index')->name('index');
                Route::post(PrioritySetup::INDEX[URI], 'update');
            });
        });
    });

    Route::group(['prefix' => 'system-settings', 'as' => 'system-settings.'], function () {
        Route::controller(SoftwareUpdateController::class)->group(function (){
            Route::get(SoftwareUpdate::VIEW[URI],'index')->name('software-update');
            Route::post(SoftwareUpdate::VIEW[URI],'update');
        });
    });

    Route::group(['prefix' => 'currency', 'as' => 'currency.','middleware'=>['module:system_settings']], function () {
        Route::controller(CurrencyController::class)->group(function (){
            Route::get(Currency::LIST[URI], 'index')->name('view')->middleware('actch');
            Route::post(Currency::ADD[URI], 'add')->name('store');
            Route::get(Currency::UPDATE[URI].'/{id}', 'getUpdateView')->name('update');
            Route::post(Currency::UPDATE[URI].'/{id}', 'update');
            Route::post(Currency::DELETE[URI], 'delete')->name('delete');
            Route::post(Currency::STATUS[URI], 'status')->name('status');
            Route::post(Currency::DEFAULT[URI], 'updateSystemCurrency')->name('system-currency-update');
        });
    });

    Route::group(['prefix' => 'addon', 'as' => 'addon.','middleware'=>['module:system_settings']], function () {
        Route::controller(AddonController::class)->group(function (){
            Route::get(AddonSetup::VIEW[URI], 'index')->name('index');
            Route::post(AddonSetup::PUBLISH[URI], 'publish')->name('publish');
            Route::post(AddonSetup::ACTIVATION[URI], 'activation')->name('activation');
            Route::post(AddonSetup::UPLOAD[URI], 'upload')->name('upload');
            Route::post(AddonSetup::DELETE[URI], 'delete')->name('delete');
        });
    });

    Route::group(['prefix' => 'social-login', 'as' => 'social-login.','middleware'=>['module:system_settings']], function () {
        Route::controller(SocialLoginSettingsController::class)->group(function (){
            Route::get(SocialLoginSettings::VIEW[URI], 'index')->name('view');
            Route::post(SocialLoginSettings::UPDATE[URI].'/{service}', 'update')->name('update');
            Route::post(SocialLoginSettings::APPLE_UPDATE[URI].'/{service}', 'updateAppleLogin')->name('update-apple');
        });
    });

    Route::group(['prefix' => 'social-media-chat', 'as' => 'social-media-chat.','middleware'=>['module:system_settings']], function () {
        Route::controller(SocialMediaChatController::class)->group(function (){
            Route::get(SocialMediaChat::VIEW[URI], 'index')->name('view');
            Route::post(SocialMediaChat::UPDATE[URI].'/{service}', 'update')->name('update');
        });
    });

    Route::group(['prefix' => 'product-settings', 'as' => 'product-settings.','middleware'=>['module:system_settings']], function () {
        Route::controller(BusinessSettingsController::class)->group(function (){
            Route::get(BusinessSettings::PRODUCT_SETTINGS[URI], 'getProductSettingsView')->name('index');
            Route::post(BusinessSettings::PRODUCT_SETTINGS[URI], 'updateProductSettings');
        });

        Route::controller(InhouseShopController::class)->group(function (){
            Route::get(InhouseShop::VIEW[URI], 'index')->name('inhouse-shop');
            Route::post(InhouseShop::VIEW[URI], 'update');
            Route::post(InhouseShop::TEMPORARY_CLOSE[URI], 'getTemporaryClose')->name('inhouse-shop-temporary-close');
            Route::post(InhouseShop::VACATION_ADD[URI], 'addVacation')->name('vacation-add');
        });
    });

    Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.','middleware'=>['module:promotion_management']], function () {
        Route::controller(BusinessSettingsController::class)->group(function (){
            Route::get(BusinessSettings::ANNOUNCEMENT[URI], 'getAnnouncementView')->name('announcement');
            Route::post(BusinessSettings::ANNOUNCEMENT[URI], 'updateAnnouncement');
        });
    });

    Route::group(['prefix' => 'file-manager', 'as' => 'file-manager.','middleware'=>['module:system_settings']], function () {
        Route::controller(FileManagerController::class)->group(function (){
            Route::get(FileManager::VIEW[URI].'/{folderPath?}', 'getFoldersView')->name('index');
            Route::get(FileManager::DOWNLOAD[URI].'/{file_name}', 'download')->name('download');
            Route::post(FileManager::IMAGE_UPLOAD[URI], 'upload')->name('image-upload');
        });
    });

    Route::group(['prefix' => 'helpTopic', 'as' => 'helpTopic.','middleware'=>['module:system_settings']], function () {
        Route::controller(HelpTopicController::class)->group(function (){
            Route::get(HelpTopic::LIST[URI], 'index')->name('list');
            Route::post(HelpTopic::ADD[URI], 'add')->name('add-new');
            Route::get(HelpTopic::STATUS[URI].'/{id}', 'updateStatus')->name('status');
            Route::get(HelpTopic::UPDATE[URI].'/{id}', 'getUpdateResponse')->name('update');
            Route::post(HelpTopic::UPDATE[URI].'/{id}', 'update');
            Route::post(HelpTopic::DELETE[URI], 'delete')->name('delete');
        });
    });

    Route::group(['prefix' => 'refund-section', 'as' => 'refund-section.','middleware'=>['module:order_management']], function () {
        Route::group(['prefix' => 'refund', 'as' => 'refund.'], function () {
            Route::controller(RefundController::class)->group(function (){
                Route::get(RefundRequest::LIST[URI].'/{status}', 'index')->name('list');
                Route::get(RefundRequest::EXPORT[URI].'/{status}', 'exportList')->name('export');
                Route::get(RefundRequest::DETAILS[URI].'/{id}', 'getDetailsView')->name('details');
                Route::post(RefundRequest::UPDATE_STATUS[URI], 'updateRefundStatus')->name('refund-status-update');
            });
        });
    });

});

