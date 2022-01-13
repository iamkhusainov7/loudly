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
    public const STATUS = 'status';
    public const INVITATION_INVITED_BY = 'invited_by';
    public const INVITATION_USER_INVITED = 'invited_user';

    /**
     * Statuses
     */

    public const IS_ACCEPTED = 'accepted';
    public const IS_PENDING = 'pending';
    public const IS_DECLINED = 'declined';
    public const IS_CANCELED = 'canceled';

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

    #[ORM\Column(type: 'string')]
    private string $status = self::IS_PENDING;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(array $data)
    {
        $this->invitedUser = $data[self::INVITATION_USER_INVITED] ?? null;
        $this->invitedBy = $data[self::INVITATION_INVITED_BY] ?? null;
        $this->status =  $data[self::STATUS] ?? self::IS_PENDING;
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

    public function getIsCanceled(): bool
    {
        return $this->status === self::IS_CANCELED;
    }

    public function setIsCanceled(): self
    {
        $this->status = self::IS_CANCELED;

        return $this;
    }

    public function getIsAccepted(): bool
    {
        return $this->status === self::IS_ACCEPTED;
    }

    public function setIsAccepted(): self
    {
        $this->status = self::IS_ACCEPTED;

        return $this;
    }

    public function getIsDeclined(): ?bool
    {
        return $this->status === self::IS_DECLINED;
    }

    public function setIsDeclined(): self
    {
        $this->status = self::IS_DECLINED;

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
        return $this->status === self::IS_PENDING;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
