<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait PaymentGatewayTrait
{
    public function getPaymentGatewaySupportedCurrencies($key = null): array
    {
        $paymentGateway = [
            "amazon_pay" => [
                "USD" => "United States Dollar",
                "GBP" => "Pound Sterling",
                "EUR" => "Euro",
                "JPY" => "Japanese Yen",
                "AUD" => "Australian Dollar",
                "NZD" => "New Zealand Dollar",
                "CAD" => "Canadian Dollar"
            ],
            "bkash" => [
                "BDT" => "Bangladeshi Taka"
            ],
            "cashfree" => [
                "INR" => "Indian Rupee"
            ],
            "ccavenue" => [
                "INR" => "Indian Rupee"
            ],
            "ccavenue" => [
                "INR" => "Indian Rupee"
            ],
            "esewa" => [
                "NPR" => "Nepalese Rupee"
            ],
            "fatoorah" => [
                "KWD" => "Kuwaiti Dinar",
                "SAR" => "Saudi Riyal"
            ],
            "flutterwave" => [
                "NGN" => "Nigerian Naira",
                "GHS" => "Ghanaian Cedi",
                "KES" => "Kenyan Shilling",
                "ZAR" => "South African Rand",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "GBP" => "British Pound Sterling",
                "CAD" => "Canadian Dollar",
                "XAF" => "Central African CFA Franc",
                "CLP" => "Chilean Peso",
                "COP" => "Colombian Peso",
                "EGP" => "Egyptian Pound",
                "GNF" => "Guinean Franc",
                "MWK" => "Malawian Kwacha",
                "MAD" => "Moroccan Dirham",
                "RWF" => "Rwandan Franc",
                "SLL" => "Sierra Leonean Leone",
                "STD" => "São Tomé and Príncipe Dobra",
                "TZS" => "Tanzanian Shilling",
                "UGX" => "Ugandan Shilling",
                "XOF" => "West African CFA Franc BCEAO",
                "ZMW" => "Zambian Kwacha"
            ],
            "foloosi" => [
                "AED" => "United Arab Emirates Dirham"
            ],
            "hubtel" => [
                "GHS" => "Ghanaian Cedi"
            ],
            "hyper_pay" => [
                "AED" => "United Arab Emirates Dirham",
                "SAR" => "Saudi Riyal",
                "EGP" => "Egyptian Pound",
                "BHD" => "Bahraini Dinar",
                "KWD" => "Kuwaiti Dinar",
                "OMR" => "Omani Rial",
                "QAR" => "Qatari Riyal",
                "USD" => "United States Dollar"
            ],
            "instamojo" => [
                "INR" => "Indian Rupee"
            ],
            "iyzi_pay" => [
                "TRY" => "Turkish Lira"
            ],
            "liqpay" => [
                "UAH" => "Ukrainian Hryvnia",
                "USD" => "United States Dollar",
                "EUR" => "Euro"
            ],
            "maxicash" => [
                "PHP" => "Philippine Peso"
            ],
            "mercadopago" => [
                "ARS" => "Argentine Peso",
                "BRL" => "Brazilian Real",
                "CLP" => "Chilean Peso",
                "COP" => "Colombian Peso",
                "MXN" => "Mexican Peso",
                "PEN" => "Peruvian Sol",
                "UYU" => "Uruguayan Peso",
                "USD" => "United States Dollar"
            ],
            "momo" => [
                "VND" => "Vietnamese Dong"
            ],
            "moncash" => [
                "HTG" => "Haitian Gourde"
            ],
            "payfast" => [
                "ZAR" => "South African Rand"
            ],
            "paymob_accept" => [
                "EGP" => "Egyptian Pound"
            ],
            "paypal" => [
                "AUD" => "Australian Dollar",
                "BRL" => "Brazilian Real",
                "CAD" => "Canadian Dollar",
                "CZK" => "Czech Koruna",
                "DKK" => "Danish Krone",
                "EUR" => "Euro",
                "HKD" => "Hong Kong Dollar",
                "HUF" => "Hungarian Forint",
                "INR" => "Indian Rupee",
                "ILS" => "Israeli New Shekel",
                "JPY" => "Japanese Yen",
                "MYR" => "Malaysian Ringgit",
                "MXN" => "Mexican Peso",
                "TWD" => "New Taiwan Dollar",
                "NZD" => "New Zealand Dollar",
                "NOK" => "Norwegian Krone",
                "PHP" => "Philippine Peso",
                "PLN" => "Polish Zloty",
                "GBP" => "Pound Sterling",
                "RUB" => "Russian Ruble",
                "SGD" => "Singapore Dollar",
                "SEK" => "Swedish Krona",
                "CHF" => "Swiss Franc",
                "THB" => "Thai Baht",
                "TRY" => "Turkish Lira",
                "USD" => "United States Dollar"
            ],
            "paystack" => [
                "NGN" => "Nigerian Naira"
            ],
            "paytabs" => [
                "AED" => "United Arab Emirates Dirham",
                "SAR" => "Saudi Riyal",
                "BHD" => "Bahraini Dinar",
                "KWD" => "Kuwaiti Dinar",
                "OMR" => "Omani Rial",
                "QAR" => "Qatari Riyal",
                "EGP" => "Egyptian Pound",
                "USD" => "United States Dollar"
            ],
            "paytm" => [
                "INR" => "Indian Rupee"
            ],
            "phonepe" => [
                "INR" => "Indian Rupee"
            ],
            "pvit" => [
                "NGN" => "Nigerian Naira"
            ],
            "razor_pay" => [
                "INR" => "Indian Rupee"
            ],
            "senang_pay" => [
                "MYR" => "Malaysian Ringgit"
            ],
            "sixcash" => [
                "BDT" => "Bangladeshi Taka"
            ],
            "ssl_commerz" => [
                "BDT" => "Bangladeshi Taka"
            ],
            "stripe" => [
                "USD" => "United States Dollar",
                "AUD" => "Australian Dollar",
                "CAD" => "Canadian Dollar",
                "EUR" => "Euro",
                "GBP" => "Pound Sterling",
                "JPY" => "Japanese Yen",
                "NZD" => "New Zealand Dollar",
                "CHF" => "Swiss Franc",
                "DKK" => "Danish Krone",
                "NOK" => "Norwegian Krone",
                "SEK" => "Swedish Krona",
                "SGD" => "Singapore Dollar",
                "HKD" => "Hong Kong Dollar"
            ],
            "swish" => [
                "SEK" => "Swedish Krona"
            ],
            "tap" => [
                "AED" => "United Arab Emirates Dirham",
                "SAR" => "Saudi Riyal",
                "BHD" => "Bahraini Dinar",
                "KWD" => "Kuwaiti Dinar",
                "OMR" => "Omani Rial",
                "QAR" => "Qatari Riyal"
            ],
            "thawani" => [
                "OMR" => "Omani Rial"
            ],
            "viva_wallet" => [
                "EUR" => "Euro"
            ],
            "worldpay" => [
                "GBP" => "Pound Sterling",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "JPY" => "Japanese Yen"
            ],
            "xendit" => [
                "IDR" => "Indonesian Rupiah",
                "PHP" => "Philippine Peso",
                "VND" => "Vietnamese Dong",
                "THB" => "Thai Baht",
                "MYR" => "Malaysian Ringgit",
                "SGD" => "Singapore Dollar"
            ],
        ];

        if ($key) {
            return $paymentGateway[$key];
        }
        return $paymentGateway;
    }

}
