<?php

namespace App\Exceptions;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidationFailedException extends HttpException
{
    public function __construct( private ArrayCollection $messages)
    {
        parent::__construct(400, 'Validation failed');
    }

    /**
     * @return ArrayCollection
     */
    public function getMessages(): ArrayCollection
    {
        return $this->messages;
    }
}