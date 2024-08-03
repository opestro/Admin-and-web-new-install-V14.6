<?php

namespace App\Library;

class Payer
{
    private $name;
    private $email;
    private $phone;
    private $address;

    public function __construct($name, $email, $phone, $address)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getAddress() {
        return $this->address;
    }

    public function information(): array
    {
        return [
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'address' => $this->getAddress()
        ];
    }
}
