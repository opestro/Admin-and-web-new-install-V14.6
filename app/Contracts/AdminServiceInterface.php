<?php

namespace App\Contracts;

interface AdminServiceInterface
{
    public function isLoginSuccessful(string $email, string $password, string|null|bool $rememberToken): bool;

    public function logout(): void;
}
