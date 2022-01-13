<?php

namespace App\Entity;

use App\Dto\User\UserDto;
use App\Dto\User\UserRegistrationDto;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity("email")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const USER_ID_KEY = 'id';
    public const USER_FIRSTNAME_KEY = 'first_name';
    public const USER_LASTNAME_KEY = 'last_name';
    public const USER_EMAIL_KEY = 'email';
    public const USER_IS_CONFIRMED_KEY = 'is_confirmed';
    public const USER_PASSWORD_KEY = 'user_password';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[Assert\Length(
        max: 255,
        maxMessage: "Your". UserDto::USER_FIRSTNAME_KEY . " cannot be blank",

    )]
    #[Assert\NotBlank(message: UserDto::USER_FIRSTNAME_KEY . " cannot be blank")]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $firstName;

    #[Assert\Length(
        max: 255,
        maxMessage: "Your". UserDto::USER_LASTNAME_KEY . " cannot be longer than {{ limit }} characters",

    )]
    #[Assert\NotBlank(message: UserDto::USER_FIRSTNAME_KEY . " cannot be blank")]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $lastName;

    #[Assert\Length(
        max: 255,
        maxMessage: "Your". UserDto::USER_EMAIL_KEY . " cannot be longer than {{ limit }} characters",

    )]
    #[Assert\NotBlank(message: UserDto::USER_FIRSTNAME_KEY . " cannot be blank")]
    #[Assert\Email]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: UserRegistrationDto::USER_PASSWORD . " cannot be blank")]
    private string $password;

    #[ORM\Column(type: 'boolean')]
    private bool $isConfirmed = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private \DateTimeInterface $confirmedAt;

    public function __construct(array $data = [])
    {
        $this->firstName = $data[self::USER_FIRSTNAME_KEY] ?? null;
        $this->lastName = $data[self::USER_LASTNAME_KEY] ?? null;
        $this->email = $data[self::USER_EMAIL_KEY] ?? null;
        $this->password = $data[self::USER_PASSWORD_KEY] ?? null;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed(bool $isConfirmed): self
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getConfirmedAt(): ?\DateTimeInterface
    {
        return $this->confirmedAt;
    }

    public function setConfirmedAt(?\DateTimeInterface $confirmedAt): self
    {
        $this->confirmedAt = $confirmedAt;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        $this->roles = [];
    }

    public function getUsername(): string
    {
        return "{$this->getFirstName()} {$this->getLastName()}";
    }
}
