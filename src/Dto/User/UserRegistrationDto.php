<?php

namespace App\Dto\User;

use App\Dto\DataTransferObjectTemplate;

class UserRegistrationDto extends DataTransferObjectTemplate
{
    public const USER_FIRSTNAME_KEY = 'userFirstName';
    public const USER_LASTNAME_KEY = 'userLastName';
    public const USER_EMAIL_KEY = 'userEmail';
    public const USER_PASSWORD = 'userPassword';
    public const USER_PASSWORD_CONFIRMATION = 'userPasswordConfirm';

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
