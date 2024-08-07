<?php

namespace App\Traits;


use App\Models\OrderStatusHistory;
use App\Models\DeliveryCountryCode;
use App\Models\DeliverymanNotification;
use App\Models\DeliverymanWallet;
use App\Models\DeliveryZipCode;
use App\Models\OrderExpectedDeliveryHistory;

trait CommonTrait
{
    public static function add_expected_delivery_date_history($order_id, $user_id, $value, $user_type, $cause = null)
    {

        if ($order_id && $user_id && $value && $user_type) {
            $delivery_history = new OrderExpectedDeliveryHistory();
            $delivery_history->order_id = $order_id;
            $delivery_history->user_id = $user_id;
            $delivery_history->user_type = $user_type;
            $delivery_history->expected_delivery_date = $value;
            $delivery_history->cause = $cause;

            $delivery_history->save();
        }
    }

    public static function add_order_status_history($order_id, $user_id, $status, $user_type, $cause = null)
    {
        if ($order_id && ($user_id || $user_id=='0') && $status && $user_type) {
            $delivery_history = new OrderStatusHistory();
            $delivery_history->order_id = $order_id;
            $delivery_history->user_id = $user_id;
            $delivery_history->user_type = $user_type;
            $delivery_history->status = $status;
            $delivery_history->cause = $cause;

            $delivery_history->save();
        }
    }

    public static function add_deliveryman_push_notification($data, $delivery_man_id)
    {
        if ($data && $delivery_man_id) {
            $notification = new DeliverymanNotification();
            $notification->order_id = $data['order_id'];
            $notification->delivery_man_id = $delivery_man_id;
            $notification->description = $data['description'];

            $notification->save();
        }
    }

    public static function delivery_man_withdrawable_balance($delivery_man_id)
    {
        $wallet = DeliverymanWallet::where('delivery_man_id', $delivery_man_id)->first();

        $withdrawable_balance = 0;
        if ($wallet) {
            $withdrawable_balance = ($wallet->current_balance ?? 0) - (($wallet->cash_in_hand ?? 0) + ($wallet->pending_withdraw ?? 0));
        }
        $withdrawable_balance = $withdrawable_balance > 0 ? $withdrawable_balance : 0;

        return $withdrawable_balance;
    }

    public static function delivery_man_total_earn($delivery_man_id)
    {
        $wallet = DeliverymanWallet::where('delivery_man_id', $delivery_man_id)->first();
        if ($wallet) {
            $total_earn = ($wallet->current_balance ?? 0) + ($wallet->total_withdraw ?? 0);
        } else {
            $total_earn = 0;
        }

        return $total_earn;
    }

    public function get_delivery_country_array()
    {
        $data = array();
        foreach (DeliveryCountryCode::all() as $delivery_country_code) {
            foreach (COUNTRIES as $key => $country) {
                if ($country['code'] == $delivery_country_code->country_code) {
                    $data[$key]['code'] = $country['code'];
                    $data[$key]['name'] = $country['name'];
                }
            }

        }
        return $data;
    }

    public function delivery_country_exist_check($input_country)
    {
        $data = array();
        foreach (DeliveryCountryCode::pluck('country_code') as $code)
        {
            foreach (COUNTRIES as $country) {
                $country['code'] == $code ?  $data[] = $country['name'] : '';
            }
        }
        $country_exists = in_array($input_country, $data);

        return $country_exists;
    }

    public function delivery_zipcode_exist_check($input_zip)
    {
        $zip_exists = in_array($input_zip, DeliveryZipCode::pluck('zipcode')->toArray());

        return $zip_exists;
    }


}
