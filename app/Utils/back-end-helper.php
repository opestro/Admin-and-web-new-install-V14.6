<?php

namespace App\Utils;

use App\Utils\Helpers;
use App\Models\BusinessSetting;
use App\Models\Currency;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use function App\Utils\currency_symbol;

class BackEndHelper
{
    public static function currency_to_usd($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $default = Currency::find(BusinessSetting::where(['type' => 'system_default_currency'])->first()->value);
            $usd = Currency::where('code', 'USD')->first()->exchange_rate;
            $rate = $default['exchange_rate'] / $usd;
            $value = floatval($amount) / floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return $value;
    }

    public static function usd_to_currency($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {

            if (session()->has('default')) {
                $default = session('default');
            } else {
                $default = Currency::find(Helpers::get_business_settings('system_default_currency'))->exchange_rate;
                session()->put('default', $default);
            }

            if (session()->has('usd')) {
                $usd = session('usd');
            } else {
                $usd = Currency::where('code', 'USD')->first()->exchange_rate;
                session()->put('usd', $usd);
            }

            $rate = $default / $usd;
            $value = floatval($amount) * floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return round($value, 2);
    }

    public static function currency_symbol()
    {
        $currency = Currency::where('id', Helpers::get_business_settings('system_default_currency'))->first();
        return $currency->symbol;
    }

    public static function set_symbol($amount)
    {
        $decimal_point_settings = Helpers::get_business_settings('decimal_point_settings');
        $position = Helpers::get_business_settings('currency_symbol_position');
        if (!is_null($position) && $position == 'left') {
            $string = currency_symbol() . '' . number_format($amount, (!empty($decimal_point_settings) ? $decimal_point_settings: 0));
        } else {
            $string = number_format($amount, !empty($decimal_point_settings) ? $decimal_point_settings: 0) . '' . currency_symbol();
        }
        return $string;
    }

    public static function currency_code()
    {
        $currency = Currency::where('id', Helpers::get_business_settings('system_default_currency'))->first();
        return $currency->code;
    }

    public static function max_earning()
    {

        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        $data = Order::where([
            'seller_is' => 'admin',
            'order_status'=>'delivered'
        ])->select(
            DB::raw('IFNULL(sum(order_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

        $max = 0;
        foreach ($data as $month) {
            $count = 0;
            foreach ($month as $order) {
                $count += $order['order_amount'];
            }
            if ($count > $max) {
                $max = $count;
            }
        }

        return $max;
    }

    public static function max_orders()
    {
        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        $data = Order::where([
            'order_type'=>'default_type'
        ])->select(
            DB::raw('COUNT(id) as count'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

        $max = 0;
        foreach ($data as $item) {
            if ($item['count'] > $max) {
                $max = $item['count'];
            }
        }

        return $max;
    }

    public static function order_status($status){
        switch ($status) {
            case "pending":
                return "Pending";
            case "confirmed":
                return "Confirmed";
            case "processing":
                return "Packaging";
            case "out_for_delivery":
                return "Out for Delivery";
            case "delivered":
                return "Delivered";
            case "returned":
                return "Returned";
            case "failed":
                return "Failed to Deliver";
            case "canceled":
                return "Canceled";
        }
    }

    public static function format_currency($value){
        $suffixes = ["1t+" => 1000000000000, "B+" => 1000000000, "M+" => 1000000, "K+" => 1000];
        foreach ($suffixes as $suffix => $factor) {
            if ($value >= $factor) {
                $div = $value / $factor;
                $formatted_value = number_format($div,1 ) . $suffix;
                break;
            }
        }

        if (!isset($formatted_value)) {
            $formatted_value = number_format($value, 2);
        }

        return $formatted_value;
    }
}
