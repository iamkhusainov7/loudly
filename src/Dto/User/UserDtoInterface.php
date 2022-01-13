<?php

namespace App\Dto\User;

interface UserDtoInterface
{
    public const USER_Id_KEY = 'userId';
    public const USER_FIRSTNAME_KEY = 'userFirstName';
    public const USER_LASTNAME_KEY = 'userLastName';
    public const USER_EMAIL_KEY = 'userEmail';
    public const USER_IS_CONFIRMED_KEY = 'isConfirmed';
    public const USER_PASSWORD = 'userPassword';
    public const USER_PASSWORD_CONFIRMATION = 'userPasswordConfirm';

}