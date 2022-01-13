<?php

namespace App\Validator;

use App\Dto\User\UserLoginDto;
use App\Entity\User;
use App\Exceptions\LoginFailedException;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserLoginFormValidator implements ValidatorInterface
{
    private ?User $user = null;

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

        if (! $user->getIsConfirmed()) {
            throw new LoginFailedException('Please, verify your email first!');
        }

        if (! $this->passwordHasher->isPasswordValid($user, $data->userPassword)) {
            throw new LoginFailedException();
        }

        $this->user = $user;
    }

    /**
     * @return User|null
     */
    public function getValidated(): ?User
    {
        return $this->user;
    }
}
