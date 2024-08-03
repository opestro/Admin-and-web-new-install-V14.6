<?php

namespace App\Utils;

use App\Models\AddFundBonusCategories;
use App\Models\OrderStatusHistory;
use App\Models\ShippingMethod;
use App\Models\Shop;
use App\Models\Admin;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Color;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\NotificationMessage;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Setting;
use App\Traits\CommonTrait;
use App\User;
use App\Utils\CartManager;
use App\Utils\OrderManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class Helpers
{
    use CommonTrait;
    public static function status($id)
    {
        if ($id == 1) {
            $x = 'active';
        } elseif ($id == 0) {
            $x = 'in-active';
        }

        return $x;
    }

    public static function transaction_formatter($transaction)
    {
        if ($transaction['paid_by'] == 'customer') {
            $user = User::find($transaction['payer_id']);
            $payer = $user->f_name . ' ' . $user->l_name;
        } elseif ($transaction['paid_by'] == 'seller') {
            $user = Seller::find($transaction['payer_id']);
            $payer = $user->f_name . ' ' . $user->l_name;
        } elseif ($transaction['paid_by'] == 'admin') {
            $user = Admin::find($transaction['payer_id']);
            $payer = $user->name;
        }

        if ($transaction['paid_to'] == 'customer') {
            $user = User::find($transaction['payment_receiver_id']);
            $receiver = $user->f_name . ' ' . $user->l_name;
        } elseif ($transaction['paid_to'] == 'seller') {
            $user = Seller::find($transaction['payment_receiver_id']);
            $receiver = $user->f_name . ' ' . $user->l_name;
        } elseif ($transaction['paid_to'] == 'admin') {
            $user = Admin::find($transaction['payment_receiver_id']);
            $receiver = $user->name;
        }

        $transaction['payer_info'] = $payer;
        $transaction['receiver_info'] = $receiver;

        return $transaction;
    }

    public static function get_customer($request = null)
    {
        $user = null;
        if (auth('customer')->check()) {
            $user = auth('customer')->user(); // for web

        } elseif (is_object($request) && method_exists($request, 'user')) {
            $user = $request->user() ?? $request->user; //for api

        } elseif (isset($request['payment_request_from']) && in_array($request['payment_request_from'], ['app']) && !isset($request->user)){
            $user = $request['is_guest'] ? 'offline' : User::find($request['customer_id']);

        } elseif (session()->has('customer_id') && !session('is_guest')) {
            $user = User::find(session('customer_id'));

        } elseif(isset($request->user)){
            $user = $request->user;
        }

        if ($user == null) {
            $user = 'offline';
        }

        return $user;
    }

    public static function coupon_discount($request)
    {
        $discount = 0;
        $user = Helpers::get_customer($request);
        $couponLimit = Order::where('customer_id', $user->id)
            ->where('coupon_code', $request['coupon_code'])->count();

        $coupon = Coupon::where(['code' => $request['coupon_code']])
            ->where('limit', '>', $couponLimit)
            ->where('status', '=', 1)
            ->whereDate('start_date', '<=', Carbon::parse()->toDateString())
            ->whereDate('expire_date', '>=', Carbon::parse()->toDateString())->first();

        if (isset($coupon)) {
            $total = 0;
            foreach (CartManager::get_cart(groupId: CartManager::get_cart_group_ids(request: $request)) as $cart) {
                $product_subtotal = $cart['price'] * $cart['quantity'];
                $total += $product_subtotal;
            }
            if ($total >= $coupon['min_purchase']) {
                if ($coupon['discount_type'] == 'percentage') {
                    $discount = (($total / 100) * $coupon['discount']) > $coupon['max_discount'] ? $coupon['max_discount'] : (($total / 100) * $coupon['discount']);
                } else {
                    $discount = $coupon['discount'];
                }
            }
        }

        return $discount;
    }

    public static function default_lang()
    {
        if (strpos(url()->current(), '/api')) {
            $lang = App::getLocale();
        } elseif (session()->has('local')) {
            $lang = session('local');
        } else {
            $data = Helpers::get_business_settings('language');
            $code = 'en';
            $direction = 'ltr';
            foreach ($data as $ln) {
                if (array_key_exists('default', $ln) && $ln['default']) {
                    $code = $ln['code'];
                    if (array_key_exists('direction', $ln)) {
                        $direction = $ln['direction'];
                    }
                }
            }
            session()->put('local', $code);
            Session::put('direction', $direction);
            $lang = $code;
        }
        return $lang;
    }

    public static function rating_count($product_id, $rating)
    {
        return Review::where(['product_id' => $product_id, 'rating' => $rating])->whereNull('delivery_man_id')->count();
    }

    public static function get_business_settings($name)
    {
        $config = null;
        $check = ['currency_model', 'currency_symbol_position', 'system_default_currency', 'language', 'company_name', 'decimal_point_settings', 'product_brand', 'digital_product', 'company_email'];

        if (in_array($name, $check) == true && session()->has($name)) {
            $config = session($name);
        } else {
            $data = BusinessSetting::where(['type' => $name])->first();
            if (isset($data)) {
                $config = json_decode($data['value'], true);
                if (is_null($config)) {
                    $config = $data['value'];
                }
            }

            if (in_array($name, $check) == true) {
                session()->put($name, $config);
            }
        }

        return $config;
    }

    public static function get_settings($object, $type)
    {
        $config = null;
        foreach ($object as $setting) {
            if ($setting['type'] == $type) {
                $config = $setting;
            }
        }
        return $config;
    }

    public static function get_shipping_methods($seller_id, $type)
    {
        if ($type == 'admin') {
            return ShippingMethod::where(['status' => 1])->where(['creator_type' => 'admin'])->get();
        } else {
            return ShippingMethod::where(['status' => 1])->where(['creator_id' => $seller_id, 'creator_type' => $type])->get();
        }
    }

    public static function get_image_path($type)
    {
        $path = asset('storage/app/public/brand');
        return $path;
    }

    public static function set_data_format($data)
    {
        $colors = is_array($data['colors']) ? $data['colors'] : json_decode($data['colors']);
        $query_data = Color::whereIn('code', $colors)->pluck('name', 'code')->toArray();
        $color_process = [];
        foreach ($query_data as $key => $color) {
            $color_process[] = array(
                'name' => $color,
                'code' => $key,
            );
        }

        $color_image = isset($data['color_image']) ? (is_array($data['color_image']) ? $data['color_image'] : json_decode($data['color_image'])) : null;
        $color_final = [];
        foreach($color_process as $color){
            $image_name = null;
            if($color_image){
                foreach($color_image as $image){
                    if($image->color && '#'.$image->color==$color['code']){
                        $image_name = $image->image_name;
                    }
                }
            }
            $color_final[] = [
                'name' => $color['name'],
                'code' => $color['code'],
                'image' => $image_name,
            ];
        }

        $variation = [];
        $data['category_ids'] = is_array($data['category_ids']) ? $data['category_ids'] : json_decode($data['category_ids']);
        $data['images'] = is_array($data['images']) ? $data['images'] : json_decode($data['images']);
        $data['colors'] = $colors;
        $data['color_image'] = $color_image;
        $data['colors_formatted'] = $color_final;
        $attributes = [];
        if ((is_array($data['attributes']) ? $data['attributes'] : json_decode($data['attributes'])) != null) {
            $attributes_arr = is_array($data['attributes']) ? $data['attributes'] : json_decode($data['attributes']);
            foreach ($attributes_arr as $attribute) {
                $attributes[] = (integer)$attribute;
            }
        }
        $data['attributes'] = $attributes;
        $data['choice_options'] = is_array($data['choice_options']) ? $data['choice_options'] : json_decode($data['choice_options']);
        $variation_arr = is_array($data['variation']) ? $data['variation'] : json_decode($data['variation'], true);
        foreach ($variation_arr as $var) {
            $variation[] = [
                'type' => $var['type'],
                'price' => (double)$var['price'],
                'sku' => $var['sku'],
                'qty' => (integer)$var['qty'],
            ];
        }
        $data['variation'] = $variation;

        return $data;
    }


    public static function product_data_formatting($data, $multi_data = false)
    {
        if ($data) {
            $storage = [];
            if ($multi_data == true) {
                foreach ($data as $item) {
                    if($item){
                        $storage[] = Helpers::set_data_format($item);
                    }
                }
                $data = $storage;
            } else {
                $data = Helpers::set_data_format($data);;
            }

            return $data;
        }
        return null;
    }

    public static function units()
    {
        $x = ['kg', 'pc', 'gms', 'ltrs'];
        return $x;
    }

    public static function default_payment_gateways()
    {
        $methods = [
            'ssl_commerz',
            'paypal',
            'stripe',
            'razor_pay',
            'paystack',
            'senang_pay',
            'paymob_accept',
            'flutterwave',
            'paytm',
            'paytabs',
            'liqpay',
            'mercadopago',
            'bkash'
        ];
        return $methods;
    }

    public static function default_sms_gateways()
    {
        $methods = [
            'twilio',
            'nexmo',
            '2factor',
            'msg91',
            'releans',
        ];
        return $methods;
    }

    public static function remove_invalid_charcaters($str)
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', preg_replace('/\s\s+/', ' ', $str));
    }

    public static function saveJSONFile($code, $data)
    {
        ksort($data);
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents(base_path('resources/lang/en/messages.json'), stripslashes($jsonData));
    }

    public static function combinations($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            $err_keeper[] = ['code' => $index, 'message' => $error[0]];
        }
        return $err_keeper;
    }

    public static function currency_load()
    {
        $default = Helpers::get_business_settings('system_default_currency');
        $current = \session('system_default_currency_info');
        if (session()->has('system_default_currency_info') == false || $default != $current['id']) {
            $id = Helpers::get_business_settings('system_default_currency');
            $currency = Currency::find($id);
            session()->put('system_default_currency_info', $currency);
            session()->put('currency_code', $currency->code);
            session()->put('currency_symbol', $currency->symbol);
            session()->put('currency_exchange_rate', $currency->exchange_rate);
        }
    }

    public static function currency_converter($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            if (session()->has('usd')) {
                $usd = session('usd');
            } else {
                $usd = Currency::where(['code' => 'USD'])->first()->exchange_rate;
                session()->put('usd', $usd);
            }
            $my_currency = \session('currency_exchange_rate');
            $rate = $my_currency / $usd;
        } else {
            $rate = 1;
        }

        return Helpers::set_symbol(round($amount * $rate, 2));
    }

    public static function language_load()
    {
        if (\session()->has('language_settings')) {
            $language = \session('language_settings');
        } else {
            $language = BusinessSetting::where('type', 'language')->first();
            \session()->put('language_settings', $language);
        }
        return $language;
    }

    public static function tax_calculation($product, $price, $tax, $tax_type)
    {
        $amount = ($price / 100) * $tax;
        return $amount;

//        $discount = self::get_product_discount(product: $product, price: $price);
//        return (($price-$discount) / 100) * $tax; //after discount decrease
    }

    public static function get_price_range($product)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        foreach (json_decode($product->variation) as $key => $variation) {
            if ($lowest_price > $variation->price) {
                $lowest_price = round($variation->price, 2);
            }
            if ($highest_price < $variation->price) {
                $highest_price = round($variation->price, 2);
            }
        }

        $lowest_price = Helpers::currency_converter($lowest_price - Helpers::get_product_discount($product, $lowest_price));
        $highest_price = Helpers::currency_converter($highest_price - Helpers::get_product_discount($product, $highest_price));

        if ($lowest_price == $highest_price) {
            return $lowest_price;
        }
        return $lowest_price . ' - ' . $highest_price;
    }

    public static function get_price_range_with_discount($product)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;
        $getOldPriceClass = (theme_root_path() === 'theme_aster' ? 'product__old-price text-muted' : '');

        foreach (json_decode($product->variation) as $key => $variation) {
            if ($lowest_price > $variation->price) {
                $lowest_price = round($variation->price, 2);
            }
            if ($highest_price < $variation->price) {
                $highest_price = round($variation->price, 2);
            }
        }

        if($product->discount >0){
            $discounted_lowest_price = Helpers::currency_converter($lowest_price - Helpers::get_product_discount($product, $lowest_price));
            $discounted_highest_price = Helpers::currency_converter($highest_price - Helpers::get_product_discount($product, $highest_price));

            if ($discounted_lowest_price == $discounted_highest_price) {
                if($discounted_lowest_price == self::currency_converter($lowest_price)){
                    return $discounted_lowest_price;
                }else{
                    return theme_root_path() === "default" ? $discounted_lowest_price." <del class='align-middle fs-16 text-muted'>".self::currency_converter($lowest_price)."</del> " : $discounted_lowest_price." <del class='$getOldPriceClass'>".self::currency_converter($lowest_price)."</del> ";
                }
            }
            return  theme_root_path() === "default" ? "<span>".$discounted_lowest_price."</span>"." <del class='align-middle fs-16 text-muted'>".self::currency_converter($lowest_price)."</del> ". ' - ' ."<span>".$discounted_highest_price."</span>"." <del class='align-middle fs-16 text-muted'>".self::currency_converter($highest_price)."</del> " : $discounted_lowest_price." <del class='$getOldPriceClass'>".self::currency_converter($lowest_price)."</del> ". ' - ' .$discounted_highest_price." <del class='$getOldPriceClass'>".self::currency_converter($highest_price)."</del> ";
        }else{
            return  theme_root_path() === "default" ? "<span>".self::currency_converter($lowest_price)."</span>".' - ' ."<span>".self::currency_converter($highest_price)."</span>" : self::currency_converter($lowest_price). ' - ' .self::currency_converter($highest_price);
        }
    }

    public static function get_product_discount($product, $price)
    {
        $discount = 0;
        if ($product['discount_type'] == 'percent') {
            $discount = ($price * $product['discount']) / 100;
        } elseif ($product['discount_type'] == 'flat') {
            $discount = $product['discount'];
        }

        return floatval($discount);
    }

    public static function module_permission_check($mod_name)
    {
        $user_role = auth('admin')->user()->role;
        $permission = $user_role->module_access;
        if (isset($permission) && $user_role->status == 1 && in_array($mod_name, (array)json_decode($permission)) == true) {
            return true;
        }

        if (auth('admin')->user()->admin_role_id == 1) {
            return true;
        }
        return false;
    }

    public static function convert_currency_to_usd($price)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            Helpers::currency_load();
            $code = session('currency_code') == null ? 'USD' : session('currency_code');
            if ($code == 'USD') {
                return $price;
            }
            $currency = Currency::where('code', $code)->first();
            $price = floatval($price) / floatval($currency->exchange_rate);

            $usd_currency = Currency::where('code', 'USD')->first();
            $price = $usd_currency->exchange_rate < 1 ? (floatval($price) * floatval($usd_currency->exchange_rate)) : (floatval($price) / floatval($usd_currency->exchange_rate));
        } else {
            $price = floatval($price);
        }

        return $price;
    }

    public static function convert_manual_currency_to_usd($price, $currency = null)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $code = $currency == null ? 'USD' : $currency;
            if ($code == 'USD') {
                return $price;
            }
            $currency = Currency::where('code', $code)->first();
            $price = floatval($price) / floatval($currency->exchange_rate);

            $usd_currency = Currency::where('code', 'USD')->first();
            $price = $usd_currency->exchange_rate < 1 ? (floatval($price) * floatval($usd_currency->exchange_rate)) : (floatval($price) / floatval($usd_currency->exchange_rate));
        } else {
            $price = floatval($price);
        }

        return $price;
    }

    /** push notification order related  */
    public static function send_order_notification($key,$type,$order){
        try {
            $lang = self::default_lang();

            /** for customer  */
            if($type == 'customer') {
                $fcm_token = $order->customer?->cm_firebase_token;
                $lang = $order->customer?->app_language ?? $lang;
                $value = Helpers::push_notificatoin_message($key,'customer', $lang);
                $value = Helpers::text_variable_data_format(value: $value,key:$key,shopName:$order->seller?->shop?->name,order_id:$order->id,user_name:"{$order->customer?->f_name} {$order->customer?->l_name}",delivery_man_name:"{$order->delivery_man?->f_name} {$order->delivery_man?->l_name}",time:now()->diffForHumans());
                if(!empty($fcm_token) || $value) {
                    $data = [
                        'title' => translate('order'),
                        'description' => $value,
                        'order_id' => $order['id'],
                        'image' => '',
                        'type' => 'order'
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                }
            }
            /** end for customer  */
            /**for seller */
            if($type == 'seller') {
                $seller_fcm_token = $order->seller?->cm_firebase_token;
                if(!empty($seller_fcm_token)) {
                    $lang = $order->seller?->app_language ?? $lang;
                    $value_seller = Helpers::push_notificatoin_message($key,'seller',$lang);
                    $value_seller = Helpers::text_variable_data_format(value:$value_seller,key:$key,shopName:$order->seller?->shop?->name,order_id:$order->id,user_name:"{$order->customer?->f_name} {$order->customer?->l_name}",delivery_man_name:"{$order->delivery_man?->f_name} {$order->delivery_man?->l_name}",time:now()->diffForHumans());

                        if ($value_seller != null) {
                            $data = [
                                'title' => translate('order'),
                                'description' => $value_seller,
                                'order_id' => $order['id'],
                                'image' => '',
                                'type' => 'order'
                            ];
                            Helpers::send_push_notif_to_device($seller_fcm_token, $data);
                        }
                }
            }
            /**end for seller */
            /** for delivery man*/
            if($type == 'delivery_man') {
                $fcm_token_delivery_man =$order->delivery_man?->fcm_token;
                $lang = $order->delivery_man?->app_language ?? $lang;
                $value_delivery_man = Helpers::push_notificatoin_message($key,'delivery_man', $lang);
                $value_delivery_man = Helpers::text_variable_data_format(value:$value_delivery_man,key:$key,shopName:$order->seller?->shop?->name,order_id:$order->id,user_name:"{$order->customer?->f_name} {$order->customer?->l_name}",delivery_man_name:"{$order->delivery_man?->f_name} {$order->delivery_man?->l_name}",time:now()->diffForHumans());
                $data = [
                    'title' => translate('order'),
                    'description' => $value_delivery_man,
                    'order_id' => $order['id'],
                    'image' => '',
                    'type' => 'order'
                ];
                if($order->delivery_man_id) {
                    self::add_deliveryman_push_notification($data, $order->delivery_man_id);
                }
                if($fcm_token_delivery_man){
                    Helpers::send_push_notif_to_device($fcm_token_delivery_man, $data);
                }
            }

            /** end delivery man*/
        } catch (\Exception $e) {

        }
    }
    /** end push notification to seller  */

    /** push notification variable message formate  */
    public static function text_variable_data_format($value,$key=null,$user_name=null,$shopName=null,$delivery_man_name=null,$time=null,$order_id=null)
    {
        $data =  $value;
        if ($data) {
            $order = $order_id ? Order::find($order_id) : null;
            $data =  $user_name ? str_replace("{userName}", $user_name, $data):$data;
            $data =  $shopName ? str_replace("{shopName}", $shopName, $data) :$data;
            $data =  $delivery_man_name ? str_replace("{deliveryManName}", $delivery_man_name, $data):$data;
            $data =  $key=='expected_delivery_date' ? ($order ? str_replace("{time}", $order->expected_delivery_date, $data):$data): ($time ? str_replace("{time}", $time, $data):$data);
            $data =  $order_id ? str_replace("{orderId}", $order_id, $data):$data;
        }
        return $data;
    }
    /* end **/
    public static function push_notificatoin_message($key,$user_type, $lang)
    {
        try {
        $notification_key = [
            'pending'   =>'order_pending_message',
            'confirmed' =>'order_confirmation_message',
            'processing'=>'order_processing_message',
            'out_for_delivery'=>'out_for_delivery_message',
            'delivered' =>'order_delivered_message',
            'returned'  =>'order_returned_message',
            'failed'    =>'order_failed_message',
            'canceled'  =>'order_canceled',
            'order_refunded_message'    =>'order_refunded_message',
            'refund_request_canceled_message'   =>'refund_request_canceled_message',
            'new_order_message' =>'new_order_message',
            'order_edit_message'=>'order_edit_message',
            'new_order_assigned_message'=>'new_order_assigned_message',
            'delivery_man_assign_by_admin_message'=>'delivery_man_assign_by_admin_message',
            'order_rescheduled_message'=>'order_rescheduled_message',
            'expected_delivery_date'=>'expected_delivery_date',
            'message_from_admin'=>'message_from_admin',
            'message_from_seller'=>'message_from_seller',
            'message_from_delivery_man'=>'message_from_delivery_man',
            'message_from_customer'=>'message_from_customer',
            'refund_request_status_changed_by_admin'=>'refund_request_status_changed_by_admin',
            'withdraw_request_status_message'=>'withdraw_request_status_message',
            'cash_collect_by_seller_message'=>'cash_collect_by_seller_message',
            'cash_collect_by_admin_message'=>'cash_collect_by_admin_message',
            'fund_added_by_admin_message' => 'fund_added_by_admin_message',
            'delivery_man_charge' => 'delivery_man_charge',
        ];
        $data = NotificationMessage::with(['translations'=>function($query)use($lang){
            $query->where('locale', $lang);
        }])->where(['key'=>$notification_key[$key],'user_type'=>$user_type])->first() ?? ["status"=>0,"message"=>"","translations"=>[]];
        if($data){
            if ($data['status'] == 0) {
                return 0;
            }
            return count($data->translations) > 0 ? $data->translations[0]->value : $data['message'];
        }else{
            return false;
        }
        } catch (\Exception $exception) {
        }
    }

    /** chatting related push notification */
    public static function chatting_notification($key,$type,$user_data,$message_form=null){
        try {
            $fcm_token = $type=='delivery_man' ? $user_data?->fcm_token : $user_data?->cm_firebase_token;
            if($fcm_token){
                $lang = $user_data?->app_language ?? self::default_lang();
                $value = Helpers::push_notificatoin_message($key,$type,$lang);

                $value = Helpers::text_variable_data_format(
                        value:$value,
                        key:$key,
                        shopName:$message_form?->shop?->name,
                        user_name:"{$message_form?->f_name} {$message_form?->l_name}",
                        delivery_man_name:"{$message_form?->f_name} {$message_form?->l_name}",
                        time:now()->diffForHumans()
                    );
                $data = [
                    'title' => translate('message'),
                    'description' => $value,
                    'order_id' => '',
                    'image' => '',
                    'type' => 'chatting'
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        } catch (\Exception $exception) {
        }

    }
    /** end chatting related push notification */

    /**
    * Device wise notification send
    */

    public static function send_push_notif_to_device($fcm_token,$data)
    {
        $key = BusinessSetting::where(['type' => 'push_notification_key'])->first()->value;
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array("authorization: key=" . $key . "",
            "content-type: application/json"
        );

        if (isset($data['order_id']) == false) {
            $data['order_id'] = null;
        }

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "data" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"' . $data['order_id'] . '",
                "type":"' . $data['type'] . '",
                "is_read": 0
              },
              "notification" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"' . $data['order_id'] . '",
                "title_loc_key":"' . $data['order_id'] . '",
                "type":"' . $data['type'] . '",
                "is_read": 0,
                "icon" : "new",
                "sound" : "default"
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function send_push_notif_to_topic($data, $topic = 'sixvalley')
    {
        $key = BusinessSetting::where(['type' => 'push_notification_key'])->first()->value;

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = ["authorization: key=" . $key . "",
            "content-type: application/json",
        ];

        $image = asset('storage/app/public/notification') . '/' . $data['image'];
        $postdata = '{
            "to" : "/topics/' . $topic . '",
            "data" : {
                "title":"' . $data->title . '",
                "body" : "' . $data->description . '",
                "image" : "' . $image . '",
                "is_read": 0
              },
              "notification" : {
                "title":"' . $data->title . '",
                "body" : "' . $data->description . '",
                "image" : "' . $image . '",
                "title_loc_key":null,
                "is_read": 0,
                "icon" : "new",
                "sound" : "default"
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function get_seller_by_token($request)
    {
        $data = '';
        $success = 0;

        $token = explode(' ', $request->header('authorization'));
        if (count($token) > 1 && strlen($token[1]) > 30) {
            $seller = Seller::where(['auth_token' => $token['1']])->first();
            if (isset($seller)) {
                $data = $seller;
                $success = 1;
            }
        }

        return [
            'success' => $success,
            'data' => $data
        ];
    }

    public static function remove_dir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") Helpers::remove_dir($dir . "/" . $object); else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public static function currency_code()
    {
        Helpers::currency_load();
        if (session()->has('currency_symbol')) {
            $symbol = session('currency_symbol');
            $code = Currency::where(['symbol' => $symbol])->first()->code;
        } else {
            $system_default_currency_info = session('system_default_currency_info');
            $code = $system_default_currency_info->code;
        }
        return $code;
    }

    public static function get_language_name($key)
    {
        $values = Helpers::get_business_settings('language');
        foreach ($values as $value) {
            if ($value['code'] == $key) {
                $key = $value['name'];
            }
        }

        return $key;
    }

    public static function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        if (is_bool(env($envKey))) {
            $oldValue = var_export(env($envKey), true);
        } else {
            $oldValue = env($envKey);
        }

        if (strpos($str, $envKey) !== false) {
            $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
        } else {
            $str .= "{$envKey}={$envValue}\n";
        }
        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
        return $envValue;
    }

    public static function requestSender()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
            CURLOPT_URL => route(base64_decode('YWN0aXZhdGlvbi1jaGVjaw==')),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $data = json_decode($response, true);
        return $data;
    }

    public static function sales_commission($order)
    {
        $discount_amount = 0;
        if ($order->coupon_code) {
            $coupon = Coupon::where(['code' => $order->coupon_code])->first();
            if ($coupon) {
                $discount_amount = $coupon->coupon_type == 'free_delivery' ? 0 : $order['discount_amount'];
            }
        }
        $order_summery = OrderManager::order_summary($order);
        $order_total = $order_summery['subtotal'] - $order_summery['total_discount_on_product'] - $discount_amount;
        $commission_amount = self::seller_sales_commission($order['seller_is'], $order['seller_id'], $order_total);

        return $commission_amount;
    }

    public static function sales_commission_before_order($cart_group_id, $coupon_discount)
    {
        $carts = CartManager::get_cart(groupId: $cart_group_id);
        $cart_summery = OrderManager::order_summary_before_place_order($carts, $coupon_discount);
        $commission_amount = self::seller_sales_commission($carts[0]['seller_is'], $carts[0]['seller_id'], $cart_summery['order_total']);

        return $commission_amount;
    }

    public static function seller_sales_commission($seller_is, $seller_id, $order_total)
    {
        $commission_amount = 0;
        if ($seller_is == 'seller') {
            $seller = Seller::find($seller_id);
            if (isset($seller) && $seller['sales_commission_percentage'] !== null) {
                $commission = $seller['sales_commission_percentage'];
            } else {
                $commission = Helpers::get_business_settings('sales_commission');
            }
            $commission_amount = number_format(($order_total / 100) * $commission, 2);
        }
        return $commission_amount;
    }

    public static function categoryName($id)
    {
        return Category::select('name')->find($id)->name;
    }

    public static function set_symbol($amount)
    {
        $decimal_point_settings = Helpers::get_business_settings('decimal_point_settings');
        $position = Helpers::get_business_settings('currency_symbol_position');
        if (!is_null($position) && $position == 'left') {
            $string = currency_symbol() . '' . number_format($amount, (!empty($decimal_point_settings) ? $decimal_point_settings : 0));
        } else {
            $string = number_format($amount, !empty($decimal_point_settings) ? $decimal_point_settings : 0) . '' . currency_symbol();
        }
        return $string;
    }

    public static function pagination_limit()
    {
        $pagination_limit = BusinessSetting::where('type', 'pagination_limit')->first();
        if ($pagination_limit != null) {
            return $pagination_limit->value;
        } else {
            return 25;
        }
    }

    public static function gen_mpdf($view, $file_prefix, $file_postfix)
    {
        $mpdf = new \Mpdf\Mpdf(['default_font' => 'FreeSerif', 'mode' => 'utf-8', 'format' => [190, 250]]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;

        $mpdf_view = $view;
        $mpdf_view = $mpdf_view->render();
        $mpdf->WriteHTML($mpdf_view);
        $mpdf->Output($file_prefix . $file_postfix . '.pdf', 'D');
    }

    public static function generate_referer_code()
    {
        $ref_code = strtoupper(Str::random('20'));
        if (User::where('referral_code', '=', $ref_code)->exists()) {
            return generate_referer_code();
        }
        return $ref_code;
    }

    public static function add_fund_to_wallet_bonus($amount)
    {
        $bonuses = AddFundBonusCategories::where('is_active', 1)
            ->whereDate('start_date_time', '<=', now())
            ->whereDate('end_date_time', '>=', now())
            ->where('min_add_money_amount', '<=', $amount)
            ->get();

        $bonuses = $bonuses->where('min_add_money_amount', $bonuses->max('min_add_money_amount'));

        foreach ($bonuses as $key => $item) {
            $item->applied_bonus_amount = $item->bonus_type == 'percentage' ? ($amount * $item->bonus_amount) / 100 : $item->bonus_amount;

            //max bonus check
            if ($item->bonus_type == 'percentage' && $item->applied_bonus_amount > $item->max_bonus_amount) {
                $item->applied_bonus_amount = $item->max_bonus_amount;
            }
        }

        return $bonuses->max('applied_bonus_amount') ?? 0;
    }
}


if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        Helpers::currency_load();
        if (\session()->has('currency_symbol')) {
            $symbol = \session('currency_symbol');
        } else {
            $system_default_currency_info = \session('system_default_currency_info');
            $symbol = $system_default_currency_info->symbol;
        }
        return $symbol;
    }
}
//formats currency
if (!function_exists('format_price')) {
    function format_price($price)
    {
        return number_format($price, 2) . currency_symbol();
    }
}

/*function translate($key)
{
    $local = Helpers::default_lang();
    App::setLocale($local);

    try {
        $lang_array = include(base_path('resources/lang/' . $local . '/messages.php'));
        $processed_key = ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key)));
        $key = Helpers::remove_invalid_charcaters($key);
        if (!array_key_exists($key, $lang_array)) {
            $lang_array[$key] = $processed_key;
            $str = "<?php return " . var_export($lang_array, true) . ";";
            file_put_contents(base_path('resources/lang/' . $local . '/messages.php'), $str);
            $result = $processed_key;
        } else {
            $result = __('messages.' . $key);
        }
    } catch (\Exception $exception) {
        $result = __('messages.' . $key);
    }

    return $result;
}*/

function auto_translator($q, $sl, $tl)
{
    $res = file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=" . $sl . "&tl=" . $tl . "&hl=hl&q=" . urlencode($q), $_SERVER['DOCUMENT_ROOT'] . "/transes.html");
    $res = json_decode($res);
    return str_replace('_', ' ', $res[0][0][0]);
}

function getLanguageCode(string $country_code): string
{
    $locales = array('af-ZA',
        'am-ET',
        'ar-AE',
        'ar-BH',
        'ar-DZ',
        'ar-EG',
        'ar-IQ',
        'ar-JO',
        'ar-KW',
        'ar-LB',
        'ar-LY',
        'ar-MA',
        'ar-OM',
        'ar-QA',
        'ar-SA',
        'ar-SY',
        'ar-TN',
        'ar-YE',
        'az-Cyrl-AZ',
        'az-Latn-AZ',
        'be-BY',
        'bg-BG',
        'bn-BD',
        'bs-Cyrl-BA',
        'bs-Latn-BA',
        'cs-CZ',
        'da-DK',
        'de-AT',
        'de-CH',
        'de-DE',
        'de-LI',
        'de-LU',
        'dv-MV',
        'el-GR',
        'en-AU',
        'en-BZ',
        'en-CA',
        'en-GB',
        'en-IE',
        'en-JM',
        'en-MY',
        'en-NZ',
        'en-SG',
        'en-TT',
        'en-US',
        'en-ZA',
        'en-ZW',
        'es-AR',
        'es-BO',
        'es-CL',
        'es-CO',
        'es-CR',
        'es-DO',
        'es-EC',
        'es-ES',
        'es-GT',
        'es-HN',
        'es-MX',
        'es-NI',
        'es-PA',
        'es-PE',
        'es-PR',
        'es-PY',
        'es-SV',
        'es-US',
        'es-UY',
        'es-VE',
        'et-EE',
        'fa-IR',
        'fi-FI',
        'fil-PH',
        'fo-FO',
        'fr-BE',
        'fr-CA',
        'fr-CH',
        'fr-FR',
        'fr-LU',
        'fr-MC',
        'he-IL',
        'hi-IN',
        'hr-BA',
        'hr-HR',
        'hu-HU',
        'hy-AM',
        'id-ID',
        'ig-NG',
        'is-IS',
        'it-CH',
        'it-IT',
        'ja-JP',
        'ka-GE',
        'kk-KZ',
        'kl-GL',
        'km-KH',
        'ko-KR',
        'ky-KG',
        'lb-LU',
        'lo-LA',
        'lt-LT',
        'lv-LV',
        'mi-NZ',
        'mk-MK',
        'mn-MN',
        'ms-BN',
        'ms-MY',
        'mt-MT',
        'nb-NO',
        'ne-NP',
        'nl-BE',
        'nl-NL',
        'pl-PL',
        'prs-AF',
        'ps-AF',
        'pt-BR',
        'pt-PT',
        'ro-RO',
        'ru-RU',
        'rw-RW',
        'sv-SE',
        'si-LK',
        'sk-SK',
        'sl-SI',
        'sq-AL',
        'sr-Cyrl-BA',
        'sr-Cyrl-CS',
        'sr-Cyrl-ME',
        'sr-Cyrl-RS',
        'sr-Latn-BA',
        'sr-Latn-CS',
        'sr-Latn-ME',
        'sr-Latn-RS',
        'sw-KE',
        'tg-Cyrl-TJ',
        'th-TH',
        'tk-TM',
        'tr-TR',
        'uk-UA',
        'ur-PK',
        'uz-Cyrl-UZ',
        'uz-Latn-UZ',
        'vi-VN',
        'wo-SN',
        'yo-NG',
        'zh-CN',
        'zh-HK',
        'zh-MO',
        'zh-SG',
        'zh-TW');

    foreach ($locales as $locale) {
        $locale_region = explode('-', $locale);
        if (strtoupper($country_code) == $locale_region[1]) {
            return $locale_region[0];
        }
    }

    return "en";
}

function hex2rgb($colour)
{
    if ($colour[0] == '#') {
        $colour = substr($colour, 1);
    }
    if (strlen($colour) == 6) {
        list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
    } elseif (strlen($colour) == 3) {
        list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
    } else {
        return false;
    }
    $r = hexdec($r);
    $g = hexdec($g);
    $b = hexdec($b);
    return array('red' => $r, 'green' => $g, 'blue' => $b);
}

if (!function_exists('customer_info')) {
    function customer_info()
    {
        return User::where('id', auth('customer')->id())->first();

    }
}

if (!function_exists('order_status_history')) {
    function order_status_history($order_id, $status)
    {
        return OrderStatusHistory::where(['order_id' => $order_id, 'status' => $status])->latest()->pluck('created_at')->first();
    }
}

if (!function_exists('get_shop_name')) {
    function get_shop_name($seller_id)
    {
        $shop = Shop::where(['seller_id' => $seller_id])->first();
        return $shop ? $shop->name : null;
    }
}

if (!function_exists('hex_to_rgb')) {
    function hex_to_rgb($hex)
    {
        $result = preg_match('/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i', $hex, $matches);
        $data = $result ? hexdec($matches[1]) . ', ' . hexdec($matches[2]) . ', ' . hexdec($matches[3]) : null;

        return $data;
    }
}
if (!function_exists('get_color_name')) {
    function get_color_name($code)
    {
        return Color::where(['code' => $code])->first()->name;
    }
}

if (!function_exists('format_biginteger')) {
    function format_biginteger($value)
    {
        $suffixes = ["1t+" => 1000000000000, "B+" => 1000000000, "M+" => 1000000, "K+" => 1000];
        foreach ($suffixes as $suffix => $factor) {
            if ($value >= $factor) {
                $div = $value / $factor;
                $formatted_value = number_format($div, 1) . $suffix;
                break;
            }
        }

        if (!isset($formatted_value)) {
            $formatted_value = $value;
        }

        return $formatted_value;
    }
}

if (!function_exists('payment_gateways')) {
    function payment_gateways()
    {
        $payment_published_status = config('get_payment_publish_status');
        $payment_gateway_published_status = isset($payment_published_status[0]['is_published']) ? $payment_published_status[0]['is_published'] : 0;

        $payment_gateways_query = Setting::whereIn('settings_type', ['payment_config'])->where('is_active', 1);
        if ($payment_gateway_published_status == 1) {
            $payment_gateways_list = $payment_gateways_query->get();
        } else {
            $payment_gateways_list = $payment_gateways_query->whereIn('key_name', Helpers::default_payment_gateways())->get();
        }

        return $payment_gateways_list;
    }
}

if (!function_exists('get_business_settings')) {
    function get_business_settings($name)
    {
        $config = null;
        $check = ['currency_model', 'currency_symbol_position', 'system_default_currency', 'language', 'company_name', 'decimal_point_settings', 'product_brand', 'digital_product', 'company_email'];

        if (in_array($name, $check) && session()->has($name)) {
            $config = session($name);
        } else {
            $data = BusinessSetting::where(['type' => $name])->first();
            if (isset($data)) {
                $config = json_decode($data['value'], true);
                if (is_null($config)) {
                    $config = $data['value'];
                }
            }

            if (in_array($name, $check)) {
                session()->put($name, $config);
            }
        }
        return $config;
    }
}

if (!function_exists('get_customer')) {
    function get_customer($request = null)
    {
        if (auth('customer')->check()) {
            return auth('customer')->user();
        }

        if ($request != null && $request->user() != null) {
            return $request->user();
        }

        if (session()->has('customer_id') && !session('is_guest')) {
            return User::find(session('customer_id'));
        }

        if (isset($request->user)) {
            return $request->user;
        }

        return 'offline';
    }
}

if (!function_exists('product_image_path')) {
    function product_image_path($image_type): string
    {
        $path = '';
        if ($image_type == 'thumbnail') {
            $path = asset('storage/app/public/product/thumbnail');
        } elseif ($image_type == 'product') {
            $path = asset('storage/app/public/product');
        }
        return $path;
    }
}

if (!function_exists('currency_converter')) {
    function currency_converter($amount): string
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            if (session()->has('usd')) {
                $usd = session('usd');
            } else {
                $usd = Currency::where(['code' => 'USD'])->first()->exchange_rate;
                session()->put('usd', $usd);
            }
            $my_currency = \session('currency_exchange_rate');
            $rate = $my_currency / $usd;
        } else {
            $rate = 1;
        }

        return Helpers::set_symbol(round($amount * $rate, 2));
    }
}


