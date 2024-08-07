<?php

namespace App\Services;

class ShippingAddressService
{
    public function getAddAddressData($request,$customerId,$addressType):array
    {
        return [
            'customer_id' => $customerId,
            'contact_person_name' => $request['f_name'].' '.$request['f_name'],
            'address_type' => $addressType,
            'address' => $request['address'],
            'city' => $request['city'],
            'zip' => $request['zip_code'],
            'country' => $request['country'],
            'phone' => $request['phone'],
            'is_billing' => 0,
            'latitude' => 0,
            'longitude' => 0,
        ];
    }
}
