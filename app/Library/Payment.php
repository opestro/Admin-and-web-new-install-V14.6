<?php

namespace App\Library;

class Payment
{
    private $success_hook;
    private $failure_hook;
    private $currency_code;
    private $payment_method;
    private $payer_id;
    private $receiver_id;
    private $additional_data;
    private $payment_amount;
    private $external_redirect_link;
    private $attribute;
    private $attribute_id;
    private $payment_platform;

    public function __construct($success_hook, $failure_hook, $currency_code, $payment_method, $payment_platform, $payer_id = null, $receiver_id = null, $additional_data = [], $payment_amount = 0, $external_redirect_link = null, $attribute = null, $attribute_id = null)
    {
        $this->success_hook = $success_hook;
        $this->failure_hook = $failure_hook;
        $this->currency_code = $currency_code;
        $this->payment_method = $payment_method;
        $this->payer_id = $payer_id;
        $this->receiver_id = $receiver_id;
        $this->additional_data = $additional_data;
        $this->payment_amount = $payment_amount;
        $this->external_redirect_link = $external_redirect_link;
        $this->attribute = $attribute;
        $this->attribute_id = $attribute_id;
        $this->payment_platform = $payment_platform;
    }

    public function getSuccessHook()
    {
        return $this->success_hook;
    }

    public function getFailureHook()
    {
        return $this->failure_hook;
    }

    public function getCurrencyCode()
    {
        return $this->currency_code;
    }

    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    public function getPayerId()
    {
        return $this->payer_id;
    }

    public function getReceiverId()
    {
        return $this->receiver_id;
    }

    public function getAdditionalData()
    {
        return $this->additional_data;
    }

    public function getPaymentAmount()
    {
        return $this->payment_amount;
    }

    public function getExternalRedirectLink()
    {
        return $this->external_redirect_link;
    }

    public function getAttribute()
    {
        return $this->attribute;
    }

    public function getAttributeId()
    {
        return $this->attribute_id;
    }

    public function getPaymentPlatForm()
    {
        return $this->payment_platform;
    }
}
