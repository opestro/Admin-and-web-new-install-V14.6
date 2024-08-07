<?php

namespace App\Services;

use App\Traits\FileManagerTrait;

class CustomerService
{
    use FileManagerTrait;

    /**
     * @return array[f_name: mixed, l_name: mixed, email: mixed, phone: mixed, country: mixed, city: mixed, zip: mixed, street_address: mixed, password: string]
     */
    public function getCustomerData(object $request):array
    {
        return [
            'f_name' => $request['f_name'],
            'l_name' => $request['l_name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'country' => $request['country']??null,
            'city' => $request['city']??null,
            'zip' => $request['zip_code']??null,
            'street_address' =>$request['address']??null,
            'password' => bcrypt($request['password'] ?? 'password')
        ];
    }
    public function deleteImage(object $data): bool
    {
        if ($data['image']) {$this->delete('profile/'.$data['image']);};
        return true;
    }
}
