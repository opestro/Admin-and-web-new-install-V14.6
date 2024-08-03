<?php

namespace App\Traits;

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
use App\Models\EmailTemplate;
use App\Models\Shop;
use App\Repositories\EmailTemplatesRepository;
use App\Services\EmailTemplateService;
use App\Utils\Helpers;
use App\Enums\GlobalConstant;
use App\Http\Controllers\InstallController;
use App\Models\Banner;
use App\Models\Product;
use App\Models\BusinessSetting;
use App\Models\NotificationMessage;
use App\Models\Order;
use App\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait UpdateClass
{
    use EmailTemplateTrait;
    public function insert_data_of($version_number)
    {
        if ($version_number == '13.0') {
            if (BusinessSetting::where(['type' => 'product_brand'])->first() == false) {
                DB::table('business_settings')->updateOrInsert(['type' => 'product_brand'], [
                    'value' => 1
                ]);
            }

            if (BusinessSetting::where(['type' => 'digital_product'])->first() == false) {
                DB::table('business_settings')->updateOrInsert(['type' => 'digital_product'], [
                    'value' => 1
                ]);
            }
        }

        if ($version_number == '13.1') {
            $refund_policy = BusinessSetting::where(['type' => 'refund-policy'])->first();
            if ($refund_policy) {
                $refund_value = json_decode($refund_policy['value'], true);
                if(!isset($refund_value['status'])){
                    BusinessSetting::where(['type' => 'refund-policy'])->update([
                        'value' => json_encode([
                            'status' => 1,
                            'content' => $refund_policy['value'],
                        ]),
                    ]);
                }
            }elseif(!$refund_policy){
                BusinessSetting::insert([
                    'type' => 'refund-policy',
                    'value' => json_encode([
                        'status' => 1,
                        'content' => '',
                    ]),
                ]);
            }

            $return_policy = BusinessSetting::where(['type' => 'return-policy'])->first();
            if ($return_policy) {
                $return_value = json_decode($return_policy['value'], true);
                if(!isset($return_value['status'])){
                    BusinessSetting::where(['type' => 'return-policy'])->update([
                        'value' => json_encode([
                            'status' => 1,
                            'content' => $return_policy['value'],
                        ]),
                    ]);
                }
            }elseif(!$return_policy){
                BusinessSetting::insert([
                    'type' => 'return-policy',
                    'value' => json_encode([
                        'status' => 1,
                        'content' => '',
                    ]),
                ]);
            }

            $cancellation_policy = BusinessSetting::where(['type' => 'cancellation-policy'])->first();
            if ($cancellation_policy) {
                $cancellation_value = json_decode($cancellation_policy['value'], true);
                if(!isset($cancellation_value['status'])){
                    BusinessSetting::where(['type' => 'cancellation-policy'])->update([
                        'value' => json_encode([
                            'status' => 1,
                            'content' => $cancellation_policy['value'],
                        ]),
                    ]);
                }
            }elseif(!$cancellation_policy){
                BusinessSetting::insert([
                    'type' => 'cancellation-policy',
                    'value' => json_encode([
                        'status' => 1,
                        'content' => '',
                    ]),
                ]);
            }

            if (BusinessSetting::where(['type' => 'offline_payment'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'offline_payment',
                    'value' => json_encode([
                        'status' => 0,
                    ]),
                    'updated_at' => now()
                ]);
            }

            if (BusinessSetting::where(['type' => 'temporary_close'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'temporary_close',
                    'value' => json_encode([
                        'status' => 0,
                    ]),
                    'updated_at' => now()
                ]);
            }

            if (BusinessSetting::where(['type' => 'vacation_add'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'vacation_add',
                    'value' => json_encode([
                        'status' => 0,
                        'vacation_start_date' => null,
                        'vacation_end_date' => null,
                        'vacation_note' => null
                    ]),
                    'updated_at' => now()
                ]);
            }

            if (BusinessSetting::where(['type' => 'cookie_setting'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'cookie_setting',
                    'value' => json_encode([
                        'status' => 0,
                        'cookie_text' => null
                    ]),
                    'updated_at' => now()
                ]);
            }

            DB::table('colors')
                ->whereIn('id', [16,38,93])
                ->delete();
        }

        if ($version_number == '14.0') {
            $colors = BusinessSetting::where('type', 'colors')->first();
            if($colors){
                $colors = json_decode($colors->value);
                BusinessSetting::where('type', 'colors')->update([
                    'value' => json_encode(
                        [
                            'primary' => $colors->primary,
                            'secondary' => $colors->secondary,
                            'primary_light' => isset($colors->primary_light) ? $colors->primary_light : '#CFDFFB',
                        ]),
                ]);
            }

            if (BusinessSetting::where(['type' => 'maximum_otp_hit'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'maximum_otp_hit',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            if (BusinessSetting::where(['type' => 'otp_resend_time'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'otp_resend_time',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            if (BusinessSetting::where(['type' => 'temporary_block_time'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'temporary_block_time',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            if (BusinessSetting::where(['type' => 'maximum_login_hit'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'maximum_login_hit',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            if (BusinessSetting::where(['type' => 'temporary_login_block_time'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'temporary_login_block_time',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            //product category id update start
            $products = Product::all();
            foreach($products as $product){
                $categories = json_decode($product->category_ids, true);
                $i = 0;
                foreach($categories as $category){
                    if($i == 0){
                        $product->category_id = $category['id'];
                    }elseif($i == 1){
                        $product->sub_category_id = $category['id'];
                    }elseif($i == 2){
                        $product->sub_sub_category_id = $category['id'];
                    }

                    $product->save();
                    $i++;
                }
            }
            //product category id update end
        }

        if ($version_number == '14.1') {
            // default theme folder delete from resources/views folder start
            $folder = base_path('resources/views');
            $directories = glob($folder . '/*', GLOB_ONLYDIR);
            foreach ($directories as $directory) {
                $array = explode('/', $directory);
                if (File::isDirectory($directory) && in_array(end($array), ['web-views', 'customer-view'])) {
                    File::deleteDirectory($directory);
                }
            }
            $front_end_dir = $folder . "/layouts/front-end";
            if (File::isDirectory($front_end_dir)) {
                File::deleteDirectory($front_end_dir);
            }

            foreach (['home.blade.php', 'welcome.blade.php'] as $file) {
                if (File::exists($folder . '/' . $file)) {
                    unlink($folder . '/' . $file);
                }
            }
            // default theme folder dele from resources/views folder end

            //apple login information insert
            if (BusinessSetting::where(['type' => 'apple_login'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'apple_login',
                    'value' => json_encode([
                        [
                            'login_medium' => 'apple',
                            'client_id' => '',
                            'client_secret' => '',
                            'status' => 0,
                            'team_id' => '',
                            'key_id' => '',
                            'service_file' => '',
                            'redirect_url' => '',
                        ]
                    ]),
                    'updated_at' => now()
                ]);
            }

            //referral code update for existing user
            $customers = User::whereNull('referral_code')->where('id','!=',0)->get();
            foreach($customers as $customer){
                $customer->referral_code = Helpers::generate_referer_code();
                $customer->save();
            }

            if (BusinessSetting::where(['type' => 'ref_earning_status'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'ref_earning_status',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            if (BusinessSetting::where(['type' => 'ref_earning_exchange_rate'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'ref_earning_exchange_rate',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            // new payment module necessary table insert
            try {
                if (!Schema::hasTable('addon_settings')) {
                $sql = File::get(base_path('database/migrations/addon_settings.sql'));
                DB::unprepared($sql);
                }


                if (!Schema::hasTable('payment_requests')) {
                $sql = File::get(base_path('database/migrations/payment_requests.sql'));
                DB::unprepared($sql);
                }

            } catch (\Exception $exception) {
                //
            }

            //existing payment gateway data import from business setting table
            $this->payment_gateway_data_update();
            $this->sms_gateway_data_update();

            // guest checkout add
            if (BusinessSetting::where(['type' => 'guest_checkout'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'guest_checkout',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            // minimum_order_amount
            if (BusinessSetting::where(['type' => 'minimum_order_amount'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'minimum_order_amount',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            if (BusinessSetting::where(['type' => 'minimum_order_amount_by_seller'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'minimum_order_amount_by_seller',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            if (BusinessSetting::where(['type' => 'minimum_order_amount_status'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'minimum_order_amount_status',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            //admin_login_url
            if (BusinessSetting::where(['type' => 'admin_login_url'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'admin_login_url',
                    'value' => 'admin',
                    'updated_at' => now()
                ]);
            }

            //employee_login_url
            if (BusinessSetting::where(['type' => 'employee_login_url'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'employee_login_url',
                    'value' => 'employee',
                    'updated_at' => now()
                ]);
            }

            //free_delivery_status
            if (BusinessSetting::where(['type' => 'free_delivery_status'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'free_delivery_status',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            //free_delivery_responsibility
            if (BusinessSetting::where(['type' => 'free_delivery_responsibility'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'free_delivery_responsibility',
                    'value' => 'admin',
                    'updated_at' => now()
                ]);
            }

            //free_delivery_over_amount
            if (BusinessSetting::where(['type' => 'free_delivery_over_amount'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'free_delivery_over_amount',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            //free_delivery_over_amount
            if (BusinessSetting::where(['type' => 'free_delivery_over_amount_seller'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'free_delivery_over_amount_seller',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            //add_funds_to_wallet
            if (BusinessSetting::where(['type' => 'add_funds_to_wallet'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'add_funds_to_wallet',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            //minimum_add_fund_amount
            if (BusinessSetting::where(['type' => 'minimum_add_fund_amount'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'minimum_add_fund_amount',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            //maximum_add_fund_amount
            if (BusinessSetting::where(['type' => 'maximum_add_fund_amount'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'maximum_add_fund_amount',
                    'value' => 0,
                    'updated_at' => now()
                ]);
            }

            //user_app_version_control
            if (BusinessSetting::where(['type' => 'user_app_version_control'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'user_app_version_control',
                    'value' => json_encode([
                        "for_android" => [
                            "status" => 1,
                            "version" => "14.1",
                            "link" => ""
                        ],
                        "for_ios" => [
                            "status" => 1,
                            "version" => "14.1",
                            "link" => ""
                        ]
                    ]),
                    'updated_at' => now()
                ]);
            }

            //seller_app_version_control
            if (BusinessSetting::where(['type' => 'seller_app_version_control'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'seller_app_version_control',
                    'value' => json_encode([
                        "for_android" => [
                            "status" => 1,
                            "version" => "14.1",
                            "link" => ""
                        ],
                        "for_ios" => [
                            "status" => 1,
                            "version" => "14.1",
                            "link" => ""
                        ]
                    ]),
                    'updated_at' => now()
                ]);
            }

            //Delivery_man_app_version_control
            if (BusinessSetting::where(['type' => 'delivery_man_app_version_control'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'delivery_man_app_version_control',
                    'value' => json_encode([
                        "for_android" => [
                            "status" => 1,
                            "version" => "14.1",
                            "link" => ""
                        ],
                        "for_ios" => [
                            "status" => 1,
                            "version" => "14.1",
                            "link" => ""
                        ]
                    ]),
                    'updated_at' => now()
                ]);
            }

            // script for theme setup for existing banner
            $theme_name = theme_root_path();
            $banners = Banner::get();
            if($banners){
                foreach($banners as $banner){
                    $banner->theme = $theme_name;
                    $banner->save();
                }
            }

            // current shipping responsibility add to orders table
            Order::query()->update(['shipping_responsibility'=>Helpers::get_business_settings('shipping_method')]);

            //whatsapp
            $whatsapp = BusinessSetting::where(['type' => 'whatsapp'])->first();
            if(!$whatsapp) {
                DB::table('business_settings')->insert([
                    'type' => 'whatsapp',
                    'value' => json_encode([
                        "status" => 1,
                        "phone" => "00000000000"
                    ]),
                    'updated_at' => now()
                ]);
            }

            //currency_symbol_position
            $currency_symbol_position = BusinessSetting::where(['type' => 'currency_symbol_position'])->first();
            if(!$currency_symbol_position){
                DB::table('business_settings')->insert([
                    'type' => 'currency_symbol_position',
                    'value' => "left",
                    'updated_at' => now()
                ]);
            }
        }

        if ($version_number == '14.2'){

            // notification message import process
            InstallController::notification_message_import();

            // business table notification message data import in notification message table
            self::notification_message_processing();

            //company riliability import process
            InstallController::company_riliability_import();
        }

        if ($version_number == '14.3'){
            if (BusinessSetting::where(['type' => 'app_activation'])->first() == false) {
                DB::table('business_settings')->updateOrInsert(
                    ['type' => 'app_activation'],
                    [
                        'type' => 'app_activation',
                        'value' => json_encode(['software_id' => '', 'is_active' => 0]),
                        'updated_at' => now()
                ]);
            }
        }

        if ($version_number == '14.3.1'){
            //shop slug
            $shops = Shop::where('slug','en')->get();
            if ($shops) {
                foreach($shops as $shop){
                    $shop->slug = Str::slug($shop->name, '-') . '-' . Str::random(6);
                    $shop->save();
                }
            }

            //translation table data update
            DB::table('translations')->where('translationable_type', 'LIKE', "%Product%")->update(['translationable_type'=>'App\Models\Product']);
            DB::table('translations')->where('translationable_type', 'LIKE', "%Brand%")->update(['translationable_type'=>'App\Models\Brand']);
            DB::table('translations')->where('translationable_type', 'LIKE', "%Category%")->update(['translationable_type'=>'App\Models\Category']);
            DB::table('translations')->where('translationable_type', 'LIKE', "%NotificationMessage%")->update(['translationable_type'=>'App\Models\NotificationMessage']);
        }

        if ($version_number == '14.4') {
            if (!NotificationMessage::where(['key' => 'product_request_approved_message'])->first()) {
                DB::table('notification_messages')->updateOrInsert([
                    'key' => 'product_request_approved_message'
                ],
                    [
                        'user_type' => 'seller',
                        'key' => 'product_request_approved_message',
                        'message' => 'customize your product request approved message message',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }

            if (!NotificationMessage::where(['key' => 'product_request_rejected_message'])->first()) {
                DB::table('notification_messages')->updateOrInsert([
                    'key' => 'product_request_rejected_message'
                ],
                    [
                        'user_type' => 'seller',
                        'key' => 'product_request_rejected_message',
                        'message' => 'customize your product request rejected message message',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        }

        if ($version_number == '14.5') {
            if (BusinessSetting::where(['type' => 'map_api_status'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'map_api_status',
                    'value' => 1,
                    'updated_at' => now()
                ]);
            }
        }

        if ($version_number == '14.6') {
            Product::where(['product_type' => 'digital'])->update(['current_stock' => 999999999]);

            //priority setup and vendor registration data process
            InstallController::getPrioritySetupAndVendorRegistrationData();

            if(Admin::count()>0 && EmailTemplate::count()<1) {
                $emailTemplateUserData = [
                    'admin',
                    'customer',
                    'vendor',
                    'delivery-man',
                ];
                foreach ($emailTemplateUserData as $key => $value) {
                    $this->getEmailTemplateDataForUpdate($value);
                }
            }
        }

        if(DOMAIN_POINTED_DIRECTORY == 'public' && function_exists('shell_exec')) {
            shell_exec('ln -s ../resources/themes themes');
            Artisan::call('storage:link');
        }
    }

    public static function notification_message_processing(){
        $business_notification_message = [
            'order_pending_message',
            'order_confirmation_msg',
            'order_processing_message',
            'out_for_delivery_message',
            'order_delivered_message',
            'order_returned_message',
            'order_failed_message',
            'order_canceled',
            'delivery_boy_assign_message',
            'delivery_boy_expected_delivery_date_message',
        ];

        $messages = BusinessSetting::whereIn('type', $business_notification_message)->get()->toArray();

        $current_notification_message = [
            'order_pending_message',
            'order_confirmation_message',
            'order_processing_message',
            'out_for_delivery_message',
            'order_delivered_message',
            'order_returned_message',
            'order_failed_message',
            'order_canceled',
            'new_order_assigned_message',
            'expected_delivery_date',
        ];

        foreach($messages as $message){
            $data = $message['type'];
            if($data == 'order_confirmation_msg'){
                $data = 'order_confirmation_message';

            }elseif($data == 'delivery_boy_assign_message'){
                $data = 'new_order_assigned_message';

            }elseif($data == 'delivery_boy_expected_delivery_date_message'){
                $data = 'expected_delivery_date';
            }

            $is_true = in_array($data, $current_notification_message);
            $value = json_decode($message['value'], true);

            if($is_true){
                $notification = NotificationMessage::where('key',$data)->first();
                $notification->message = $value['message'];
                $notification->status = $value['status'];
                $notification->save();
            }
        }

        return true;
    }

    private function sms_gateway_data_update(){
        try {
            $gateway = array_merge(Helpers::default_sms_gateways(), [
                        'twilio_sms',
                        'nexmo_sms',
                        '2factor_sms',
                        'msg91_sms',
                        'releans_sms',
                    ]);

            $data = BusinessSetting::whereIn('type',$gateway)->pluck('value','type')->toArray();

            if($data) {
                foreach ($data as $key => $value) {

                    $decoded_value = json_decode($value, true);

                    $gateway = $key;
                    if ($key == 'twilio_sms') {
                        $gateway = 'twilio';
                        $additional_data = [
                            'sid' => $decoded_value['sid'],
                            'messaging_service_sid' => $decoded_value['messaging_service_sid'],
                            'token' => $decoded_value['token'],
                            'from' => $decoded_value['from'],
                            'otp_template' => $decoded_value['otp_template'],
                        ];
                    } elseif ($key == 'nexmo_sms') {
                        $gateway = 'nexmo';
                        $additional_data = [
                            'api_key' => $decoded_value['api_key'],
                            'api_secret' => $decoded_value['api_secret'],
                            'from' => $decoded_value['from'],
                            'otp_template' => $decoded_value['otp_template'],
                        ];
                    } elseif ($key == '2factor_sms') {
                        $gateway = '2factor';
                        $additional_data = [
                            'api_key' => $decoded_value['api_key'],
                        ];
                    } elseif ($key == 'msg91_sms') {
                        $gateway = 'msg91';
                        $additional_data = [
                            'template_id' => $decoded_value['template_id'],
                            'authkey' => $decoded_value['authkey'] ?? '',
                        ];
                    } elseif ($key == 'releans_sms') {
                        $gateway = 'releans';
                        $additional_data = [
                            'api_key' => $decoded_value['api_key'],
                            'from' => $decoded_value['from'],
                            'otp_template' => $decoded_value['otp_template'],
                        ];
                    }

                    $default_data = [
                        'gateway' => $gateway,
                        'mode' => 'live',
                        'status' => $decoded_value['status'] ?? 0
                    ];

                    $credentials = json_encode(array_merge($default_data, $additional_data));

                    $payment_additional_data = [
                        'gateway_title' => ucfirst(str_replace('_', ' ', $gateway)),
                        'gateway_image' => null
                    ];

                    DB::table('addon_settings')->updateOrInsert(['key_name' => $gateway, 'settings_type' => 'sms_config'], [
                        'key_name' => $gateway,
                        'live_values' => $credentials,
                        'test_values' => $credentials,
                        'settings_type' => 'sms_config',
                        'mode' => isset($decoded_value['status']) == 1 ? 'live' : 'test',
                        'is_active' => isset($decoded_value['status']) == 1 ? 1 : 0,
                        'additional_data' => json_encode($payment_additional_data),
                    ]);


                }
                BusinessSetting::whereIn('type', $gateway)->delete();
            }
        } catch (\Exception $exception) {
            dd($exception);
        }
        return true;
    }

    private function payment_gateway_data_update(){
        try{
            $gateway[] = ['ssl_commerz_payment'];

            $data = BusinessSetting::whereIn('type', GlobalConstant::DEFAULT_PAYMENT_GATEWAYS)->pluck('value','type')->toArray();

            if($data){
                foreach($data as $key => $value)
                {
                    $gateway = $key;
                    if($key == 'ssl_commerz_payment' ){
                        $gateway = 'ssl_commerz';
                    }

                    $decoded_value = json_decode($value , true);
                    $data = [
                        'gateway' => $gateway ,
                        'mode' =>  isset($decoded_value['status']) == 1  ?  'live': 'test'
                    ];

                    if ($gateway == 'ssl_commerz') {
                        $additional_data = [
                            'status' => $decoded_value['status'],
                            'store_id' => $decoded_value['store_id'],
                            'store_password' => $decoded_value['store_password'],
                        ];
                    } elseif ($gateway == 'paypal') {
                        $additional_data = [
                            'status' => $decoded_value['status'],
                            'client_id' => $decoded_value['paypal_client_id'],
                            'client_secret' => $decoded_value['paypal_secret'],
                        ];
                    } elseif ($gateway == 'stripe') {
                        $additional_data = [
                            'status' => $decoded_value['status'],
                            'api_key' => $decoded_value['api_key'],
                            'published_key' => $decoded_value['published_key'],
                        ];
                    } elseif ($gateway == 'razor_pay') {
                        $additional_data = [
                            'status' => $decoded_value['status'],
                            'api_key' => $decoded_value['razor_key'],
                            'api_secret' => $decoded_value['razor_secret'],
                        ];
                    } elseif ($gateway == 'senang_pay') {
                        $additional_data = [
                            'status' => $decoded_value['status'],
                            'callback_url' => null,
                            'secret_key' => $decoded_value['secret_key'],
                            'merchant_id' => $decoded_value['merchant_id'],
                        ];
                    } elseif ($gateway == 'paytabs') {
                        $additional_data = [
                            'status' => $decoded_value['status'],
                            'profile_id' => $decoded_value['profile_id'],
                            'server_key' => $decoded_value['server_key'],
                            'base_url' => $decoded_value['base_url'],
                        ];
                    } elseif ($gateway == 'paystack') {
                        $additional_data = [
                            'status' => $decoded_value['status'],
                            'callback_url' => $decoded_value['paymentUrl'],
                            'public_key' => $decoded_value['publicKey'],
                            'secret_key' => $decoded_value['secretKey'],
                            'merchant_email' => $decoded_value['merchantEmail'],
                        ];
                    } elseif ($gateway == 'paymob_accept') {
                        $additional_data = [
                            'status' => $decoded_value['status'],
                            'callback_url' => null,
                            'api_key' => $decoded_value['api_key'],
                            'iframe_id' => $decoded_value['iframe_id'],
                            'integration_id' => $decoded_value['integration_id'],
                            'hmac' => $decoded_value['hmac'],
                        ];
                    } elseif ($gateway == 'mercadopago') {
                        $additional_data = [
                            'status' => $decoded_value['status'],
                            'access_token' => $decoded_value['access_token'],
                            'public_key' => $decoded_value['public_key'],
                        ];
                    } elseif ($gateway == 'liqpay') {
                        $additional_data = [
                            'status' => $decoded_value['status'],
                            'private_key' => $decoded_value['public_key'],
                            'public_key' => $decoded_value['private_key'],
                        ];
                    } elseif ($gateway == 'flutterwave') {
                        $additional_data = [
                            'status' => $decoded_value['status'],
                            'secret_key' => $decoded_value['secret_key'],
                            'public_key' => $decoded_value['public_key'],
                            'hash' => $decoded_value['hash'],
                        ];
                    } elseif ($gateway == 'paytm') {
                        $additional_data = [
                            'status' => $decoded_value['status'],
                            'merchant_key' => $decoded_value['paytm_merchant_key'],
                            'merchant_id' => $decoded_value['paytm_merchant_mid'],
                            'merchant_website_link' => $decoded_value['paytm_merchant_website'],
                        ];
                    } elseif ($gateway == 'bkash') {
                        $additional_data = [
                            'status' => $decoded_value['status'],
                            'app_key' => $decoded_value['api_key'],
                            'app_secret' => $decoded_value['api_secret'],
                            'username' => $decoded_value['username'],
                            'password' => $decoded_value['password'],
                        ];
                    }

                    $credentials= json_encode(array_merge($data, $additional_data));

                    $payment_additional_data=['gateway_title' => ucfirst(str_replace('_',' ',$gateway)),
                        'gateway_image' => null];


                    DB::table('addon_settings')->updateOrInsert(['key_name' => $gateway, 'settings_type' => 'payment_config'], [
                        'key_name' => $gateway,
                        'live_values' => $credentials,
                        'test_values' => $credentials,
                        'settings_type' => 'payment_config',
                        'mode' => isset($decoded_value['status']) && $decoded_value['status'] == '1'  ?  'live': 'test',
                        'is_active' => isset($decoded_value['status']) && $decoded_value['status'] == '1'  ?  1: 0 ,
                        'additional_data' => json_encode($payment_additional_data),
                    ]);
                }
                BusinessSetting::whereIn('type', GlobalConstant::DEFAULT_PAYMENT_GATEWAYS)->delete();
            }
        } catch (\Exception $exception) {

        }
        return true;
    }

}
