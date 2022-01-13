<?php

namespace App\Validator;

use App\Exceptions\ValidationFailedException;

interface ValidatorInterface
{
    /**
     * @param mixed $data
     * @return mixed
     * @throws ValidationFailedException
     */
    public function validate(mixed $data): void;
}
