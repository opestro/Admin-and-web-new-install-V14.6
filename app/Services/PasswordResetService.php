<?php

namespace App\Services;

class PasswordResetService
{
    public function getAddData(string|int $identity, string $token,string $userType):array
    {
        return [
            'identity' => $identity,
            'token' => $token,
            'user_type'=>$userType,
            'created_at' => now(),
        ];
    }
}
