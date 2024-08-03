<?php

namespace App\Utils;


use App\Utils\Helpers;
use App\Models\Currency;

class Convert
{
    public static function usd($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $default = Currency::find(Helpers::get_business_settings('system_default_currency'));
            $usd = Currency::where('code', 'USD')->first()->exchange_rate ?? 1;
            $rate = $default['exchange_rate'] / $usd;
            $value = floatval($amount) / floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return $value;
    }
    public static function usdPaymentModule($amount, $currency)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {

            $default = Currency::find(Helpers::get_business_settings('system_default_currency'));
            $current_currency = Currency::where(['code' => $currency])->first()->exchange_rate ?? 1;
            $rate = $default['exchange_rate'] >= $current_currency ? ($current_currency / $default['exchange_rate']) : ($default['exchange_rate'] / $current_currency);

            $value = floatval($amount) * floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return $value;
    }

    public static function default($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $default = Currency::find(Helpers::get_business_settings('system_default_currency'));
            $usd = Currency::where('code', 'USD')->first()->exchange_rate ?? 1;
            $rate = $default['exchange_rate'] / $usd;
            $value = floatval($amount) * floatval($rate);
        } else {
            $value = floatval($amount);
        }
        return round($value, 2);
    }

    public static function bdtTousd($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $bdt = Currency::where(['code' => 'BDT'])->first()->exchange_rate ?? 1;
            $usd = Currency::where('code', 'USD')->first()->exchange_rate ?? 1;
            $rate = $bdt / $usd;
            $value = floatval($amount) / floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return $value;
    }

    public static function usdTobdt($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $bdt = Currency::where(['code' => 'BDT'])->first()->exchange_rate ?? 1;
            $usd = Currency::where('code', 'USD')->first()->exchange_rate ?? 1;
            $rate = $usd / $bdt;
            $value = floatval($amount) / floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return $value;
    }

    public static function usdTomyr($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $myr = Currency::where(['code' => 'MYR'])->first()->exchange_rate ?? 1;
            $usd = Currency::where('code', 'USD')->first()->exchange_rate ?? 1;
            $rate = $usd / $myr;
            $value = floatval($amount) / floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return $value;
    }

    public static function usdTozar($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $zar = Currency::where(['code' => 'ZAR'])->first()->exchange_rate ?? 1;
            $usd = Currency::where('code', 'USD')->first()->exchange_rate ?? 1;
            $rate = $usd / $zar;
            $value = floatval($amount) / floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return $value;
    }

    public static function usdToinr($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $inr = Currency::where(['code' => 'INR'])->first()->exchange_rate ?? 1;
            $usd = Currency::where('code', 'USD')->first()->exchange_rate ?? 1;
            $rate = $usd / $inr;
            $value = floatval($amount) / floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return $value;
    }

    public static function usdToegp($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $egp = Currency::where(['code' => 'EGP'])->first()->exchange_rate ?? 1;
            $usd = Currency::where('code', 'USD')->first()->exchange_rate ?? 1;
            $rate = $usd / $egp;
            $value = floatval($amount) / floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return $value;
    }
}
