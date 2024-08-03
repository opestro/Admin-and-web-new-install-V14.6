<?php

namespace App\Services;

use App\Traits\PushNotificationTrait;

class CustomerWalletService
{
    use PushNotificationTrait;

    public function sendPushNotificationMessage(object $request, object $customer) :bool
    {
        $customer_fcm_token = $customer?->cm_firebase_token;
        if(!empty($customer_fcm_token)) {
            $lang = $customer?->app_language ?? getDefaultLanguage();
            $value= $this->pushNotificationMessage('fund_added_by_admin_message','customer', $lang);
            if ($value != null) {
                $data = [
                    'title' => setCurrencySymbol(amount: currencyConverter(amount: $request['amount']), currencyCode: getCurrencyCode(type: 'default')).' '.translate('_fund_added'),
                    'description' => $value,
                    'image' => '',
                    'type' => 'wallet'
                ];
                $this->sendPushNotificationToDevice($customer_fcm_token, $data);
            }
        }

        return true;
    }
}
