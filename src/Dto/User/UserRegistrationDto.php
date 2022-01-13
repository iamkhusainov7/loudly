<?php

namespace App\Dto\User;

use App\Dto\DataTransferObjectTemplate;

class UserRegistrationDto extends DataTransferObjectTemplate implements UserDtoInterface
{
    public ?int $userId;

    public ?string $userFirstName;

    public ?string $userLastName;

    public ?string $userEmail;

    public ?string $userPassword;

    public ?string $userPasswordConfirm;

    public function toArray(): array
    {
        return (array) $this;
    }
}
