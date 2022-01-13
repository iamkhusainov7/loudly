<?php

namespace App\Dto\Invitation;

interface InvitationDtoInterface
{
    public const INVITATION_Id_KEY = 'invitationId';
    public const INVITATION_UPDATED_AT = 'invitationUpdatedAt';
    public const INVITATION_INVITED_AT = 'invitationAt';
    public const INVITATION_IS_CANCELED = 'invitationIsCanceled';
    public const INVITATION_IS_ACCEPTED = 'isAccepted';
    public const INVITATION_INVITED_BY = 'invitationInvitedBy';
    public const INVITATION_USER_INVITED = 'invitationInvitedUser';
}
