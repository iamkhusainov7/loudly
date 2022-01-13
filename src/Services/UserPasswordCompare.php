<?php

namespace App\Services;

use App\Services\Contracts\PasswordCompareInterface;

class UserPasswordCompare implements PasswordCompareInterface
{
    public function isEqual(?string $password, ?string $passwordToBeCompared): bool
    {
        return ($password !== null && $passwordToBeCompared !== null) && $password === $passwordToBeCompared;
    }
}
