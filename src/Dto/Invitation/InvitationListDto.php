<?php

namespace App\Dto\Invitation;

use App\Dto\DataTransferObjectTemplate;
use App\Entity\Invitation;
use App\Entity\User;

class InvitationListDto extends DataTransferObjectTemplate implements InvitationDtoInterface
{
    public ?string $invitationId;
    public ?string $invitationInvitedUser;
    public ?string $invitationInvitedBy;
    public ?string $invitationStatus;
    public ?\DateTimeImmutable $invitationUpdatedAt;
    public ?\DateTimeImmutable $invitationInvitedAt;

    public static function fromEntity(Invitation $invitation): InvitationListDto
    {
        return new self([
            self::INVITATION_Id_KEY => $invitation->getId(),
            self::INVITATION_STATUS => $invitation->getStatus(),
            self::INVITATION_INVITED_AT => $invitation->getInvitedAt(),
            self::INVITATION_INVITED_BY => $invitation->getInvitedBy()->getEmail(),
            self::INVITATION_USER_INVITED => $invitation->getInvitedUser()->getEmail(),
            self::INVITATION_UPDATED_AT => $invitation->getUpdatedAt(),
        ]);
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}
