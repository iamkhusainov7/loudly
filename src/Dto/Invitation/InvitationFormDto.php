<?php

namespace App\Dto\Invitation;

use App\Dto\DataTransferObjectTemplate;
use App\Entity\User;

class InvitationFormDto extends DataTransferObjectTemplate implements InvitationDtoInterface
{
    public ?string $invitationInvitedUser;
    public User $invitationInvitedBy;

    public function toArray(): array
    {
        return (array) $this;
    }
}
