<?php

namespace App\Dto\User;

use App\Dto\DataTransferObjectTemplate;

class UserDto extends DataTransferObjectTemplate
{
    public const USER_Id_KEY = 'userId';
    public const USER_FIRSTNAME_KEY = 'userFirstName';
    public const USER_LASTNAME_KEY = 'userLastName';
    public const USER_EMAIL_KEY = 'userEmail';
    public const USER_IS_CONFIRMED_KEY = 'isConfirmed';

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
