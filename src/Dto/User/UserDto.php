<?php

namespace App\Dto\User;

use App\Dto\DataTransferObjectTemplate;

class UserDto extends DataTransferObjectTemplate implements UserDtoInterface
{
    public ?int $userId;

    public ?string $userFirstName;

    public ?string $userLastName;

    public ?string $userEmail;

    public ?bool $isConfirmed;

    public function toArray(): array
    {
        return (array) $this;
    }
}
