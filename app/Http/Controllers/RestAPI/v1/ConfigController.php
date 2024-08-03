<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Color;
use App\Models\Currency;
use App\Models\HelpTopic;
use App\Models\ShippingType;
use App\Models\Tag;
use App\Utils\Helpers;
use App\Utils\ProductManager;
use function App\Utils\payment_gateways;

class ConfigController extends Controller
{
    public function configuration()
    {
        $currency = Currency::all();
        $social_login = [];
        foreach (Helpers::get_business_settings('social_login') as $social) {
            $config = [
                'login_medium' => $social['login_medium'],
                'status' => (boolean)$social['status']
            ];
            array_push($social_login, $config);
        }

        foreach (Helpers::get_business_settings('apple_login') as $social) {
            $config = [
                'login_medium' => $social['login_medium'],
                'status' => (boolean)$social['status']
            ];
            array_push($social_login, $config);
        }

        $languages = Helpers::get_business_settings('pnc_language');
        $lang_array = [];
        foreach ($languages as $language) {
            array_push($lang_array, [
                'code' => $language,
                'name' => Helpers::get_language_name($language)
            ]);
        }

        $offline_payment = null;
        $offline_payment_status = Helpers::get_business_settings('offline_payment')['status'] == 1 ?? 0;
        if($offline_payment_status){
            $offline_payment = [
                'name' => 'offline_payment',
                'image' => dynamicAsset(path: 'public/assets/back-end/img/pay-offline.png'),
            ];
        }

        $payment_methods = payment_gateways();
        $payment_methods->map(function ($payment) {
            $payment->additional_datas = json_decode($payment->additional_data);

            unset(
                $payment->additional_data,
                $payment->live_values,
                $payment->test_values,
                $payment->id,
                $payment->settings_type,
                $payment->mode,
                $payment->is_active,
                $payment->created_at,
                $payment->updated_at
            );
        });


        $admin_shipping = ShippingType::where('seller_id',0)->first();
        $shipping_type = isset($admin_shipping)==true?$admin_shipping->shipping_type:'order_wise';

        $company_logo = dynamicStorage(path: "storage/app/public/company/").'/'.BusinessSetting::where(['type'=>'company_web_logo'])->first()->value;
        $company_fav_icon = dynamicStorage(path: "storage/app/public/company/").'/'.BusinessSetting::where(['type'=>'company_fav_icon'])->first()->value;
        $companyShopBanner = getWebConfig(name: 'shop_banner');
        $company_cover_image = dynamicStorage(path: "storage/app/public/shop/").'/'.$companyShopBanner;

        return response()->json([
            'brand_setting' => BusinessSetting::where('type', 'product_brand')->first()->value,
            'digital_product_setting' => BusinessSetting::where('type', 'digital_product')->first()->value,
            'system_default_currency' => (int)Helpers::get_business_settings('system_default_currency'),
            'digital_payment' => (boolean)Helpers::get_business_settings('digital_payment')['status'] ?? 0,
            'cash_on_delivery' => (boolean)Helpers::get_business_settings('cash_on_delivery')['status'] ?? 0,
            'seller_registration' => BusinessSetting::where('type', 'seller_registration')->first()->value,
            'pos_active' => BusinessSetting::where('type','seller_pos')->first()->value,
            'company_phone' => Helpers::get_business_settings('company_phone'),
            'company_email' => Helpers::get_business_settings('company_email'),
            'company_logo' => $company_logo,
            'company_cover_image' => $company_cover_image,
            'company_name' => getWebConfig(name: 'company_name'),
            'company_fav_icon' => $company_fav_icon,
            'delivery_country_restriction' => Helpers::get_business_settings('delivery_country_restriction'),
            'delivery_zip_code_area_restriction' => Helpers::get_business_settings('delivery_zip_code_area_restriction'),
            'base_urls' => [
                'product_image_url' => ProductManager::product_image_path('product'),
                'product_thumbnail_url' => ProductManager::product_image_path('thumbnail'),
                'digital_product_url' => dynamicStorage(path: 'storage/app/public/product/digital-product'),
                'brand_image_url' => dynamicStorage(path: 'storage/app/public/brand'),
                'customer_image_url' => dynamicStorage(path: 'storage/app/public/profile'),
                'banner_image_url' => dynamicStorage(path: 'storage/app/public/banner'),
                'category_image_url' => dynamicStorage(path: 'storage/app/public/category'),
                'review_image_url' => dynamicStorage(path: 'storage/app/public'),
                'seller_image_url' => dynamicStorage(path: 'storage/app/public/seller'),
                'shop_image_url' => dynamicStorage(path: 'storage/app/public/shop'),
                'notification_image_url' => dynamicStorage(path: 'storage/app/public/notification'),
                'delivery_man_image_url' => dynamicStorage(path: 'storage/app/public/delivery-man'),
                'support_ticket_image_url' => dynamicStorage(path: 'storage/app/public/support-ticket'),
                'chatting_image_url' => dynamicStorage(path: 'storage/app/public/chatting'),
            ],
            'static_urls' => [
                'contact_us' => route('contacts'),
                'brands' => route('brands'),
                'categories' => route('categories'),
                'customer_account' => route('user-account'),
            ],
            'about_us' => Helpers::get_business_settings('about_us'),
            'privacy_policy' => Helpers::get_business_settings('privacy_policy'),
            'faq' => HelpTopic::all(),
            'terms_&_conditions' => Helpers::get_business_settings('terms_condition'),
            'refund_policy' => Helpers::get_business_settings('refund-policy'),
            'return_policy' => Helpers::get_business_settings('return-policy'),
            'cancellation_policy' => Helpers::get_business_settings('cancellation-policy'),
            'currency_list' => $currency,
            'currency_symbol_position' => Helpers::get_business_settings('currency_symbol_position') ?? 'right',
            'business_mode'=> Helpers::get_business_settings('business_mode'),
            'maintenance_mode' => (boolean)Helpers::get_business_settings('maintenance_mode') ?? 0,
            'language' => $lang_array,
            'colors' => Color::all(),
            'unit' => Helpers::units(),
            'shipping_method' => Helpers::get_business_settings('shipping_method'),
            'email_verification' => (boolean)Helpers::get_business_settings('email_verification'),
            'phone_verification' => (boolean)Helpers::get_business_settings('phone_verification'),
            'country_code' => Helpers::get_business_settings('country_code'),
            'social_login' => $social_login,
            'currency_model' => Helpers::get_business_settings('currency_model'),
            'forgot_password_verification' => Helpers::get_business_settings('forgot_password_verification'),
            'announcement'=> Helpers::get_business_settings('announcement'),
            'pixel_analytics'=> Helpers::get_business_settings('pixel_analytics'),
            'software_version'=>env('SOFTWARE_VERSION'),
            'decimal_point_settings'=>Helpers::get_business_settings('decimal_point_settings'),
            'inhouse_selected_shipping_type'=>$shipping_type,
            'billing_input_by_customer'=>Helpers::get_business_settings('billing_input_by_customer'),
            'minimum_order_limit'=>Helpers::get_business_settings('minimum_order_limit'),
            'wallet_status'=>Helpers::get_business_settings('wallet_status'),
            'loyalty_point_status'=>Helpers::get_business_settings('loyalty_point_status'),
            'loyalty_point_exchange_rate'=>Helpers::get_business_settings('loyalty_point_exchange_rate'),
            'loyalty_point_minimum_point'=>Helpers::get_business_settings('loyalty_point_minimum_point'),
            'payment_methods' => $payment_methods,
            'offline_payment' => $offline_payment,
            'payment_method_image_path' => dynamicStorage(path: 'storage/app/public/payment_modules/gateway_image'),
            'ref_earning_status' => BusinessSetting::where('type', 'ref_earning_status')->first()->value ?? 0,
            'active_theme' => theme_root_path(),
            'popular_tags'=>Tag::orderBy('visit_count', 'desc')->take(15)->get(),
            'guest_checkout'=>Helpers::get_business_settings('guest_checkout'),
            'upload_picture_on_delivery'=>Helpers::get_business_settings('upload_picture_on_delivery'),
            'user_app_version_control'=>Helpers::get_business_settings('user_app_version_control'),
            'seller_app_version_control'=>Helpers::get_business_settings('seller_app_version_control'),
            'delivery_man_app_version_control'=>Helpers::get_business_settings('delivery_man_app_version_control'),
            'add_funds_to_wallet'=>Helpers::get_business_settings('add_funds_to_wallet'),
            'minimum_add_fund_amount'=>Helpers::get_business_settings('minimum_add_fund_amount'),
            'maximum_add_fund_amount'=>Helpers::get_business_settings('maximum_add_fund_amount'),
            'inhouse_temporary_close'=>Helpers::get_business_settings('temporary_close'),
            'inhouse_vacation_add'=>Helpers::get_business_settings('vacation_add'),
            'free_delivery_status'=>Helpers::get_business_settings('free_delivery_status'),
            'free_delivery_over_amount'=>Helpers::get_business_settings('free_delivery_over_amount'),
            'free_delivery_responsibility'=>Helpers::get_business_settings('free_delivery_responsibility'),
            'free_delivery_over_amount_seller'=>Helpers::get_business_settings('free_delivery_over_amount_seller'),
            'minimum_order_amount_status'=> Helpers::get_business_settings('minimum_order_amount_status'),
            'minimum_order_amount'=> Helpers::get_business_settings('minimum_order_amount'),
            'minimum_order_amount_by_seller'=> Helpers::get_business_settings('minimum_order_amount_by_seller'),
            'order_verification'=> Helpers::get_business_settings('order_verification'),
            'referral_customer_signup_url'=> route('home').'?referral_code=',
            'system_timezone'=> getWebConfig(name: 'timezone'),
            'refund_day_limit'=> getWebConfig(name: 'refund_day_limit'),
            'map_api_status'=> getWebConfig(name: 'map_api_status'),
            'default_location'=> getWebConfig(name: 'default_location'),
        ]);
    }
}

