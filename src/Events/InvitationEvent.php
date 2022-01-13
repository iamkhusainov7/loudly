<?php

namespace App\Events;

use App\Entity\Invitation;
use Symfony\Contracts\EventDispatcher\Event;

class InvitationEvent extends Event
{
    public const USER_INVITED = 'user.invited';
    public const USER_CANCELED = 'user.invite.canceled';

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
