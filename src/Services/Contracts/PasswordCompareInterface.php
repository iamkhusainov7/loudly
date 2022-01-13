<?php

namespace App\Services\Contracts;

interface PasswordCompareInterface
{
    public function isEqual(string $password, string $passwordToBeCompared): bool;
}
