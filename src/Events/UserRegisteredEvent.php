<?php

namespace App\Events;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserRegisteredEvent extends Event
{
    public const NAME = 'user.registered';

    /**
     * @param User $user
     * @param string $emailVerificationLink
     */
    public function __construct(
        private User $user,
        private string $emailVerificationLink,
    ) {
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getEmailVerificationLink(): string
    {
        return $this->emailVerificationLink;
    }
}
