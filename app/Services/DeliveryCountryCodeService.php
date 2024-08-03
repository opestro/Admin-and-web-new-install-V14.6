<?php

namespace App\Services;

use App\Enums\GlobalConstant;
use App\Traits\FileManagerTrait;

class DeliveryCountryCodeService
{
    use FileManagerTrait;

    public function getDeliveryCountryArray(object|null $deliveryCountryCodes):array
    {
        $data = array();
        foreach ($deliveryCountryCodes as $deliveryCountryCode) {
            foreach (GlobalConstant::COUNTRIES as $key => $country) {
                if ($country['code'] == $deliveryCountryCode['country_code']) {
                    $data[$key]['code'] = $country['code'];
                    $data[$key]['name'] = $country['name'];
                }
            }
        }

        return $data;
    }

}
