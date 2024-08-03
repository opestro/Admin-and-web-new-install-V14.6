<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Brand;
use App\Models\EmailTemplate;
use App\Models\HelpTopic;
use App\Models\Product;
use App\Models\VendorRegistrationReason;
use App\Traits\EmailTemplateTrait;
use App\Utils\Helpers;
use App\Models\ShippingType;
use App\Models\BusinessSetting;
use App\Models\NotificationMessage;
use App\Traits\ActivationClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class InstallController extends Controller
{
    use ActivationClass,EmailTemplateTrait;

    public function step0()
    {
        return view('installation.step0');
    }

    public function step1()
    {
        $permission['curl_enabled'] = function_exists('curl_version');
        $permission['db_file_write_perm'] = is_writable(base_path('.env'));
        $permission['routes_file_write_perm'] = is_writable(base_path('app/Providers/RouteServiceProvider.php'));
        return view('installation.step1', compact('permission'));
    }

    public function step2()
    {
        return view('installation.step2');
    }

    public function step3()
    {
        return view('installation.step3');
    }

    public function step4()
    {
        return view('installation.step4');
    }

    public function step5()
    {
        //start symlink
        if(DOMAIN_POINTED_DIRECTORY == 'public' && function_exists('shell_exec')) {
            shell_exec('ln -s ../resources/themes themes');
            Artisan::call('storage:link');
        }
        //end symlink

        Artisan::call('config:cache');
        Artisan::call('config:clear');
        return view('installation.step5');
    }

    public function purchase_code(Request $request)
    {
        Helpers::setEnvironmentValue('SOFTWARE_ID', 'MzE0NDg1OTc=');
        Helpers::setEnvironmentValue('BUYER_USERNAME', $request['username']);
        Helpers::setEnvironmentValue('PURCHASE_CODE', $request['purchase_key']);

        $post = [
            'name' => $request['name'],
            'email' => $request['email'],
            'username' => $request['username'],
            'purchase_key' => $request['purchase_key'],
            'domain' => preg_replace("#^[^:/.]*[:/]+#i", "", url('/')),
        ];
        $response = $this->dmvf($post);

        return redirect($response . '?token=' . bcrypt('step_3'));
    }

    public function system_settings(Request $request)
    {
        DB::table('admins')->insertOrIgnore([
            'name' => $request['admin_name'],
            'email' => $request['admin_email'],
            'admin_role_id' => 1,
            'password' => bcrypt($request['admin_password']),
            'phone' => $request['admin_phone'],
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'company_name'], [
            'value' => $request['company_name']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'currency_model'], [
            'value' => $request['currency_model']
        ]);

        DB::table('admin_wallets')->insert([
            'admin_id' => 1,
            'withdrawn' => 0,
            'commission_earned' => 0,
            'inhouse_earning' => 0,
            'delivery_charge_earned' => 0,
            'pending_amount' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'product_brand'], [
            'value' => 1
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'digital_product'], [
            'value' => 1
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'delivery_boy_expected_delivery_date_message'], [
            'value' => json_encode([
                'status' => 0,
                'message' => ''
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_canceled'], [
            'value' => json_encode([
                'status' => 0,
                'message' => ''
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'offline_payment'], [
            'value' => json_encode([
                'status' => 0
            ])
        ]);

        $refund_policy = BusinessSetting::where(['type' => 'refund-policy'])->first();
        if ($refund_policy) {
            $refund_value = json_decode($refund_policy['value'], true);
            if (!isset($refund_value['status'])) {
                BusinessSetting::where(['type' => 'refund-policy'])->update([
                    'value' => json_encode([
                        'status' => 1,
                        'content' => $refund_policy['value'],
                    ]),
                ]);
            }
        } elseif (!$refund_policy) {
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
            if (!isset($return_value['status'])) {
                BusinessSetting::where(['type' => 'return-policy'])->update([
                    'value' => json_encode([
                        'status' => 1,
                        'content' => $return_policy['value'],
                    ]),
                ]);
            }
        } elseif (!$return_policy) {
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
            if (!isset($cancellation_value['status'])) {
                BusinessSetting::where(['type' => 'cancellation-policy'])->update([
                    'value' => json_encode([
                        'status' => 1,
                        'content' => $cancellation_policy['value'],
                    ]),
                ]);
            }
        } elseif (!$cancellation_policy) {
            BusinessSetting::insert([
                'type' => 'cancellation-policy',
                'value' => json_encode([
                    'status' => 1,
                    'content' => '',
                ]),
            ]);
        }

        DB::table('business_settings')->updateOrInsert(['type' => 'temporary_close'], [
            'type' => 'temporary_close',
            'value' => json_encode([
                'status' => 0,
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'vacation_add'], [
            'type' => 'vacation_add',
            'value' => json_encode([
                'status' => 0,
                'vacation_start_date' => null,
                'vacation_end_date' => null,
                'vacation_note' => null
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'cookie_setting'], [
            'type' => 'cookie_setting',
            'value' => json_encode([
                'status' => 0,
                'cookie_text' => null
            ])
        ]);

        DB::table('colors')
            ->whereIn('id', [16, 38, 93])
            ->delete();

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

        DB::table('business_settings')->updateOrInsert(['type' => 'ref_earning_status'],
            [
                'type' => 'ref_earning_status',
                'value' => 0,
                'updated_at' => now()
            ]
        );

        DB::table('business_settings')->updateOrInsert(['type' => 'ref_earning_exchange_rate'],
            [
                'type' => 'ref_earning_exchange_rate',
                'value' => 0,
                'updated_at' => now()
            ]);

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

            $this->set_data();


        } catch (\Exception $exception) {
            //
        }

        // guest checkout add
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'guest_checkout',
//            'value' => 0,
//            'updated_at' => now()
//        ]);
//
//        // minimum_order_amount
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'minimum_order_amount',
//            'value' => 0,
//            'updated_at' => now()
//        ]);
//
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'minimum_order_amount_by_seller',
//            'value' => 0,
//            'updated_at' => now()
//        ]);
//
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'minimum_order_amount_status',
//            'value' => 0,
//            'updated_at' => now()
//        ]);
//
//        //admin_login_url
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'admin_login_url',
//            'value' => 'admin',
//            'updated_at' => now()
//        ]);
//
//        //employee_login_url
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'employee_login_url',
//            'value' => 'employee',
//            'updated_at' => now()
//        ]);
//
//        //free_delivery_status
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'free_delivery_status',
//            'value' => 0,
//            'updated_at' => now()
//        ]);
//
//        //free_delivery_responsibility
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'free_delivery_responsibility',
//            'value' => 'admin',
//            'updated_at' => now()
//        ]);
//
//        //free_delivery_over_amount
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'free_delivery_over_amount',
//            'value' => 0,
//            'updated_at' => now()
//        ]);
//
//        //free_delivery_over_amount
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'free_delivery_over_amount_seller',
//            'value' => 0,
//            'updated_at' => now()
//        ]);
//
//        //add_funds_to_wallet
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'add_funds_to_wallet',
//            'value' => 0,
//            'updated_at' => now()
//        ]);
//
//        //minimum_add_fund_amount
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'minimum_add_fund_amount',
//            'value' => 0,
//            'updated_at' => now()
//        ]);
//
//        //maximum_add_fund_amount
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'maximum_add_fund_amount',
//            'value' => 0,
//            'updated_at' => now()
//        ]);
//
//        //user_app_version_control
//        DB::table('business_settings')->updateOrInsert([
//            'type' => 'user_app_version_control',
//            'value' => 0,
//            'updated_at' => now()
//        ]);
//
//        //user_app_version_control
//        DB::table('business_settings')->insert([
//            'type' => 'user_app_version_control',
//            'value' => json_encode([
//                "for_android" => [
//                    "status" => 1,
//                    "version" => "14.1",
//                    "link" => ""
//                ],
//                "for_ios" => [
//                    "status" => 1,
//                    "version" => "14.1",
//                    "link" => ""
//                ]
//            ]),
//            'updated_at' => now()
//        ]);
//
//        //seller_app_version_control
//        DB::table('business_settings')->insert([
//            'type' => 'seller_app_version_control',
//            'value' => json_encode([
//                "for_android" => [
//                    "status" => 1,
//                    "version" => "14.1",
//                    "link" => ""
//                ],
//                "for_ios" => [
//                    "status" => 1,
//                    "version" => "14.1",
//                    "link" => ""
//                ]
//            ]),
//            'updated_at' => now()
//        ]);
//
//        //Delivery_man_app_version_control
//        DB::table('business_settings')->insert([
//            'type' => 'delivery_man_app_version_control',
//            'value' => json_encode([
//                "for_android" => [
//                    "status" => 1,
//                    "version" => "14.1",
//                    "link" => ""
//                ],
//                "for_ios" => [
//                    "status" => 1,
//                    "version" => "14.1",
//                    "link" => ""
//                ]
//            ]),
//            'updated_at' => now()
//        ]);
//
//        //whatsapp
//        DB::table('business_settings')->insert([
//            'type' => 'whatsapp',
//            'value' => json_encode([
//                "status"=>1,
//                "phone"=>"00000000000"
//                ]),
//            'updated_at' => now()
//        ]);
//
//        //currency_symbol_position
//        DB::table('business_settings')->insert([
//            'type' => 'currency_symbol_position',
//            'value' => "left",
//            'updated_at' => now()
//        ]);

        // data insert into shipping table
        $new_shipping_type = new ShippingType;
        $new_shipping_type->seller_id = 0;
        $new_shipping_type->shipping_type = 'order_wise';
        $new_shipping_type->save();

        self::notification_message_import(); // notification message add in the new table
        self::company_riliability_import(); // company riliability add in the new table

        DB::table('business_settings')->updateOrInsert(['type' => 'app_activation',],
            [
                'type' => 'app_activation',
                'value' => json_encode([
                    'software_id' => '',
                    'is_active' => 0
                ]),
                'updated_at' => now()
            ]
        );

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

        DB::table('business_settings')->updateOrInsert(['type' => 'map_api_status',],
            [
                'type' => 'map_api_status',
                'value' => 1,
                'updated_at' => now()
            ]
        );

        //priority setup and vendor registration data process
        $this->getPrioritySetupAndVendorRegistrationData();

        if(Admin::count()>0 && EmailTemplate::count()<1){
            $emailTemplateUserData = [
                'admin',
                'customer',
                'vendor',
                'delivery-man',
            ];
            foreach ($emailTemplateUserData as $key=>$value){
                $this->getEmailTemplateDataForUpdate($value);
            }
        }

        $previousRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);
        //sleep(5);
        return view('installation.step6');
    }

    public static function getPrioritySetupAndVendorRegistrationData()
    {
        if (BusinessSetting::where(['type' => 'vendor_registration_header'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(["type" => "vendor_registration_header"], [
                "value" => json_encode([
                    "title" => "Vendor Registration",
                    "sub_title" => "Create your own store.Already have store?",
                    "image" => ""
                ]),
            ]);
        }

        if (BusinessSetting::where(['type' => 'vendor_registration_sell_with_us'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(["type" => "vendor_registration_sell_with_us"], [
                "value" => json_encode([
                    "title" => "Why Sell With Us",
                    "sub_title" => "Boost your sales! Join us for a seamless, profitable experience with vast buyer reach and top-notch support. Sell smarter today!",
                    "image" => ""
                ]),
            ]);
        }

        if (BusinessSetting::where(['type' => 'download_vendor_app'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(["type" => "download_vendor_app"], [
                "value" => json_encode([
                    "title" => "Download Free Vendor App",
                    "sub_title" => "Download our free seller app and start reaching millions of buyers on the go! Easy setup, manage listings, and boost sales anywhere.",
                    "image" => null,
                    "download_google_app" => null,
                    "download_google_app_status" => 0,
                    "download_apple_app" => null,
                    "download_apple_app_status" => 0,
                ]),
            ]);
        }

        if (BusinessSetting::where(['type' => 'business_process_main_section'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(["type" => "business_process_main_section"], [
                "value" => json_encode([
                    "title" => "3 Easy Steps To Start Selling",
                    "sub_title" => "Start selling quickly! Register, upload your products with detailed info and images, and reach millions of buyers instantly.",
                    "image" => ""
                ]),
            ]);
        }

        if (BusinessSetting::where(['type' => 'business_process_step'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(["type" => "business_process_step"], [
                "value" => json_encode(
                    [
                        [
                            "title" => "Get Registered",
                            "description" => "Sign up easily and create your seller account in just a few minutes. It's fast and simple to get started.",
                            "image" => "",
                        ],
                        [
                            "title" => "Upload Products",
                            "description" => "List your products with detailed descriptions and high-quality images to attract more buyers effortlessly.",
                            "image" => "",
                        ],
                        [
                            "title" => "Start Selling",
                            "description" => "Go live and start reaching millions of potential buyers immediately. Watch your sales grow with our vast audience.",
                            "image" => "",
                        ]
                    ]
                ),
            ]);
        }

        //registration data insert start
        $vendorRegistrationReason = [
            [
                "title" => "Millions of Users",
                "description" => "Access a vast audience with millions of active users ready to buy your products.",
                "priority" => 1,
                "status" => 1,
            ],
            [
                "title" => "Free Marketing",
                "description" => "Benefit from our extensive, no-cost marketing efforts to boost your visibility and sales.",
                "priority" => 2,
                "status" => 1,
            ],
            [
                "title" => "SEO Friendly",
                "description" => "Enjoy enhanced search visibility with our SEO-friendly platform, driving more traffic to your listings.",
                "priority" => 3,
                "status" => 1,
            ],
            [
                "title" => "24/7 Support",
                "description" => "Get round-the-clock support from our dedicated team to resolve any issues and assist you anytime.",
                "priority" => 4,
                "status" => 1,
            ],
            [
                "title" => "Easy Onboarding",
                "description" => "Start selling quickly with our user-friendly onboarding process designed to get you up and running fast.",
                "priority" => 5,
                "status" => 1,
            ],
        ];

        if (VendorRegistrationReason::count()<1) {
            foreach($vendorRegistrationReason as $reason){
                DB::table('vendor_registration_reasons')->updateOrInsert(["title" => $reason['title']], [
                    "description" => $reason['description'],
                    "priority" => $reason['priority'],
                    "status" => $reason['status'],
                ]);
            }
        }
        //registration data insert end


        //faq for vendor registration start
        $faqVendorRegistration = [
            [
                "type" => "vendor_registration",
                "question" => "How do I register as a seller?",
                "answer" => 'To register, click on the "Sign Up" button, fill in your details, and verify your account via email.',
                'ranking' => 1,
                'status' => 1,
            ],
            [
                'type' => 'vendor_registration',
                'question' => 'What are the fees for selling?',
                'answer' => 'Our platform charges a small commission on each sale. There are no upfront listing fees.',
                'ranking' => 2,
                'status' => 1,
            ],
            [
                'type' => 'vendor_registration',
                'question' => 'How do I upload products?',
                'answer' => 'Log in to your seller account, go to the "Upload Products" section, and fill in the product details and images.',
                'ranking' => 3,
                'status' => 1,
            ],
            [
                'type' => 'vendor_registration',
                'question' => 'How do I handle customer inquiries?',
                'answer' => "You can manage customer inquiries directly through our platform's messaging system, ensuring quick and efficient communication.",
                'ranking' => 4,
                'status' => 1,
            ],
        ];

        if(HelpTopic::where('type', 'vendor_registration')->count()<5){
            foreach($faqVendorRegistration as $faq){
                DB::table('help_topics')->insert([
                    "type" => $faq['type'],
                    "question" => $faq['question'],
                    "answer" => $faq['answer'],
                    "ranking" => $faq['ranking'],
                    "status" => $faq['status'],
                ]);
            }
        }
        //faq for vendor registration start



        Product::where(['product_type' => 'digital'])->update(['current_stock' => 999999999]);
        $prioritySetupKeyArray = [
            'brand_list_priority',
            'category_list_priority',
            'vendor_list_priority',
            'flash_deal_priority',
            'featured_product_priority',
            'feature_deal_priority',
            'new_arrival_product_list_priority',
            'top_vendor_list_priority',
            'category_wise_product_list_priority',
            'top_rated_product_list_priority',
            'best_selling_product_list_priority',
            'searched_product_list_priority',
            'vendor_product_list_priority'
        ];
        foreach ($prioritySetupKeyArray as $key=>$value){
            if (BusinessSetting::where(['type' => $value])->first() == false) {
                DB::table('business_settings')->updateOrInsert(['type' => $value],
                    [
                        'type' => $value,
                        'value' => '',
                        'created_at' => now(),
                        'updated_at' => now(),

                    ]
                );
            }
        }
    }

    public static function notification_message_import()
    {
        /** for customer */
        $user_type_customer = NotificationMessage::where('user_type', 'customer')->get();
        $array_for_customer_message_key = [
            'order_pending_message',
            'order_confirmation_message',
            'order_processing_message',
            'out_for_delivery_message',
            'order_delivered_message',
            'order_returned_message',
            'order_failed_message',
            'order_canceled',
            'order_refunded_message',
            'refund_request_canceled_message',
            'message_from_delivery_man',
            'message_from_seller',
            'fund_added_by_admin_message',
        ];
        foreach ($array_for_customer_message_key as $key => $value) {
            $key_check = $user_type_customer->where('key', $value)->first();
            if ($key_check == null) {
                DB::table('notification_messages')->updateOrInsert(['user_type' => 'customer'],
                    [
                        'user_type' => 'customer',
                        'key' => $value,
                        'message' => 'customize your' . ' ' . str_replace('_', ' ', $value) . ' ' . 'message',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
        /**end for customer*/

        $user_type_seller = NotificationMessage::where('user_type', 'seller')->get();
        $array_for_seller_message_key = [
            'new_order_message',
            'refund_request_message',
            'order_edit_message',
            'withdraw_request_status_message',
            'message_from_customer',
            'delivery_man_assign_by_admin_message',
            'order_delivered_message',
            'order_canceled',
            'order_refunded_message',
            'refund_request_canceled_message',
            'refund_request_status_changed_by_admin',

        ];
        foreach ($array_for_seller_message_key as $key => $value) {
            $key_check = $user_type_seller->where('key', $value)->first();
            if ($key_check == null) {
                DB::table('notification_messages')->insert([
                    'user_type' => 'seller',
                    'key' => $value,
                    'message' => 'customize your' . ' ' . str_replace('_', ' ', $value) . ' ' . 'message',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        /**end for seller*/

        /**start delivery man*/
        $user_type_delivery_man = NotificationMessage::where('user_type', 'delivery_man')->get();
        $array_for_delivery_man_message_key = [
            'new_order_assigned_message',
            'expected_delivery_date',
            'delivery_man_charge',
            'order_canceled',
            'order_rescheduled_message',
            'order_edit_message',
            'message_from_seller',
            'message_from_admin',
            'message_from_customer',
            'cash_collect_by_admin_message',
            'cash_collect_by_seller_message',
            'withdraw_request_status_message',

        ];
        foreach ($array_for_delivery_man_message_key as $key => $value) {
            $key_check = $user_type_delivery_man->where('key', $value)->first();
            if ($key_check == null) {
                DB::table('notification_messages')->insert([
                    'user_type' => 'delivery_man',
                    'key' => $value,
                    'message' => 'customize your' . ' ' . str_replace('_', ' ', $value) . ' ' . 'message',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        /**end for delivery man*/
    }

    public static function company_riliability_import()
    {
        $datas = [
            [
                'item' => 'delivery_info',
                'title' => 'Fast Delivery all across the country',
                'image' => '',
                'status' => 1,
            ],
            [
                'item' => 'safe_payment',
                'title' => 'Safe Payment',
                'image' => '',
                'status' => 1,
            ],
            [
                'item' => 'return_policy',
                'title' => '7 Days Return Policy',
                'image' => '',
                'status' => 1,
            ],
            [
                'item' => 'authentic_product',
                'title' => '100% Authentic Products',
                'image' => '',
                'status' => 1,
            ],
        ];

        if (BusinessSetting::where(['type' => 'company_reliability'])->first() == false) {
            BusinessSetting::insert(['type' => 'company_reliability'], [
                'value' => json_encode($datas),
            ]);
        }
    }

    public function database_installation(Request $request)
    {
        if (self::check_database_connection($request->DB_HOST, $request->DB_DATABASE, $request->DB_USERNAME, $request->DB_PASSWORD)) {

            $key = base64_encode(random_bytes(32));
            $output = 'APP_NAME=6valley' . time() . '
                    APP_ENV=live
                    APP_KEY=base64:' . $key . '
                    APP_DEBUG=false
                    APP_INSTALL=true
                    APP_LOG_LEVEL=debug
                    APP_MODE=live
                    APP_URL=' . URL::to('/') . '

                    DB_CONNECTION=mysql
                    DB_HOST=' . $request->DB_HOST . '
                    DB_PORT=3306
                    DB_DATABASE=' . $request->DB_DATABASE . '
                    DB_USERNAME=' . $request->DB_USERNAME . '
                    DB_PASSWORD=' . $request->DB_PASSWORD . '

                    BROADCAST_DRIVER=log
                    CACHE_DRIVER=file
                    SESSION_DRIVER=file
                    SESSION_LIFETIME=60
                    QUEUE_DRIVER=sync

                    AWS_ENDPOINT=
                    AWS_ACCESS_KEY_ID=
                    AWS_SECRET_ACCESS_KEY=
                    AWS_DEFAULT_REGION=us-east-1
                    AWS_BUCKET=

                    REDIS_HOST=127.0.0.1
                    REDIS_PASSWORD=null
                    REDIS_PORT=6379

                    PUSHER_APP_ID=
                    PUSHER_APP_KEY=
                    PUSHER_APP_SECRET=
                    PUSHER_APP_CLUSTER=mt1

                    PURCHASE_CODE=' . session('purchase_key') . '
                    BUYER_USERNAME=' . session('username') . '
                    SOFTWARE_ID=MzE0NDg1OTc=

                    SOFTWARE_VERSION=' . SOFTWARE_VERSION . '
                    ';
            $file = fopen(base_path('.env'), 'w');
            fwrite($file, $output);
            fclose($file);

            $path = base_path('.env');
            if (file_exists($path)) {
                return redirect('step4');
            } else {
                session()->flash('error', 'Database error!');
                return redirect('step3');
            }
        } else {
            session()->flash('error', 'Database error!');
            return redirect('step3');
        }
    }

    public function import_sql()
    {
        try {
            $sql_path = base_path('installation/backup/database.sql');
            DB::unprepared(file_get_contents($sql_path));
            return redirect('step5');
        } catch (\Exception $exception) {
            session()->flash('error', 'Your database is not clean, do you want to clean database then import?');
            return back();
        }
    }

    public function force_import_sql()
    {
        try {
            Artisan::call('db:wipe');
            $sql_path = base_path('installation/backup/database.sql');
            DB::unprepared(file_get_contents($sql_path));
            return redirect('step5');
        } catch (\Exception $exception) {
            session()->flash('error', 'Check your database permission!');
            return back();
        }
    }

    function check_database_connection($db_host = "", $db_name = "", $db_user = "", $db_pass = "")
    {

        if (@mysqli_connect($db_host, $db_user, $db_pass, $db_name)) {
            return true;
        } else {
            return false;
        }
    }
}
