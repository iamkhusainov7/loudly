<?php

namespace App\Dto\User;

use App\Dto\DataTransferObjectTemplate;

class UserLoginDto extends DataTransferObjectTemplate implements UserDtoInterface
{
    public ?string $userEmail;
    public ?string $userPassword;

    public function toArray(): array
    {
        return (array) $this;
    }
}
