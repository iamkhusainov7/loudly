<?php

namespace App\Events;

use App\Entity\Invitation;
use Symfony\Contracts\EventDispatcher\Event;

class InvitationSentEvent extends Event
{
    public const NAME = 'user.invited';

    /**
     * @param Invitation $invitation
     */
    public function __construct(
        private Invitation $invitation
    ) {
    }

    /**
     * @return Invitation
     */
    public function getInvitation(): Invitation
    {
        return $this->invitation;
    }
}
