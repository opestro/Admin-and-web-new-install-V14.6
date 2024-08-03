<?php

use App\Models\Currency;

if (!function_exists('loadCurrency')) {
    /**
     * @return void
     */
    function loadCurrency(): void
    {
        $defaultCurrency = getWebConfig(name: 'system_default_currency');
        $currentCurrencyInfo = session('system_default_currency_info');
        if (!session()->has('system_default_currency_info') || $defaultCurrency != $currentCurrencyInfo['id']) {
            $id = getWebConfig(name: 'system_default_currency');
            $currency = Currency::find($id);
            session()->put('system_default_currency_info', $currency);
            session()->put('currency_code', $currency->code);
            session()->put('currency_symbol', $currency->symbol);
            session()->put('currency_exchange_rate', $currency->exchange_rate);
        }
    }
}

if (!function_exists('currencyConverter')) {
    /** system default currency to usd convert
     * @param float $amount
     * @param string $to
     * @return float|int
     */
    function currencyConverter(float $amount, string $to = USD): float|int
    {
        $currencyModel = getWebConfig('currency_model');
        if ($currencyModel == MULTI_CURRENCY) {
            $default = Currency::find(getWebConfig('system_default_currency'))->exchange_rate;
            $exchangeRate = exchangeRate($to);
            $rate = $default / $exchangeRate;
            $value = $amount / floatval($rate);
        } else {
            $value = $amount;
        }
        return $value;
    }
}

if (!function_exists('usdToDefaultCurrency')) {
    /**
     * system usd currency to default convert
     * @param float|int $amount
     * @return float|int
     */
    function usdToDefaultCurrency(float|int $amount): float|int
    {
        $currencyModel = getWebConfig('currency_model');
        if ($currencyModel == MULTI_CURRENCY) {
            if (session()->has('default')) {
                $default = session('default');
            } else {
                $default = Currency::find(getWebConfig('system_default_currency'))->exchange_rate;
                session()->put('default', $default);
            }

            if (session()->has('usd')) {
                $usd = session('usd');
            } else {
                $usd = exchangeRate(USD);
                session()->put('usd', $usd);
            }

            $rate = $default / $usd;
            $value = $amount * floatval($rate);
        } else {
            $value = $amount;
        }

        return round($value, 2);
    }
}

if (!function_exists('webCurrencyConverter')) {
    /**
     * currency convert for web panel
     * @param string|int|float $amount
     * @return float|string
     */
    function webCurrencyConverter(string|int|float $amount): float|string
    {
        $currencyModel = getWebConfig('currency_model');
        if ($currencyModel == MULTI_CURRENCY) {
            if (session()->has('usd')) {
                $usd = session('usd');
            } else {
                $usd = Currency::where(['code' => 'USD'])->first()->exchange_rate;
                session()->put('usd', $usd);
            }
            $myCurrency = \session('currency_exchange_rate');
            $rate = $myCurrency / $usd;
        } else {
            $rate = 1;
        }

        return setCurrencySymbol(amount: round($amount * $rate, 2), currencyCode: getCurrencyCode(type: 'web'));
    }
}

if (!function_exists('exchangeRate')) {
    /**
     * @param string $currencyCode
     * @return float|int
     */
    function exchangeRate(string $currencyCode = USD): float|int
    {
        return Currency::where('code', $currencyCode)->first()->exchange_rate ?? 1;
    }
}

if (!function_exists('getCurrencySymbol')) {
    /**
     * @param string $currencyCode
     * @return float|int|string
     */
    function getCurrencySymbol(string $currencyCode = USD): float|int|string
    {
        loadCurrency();
        if (session()->has('currency_symbol')) {
            $currentSymbol = session('currency_symbol');
        } else {
            $systemDefaultCurrencyInfo = session('system_default_currency_info');
            $currentSymbol = $systemDefaultCurrencyInfo->symbol;
        }
        return $currentSymbol;
    }
}

if (!function_exists('setCurrencySymbol')) {
    /**
     * @param string|int|float $amount
     * @param string $currencyCode
     * @return string
     */
    function setCurrencySymbol(string|int|float $amount, string $currencyCode=USD): string
    {
        $decimal_point_settings = getWebConfig('decimal_point_settings');
        $position = getWebConfig('currency_symbol_position');
        if ($position === 'left') {
            $string = getCurrencySymbol(currencyCode: $currencyCode) . '' . number_format($amount, (!empty($decimal_point_settings) ? $decimal_point_settings : 0));
        } else {
            $string = number_format($amount, !empty($decimal_point_settings) ? $decimal_point_settings : 0) . '' . getCurrencySymbol(currencyCode: $currencyCode);
        }
        return $string;
    }
}

if (!function_exists('getCurrencyCode')) {
    /**
     * @param string $type default,web
     * @return string
     */
    function getCurrencyCode(string $type='default'): string
    {
        if($type == 'web'){
            $currencyCode = session('currency_code');
        }else{
            if (session()->has('system_default_currency_info')){
                $currencyCode = session('system_default_currency_info')->code;
            }else{
                $currencyId = getWebConfig('system_default_currency');
                $currencyCode = Currency::where('id', $currencyId)->first()->code;
            }
        }
        return $currencyCode;
    }
}

if (!function_exists('getFormatCurrency')) {
    /**
     * @param string|int|float $amount
     * @return string
     */
    function getFormatCurrency(string|int|float $amount): string
    {
        $suffixes = ["1t+" => 1000000000000, "B+" => 1000000000, "M+" => 1000000, "K+" => 1000];
        foreach ($suffixes as $suffix => $factor) {
            if ($amount >= $factor) {
                $div = $amount / $factor;
                $formattedValue = number_format($div,1 ) . $suffix;
                break;
            }
        }

        if (!isset($formattedValue)) {
            $formattedValue = number_format($amount, 2);
        }

        return $formattedValue;
    }
}

