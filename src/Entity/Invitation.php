<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InvitationRepository::class)]
class Invitation
{
    public const INVITATION_Id_KEY = 'id';
    public const INVITATION_INVITED_AT = 'invited_at';
    public const INVITATION_IS_CANCELED = 'canceled';
    public const INVITATION_IS_ACCEPTED = 'is_accepted';
    public const INVITATION_IS_DECLINED = 'is_declined';
    public const INVITATION_INVITED_BY = 'invited_by';
    public const INVITATION_USER_INVITED = 'invited_user';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $invitedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'receivedInvitations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?User $invitedUser;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'sentInvitations')]
    #[ORM\JoinColumn(nullable: false)]
    private User $invitedBy;

    #[ORM\Column(type: 'boolean')]
    private bool $isCanceled;

    #[ORM\Column(type: 'boolean')]
    private bool $isAccepted;

    #[ORM\Column(type: 'boolean')]
    private bool $isDeclined;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private \DateTimeImmutable $updatedAt;

    public function __construct(array $data)
    {
        $this->invitedUser = $data[self::INVITATION_USER_INVITED] ?? null;
        $this->invitedBy = $data[self::INVITATION_INVITED_BY] ?? null;
        $this->isAccepted = $data[self::INVITATION_IS_ACCEPTED] ?? false;
        $this->isCanceled = $data[self::INVITATION_IS_CANCELED] ?? false;
        $this->isDeclined = $data[self::INVITATION_IS_DECLINED] ?? false;
        $this->invitedAt = $data[self::INVITATION_INVITED_AT] ?? new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvitedAt(): ?\DateTimeImmutable
    {
        return $this->invitedAt;
    }

    public function setInvitedAt(\DateTimeImmutable $invitedAt): self
    {
        $this->invitedAt = $invitedAt;

        return $this;
    }

    public function getInvitedUser(): ?User
    {
        return $this->invitedUser;
    }

    public function setInvitedUser(?User $invitedUser): self
    {
        $this->invitedUser = $invitedUser;

        return $this;
    }

    public function getInvitedBy(): ?User
    {
        return $this->invitedBy;
    }

    public function setInvitedBy(?User $invitedBy): self
    {
        $this->invitedBy = $invitedBy;

        return $this;
    }

    public function getIsCanceled(): ?bool
    {
        return $this->isCanceled;
    }

    public function setIsCanceled(bool $isCanceled): self
    {
        $this->isCanceled = $isCanceled;

        return $this;
    }

    public function getIsAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(bool $isAccepted): self
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    public function getIsDeclined(): ?bool
    {
        return $this->isDeclined;
    }

    public function setIsDeclined(bool $isDeclined): self
    {
        $this->isDeclined = $isDeclined;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isPending(): bool
    {
        return ! $this->isDeclined && ! $this->isCanceled && ! $this->isAccepted;
    }
}
