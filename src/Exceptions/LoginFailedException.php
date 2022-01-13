<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginFailedException extends HttpException
{
    public function __construct(string $message = 'User login or password is wrong!')
    {
        parent::__construct(400, $message);
    }
}
