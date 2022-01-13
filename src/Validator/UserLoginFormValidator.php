<?php

namespace App\Validator;

use App\Dto\User\UserLoginDto;
use App\Entity\User;
use App\Exceptions\LoginFailedException;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserLoginFormValidator implements ValidatorInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private UserRepository $repository)
    {
    }


    /**
     * @param UserLoginDto $data
     * @return void
     */
    public function validate(mixed $data): void
    {
        $user = $this->repository->findOneBy([User::USER_EMAIL_KEY => $data->userEmail]);

        if (! $user) {
            throw new LoginFailedException();
        }


        if (! $this->passwordHasher->isPasswordValid($user, $data->userPassword)) {
            throw new LoginFailedException();
        }
    }
}
