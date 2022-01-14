<?php

namespace App\Validator;

use App\Dto\Invitation\InvitationDtoInterface;
use App\Entity\Invitation;
use App\Exceptions\ValidationFailedException;
use App\Repository\InvitationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

class InvitationFormValidator extends FormValidator
{
    private array $mapPaths = [
        'invitedUser' => InvitationDtoInterface::INVITATION_USER_INVITED,
    ];

    public function __construct(SymfonyValidatorInterface $validator, private InvitationRepository $repository)
    {
        parent::__construct($validator);
    }

    /**
     * @param Invitation $data
     * @return void
     */
    public function validate(mixed $data): void
    {
        parent::validate($data);

        if ($data->getInvitedBy() === $data->getInvitedUser()) {
            throw new ValidationFailedException(new ArrayCollection([InvitationDtoInterface::INVITATION_USER_INVITED => 'Can not be the same as your email!']));
        }

        $invitation = $this->repository->getInvitationByUsers($data->getInvitedBy(), $data->getInvitedUser());

        if (! $invitation) {
            return;
        }

        if (
            $invitation->isPending()
        ) {
            throw new ValidationFailedException(new ArrayCollection([InvitationDtoInterface::INVITATION_USER_INVITED => 'You have already sent the invitation to this user!']));
        }

        //I was not sure if the invitations can be multiple, if in the case of canceling or declining, a user should be able to send one more invitation
//        if ($invitation->getIsAccepted()) {
//            throw new ValidationFailedException(new ArrayCollection([InvitationDtoInterface::INVITATION_USER_INVITED => 'This user has already accepted your invitation!']));
//        }
    }

    protected function mapPath(string $path): ?string
    {
        return $this->mapPaths[$path] ?? null;
    }
}
