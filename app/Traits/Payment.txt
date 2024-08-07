<?php

namespace App\Traits;

use App\Models\PaymentRequest;
use InvalidArgumentException;

trait Payment
{
    public static function generate_link(object $payer, object $payment_info, Object $receiver)
    {
        if ($payment_info->getPaymentAmount() === 0) {
            throw new InvalidArgumentException('Payment amount can not be 0');
        }

        if (!in_array(strtoupper($payment_info->getCurrencyCode()), array_column(GATEWAYS_CURRENCIES, 'code'))) {
            throw new InvalidArgumentException('Need a valid currency code');
        }

        if (!in_array($payment_info->getPaymentMethod(), array_column(GATEWAYS_PAYMENT_METHODS, 'key'))) {
            throw new InvalidArgumentException('Need a valid payment gateway');
        }

        if (!is_array($payment_info->getAdditionalData())) {
            throw new InvalidArgumentException('Additional data should be in a valid array');
        }

        $payment = new PaymentRequest();
        $payment->payment_amount = $payment_info->getPaymentAmount();
        $payment->success_hook = $payment_info->getSuccessHook();
        $payment->failure_hook = $payment_info->getFailureHook();
        $payment->payer_id = $payment_info->getPayerId();
        $payment->receiver_id = $payment_info->getReceiverId();
        $payment->currency_code = strtoupper($payment_info->getCurrencyCode());
        $payment->payment_method = $payment_info->getPaymentMethod();
        $payment->additional_data = json_encode($payment_info->getAdditionalData());
        $payment->payer_information = json_encode($payer->information());
        $payment->receiver_information = json_encode($receiver->information());
        $payment->external_redirect_link = $payment_info->getExternalRedirectLink();
        $payment->attribute = $payment_info->getAttribute();
        $payment->attribute_id = $payment_info->getAttributeId();
        $payment->payment_platform = $payment_info->getPaymentPlatForm();
        $payment->save();

        if ($payment->payment_method == 'ssl_commerz') {
            return url("payment/sslcommerz/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'stripe'){
            return url("payment/stripe/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'paymob_accept'){
            return url("payment/paymob/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'flutterwave'){
            return url("payment/flutterwave-v3/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'paytm'){
            return url("payment/paytm/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'paypal'){
            return url("payment/paypal/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'paytabs'){
            return url("payment/paytabs/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'liqpay'){
            return url("payment/liqpay/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'razor_pay'){
            return url("payment/razor-pay/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'senang_pay'){
            return url("payment/senang-pay/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'mercadopago'){
            return url("payment/mercadopago/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'bkash'){
            return url("payment/bkash/make-payment/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'paystack'){
            return url("payment/paystack/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'fatoorah'){
            return url("payment/fatoorah/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'xendit'){
            return url("payment/xendit/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'amazon_pay'){
            return url("payment/amazon/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'iyzi_pay'){
            return url("payment/iyzipay/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'hyper_pay'){
            return url("payment/hyperpay/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'foloosi'){
            return url("payment/foloosi/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'ccavenue'){
            return url("payment/ccavenue/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'pvit'){
            return url("payment/pvit/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'moncash'){
            return url("payment/moncash/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'thawani'){
            return url("payment/thawani/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'tap'){
            return url("payment/tap/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'viva_wallet'){
            return url("payment/viva/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'hubtel'){
            return url("payment/hubtel/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'maxicash'){
            return url("payment/maxicash/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'esewa'){
            return url("payment/esewa/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'swish'){
            return url("payment/swish/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'momo'){
            return url("payment/momo/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'payfast'){
            return url("payment/payfast/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'worldpay'){
            return url("payment/worldpay/pay/?payment_id={$payment->id}");
        }else if($payment->payment_method == 'sixcash'){
            return url("payment/sixcash/pay/?payment_id={$payment->id}");
        }

        return false;
    }
}
