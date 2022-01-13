<?php

namespace App\Validator;

use App\Dto\User\UserDto;
use App\Dto\User\UserRegistrationDto;
use App\Entity\User;
use App\Exceptions\ValidationFailedException;
use App\Services\Contracts\PasswordCompareInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

class UserRegistrationFormValidator extends FormValidator
{
    public const USER_KEY = 'user';

    private array $mapPaths = [
        'email' => UserDto::USER_EMAIL_KEY,
        'lastName' => UserDto::USER_LASTNAME_KEY,
        'firstName' => UserDto::USER_FIRSTNAME_KEY,
        'password' => UserRegistrationDto::USER_PASSWORD,
    ];

    public function __construct(SymfonyValidatorInterface $validator, private PasswordCompareInterface $compare)
    {
        parent::__construct($validator);
    }

    /**
     * @param ArrayCollection $data
     * @return mixed|void
     */
    public function validate(mixed $data): void
    {
        /**
         * @var $user User
         */
        $user = $data->get(self::USER_KEY);
        parent::validate($user);

        if (! $data->get(UserRegistrationDto::USER_PASSWORD_CONFIRMATION)) {
            throw new ValidationFailedException(
                new ArrayCollection([
                    UserRegistrationDto::USER_PASSWORD_CONFIRMATION => UserRegistrationDto::USER_PASSWORD_CONFIRMATION . ' can not be blank'
                ]));
        }

        if (! $this->compare->isEqual($user->getPassword(), $data->get(UserRegistrationDto::USER_PASSWORD_CONFIRMATION))) {
            throw new ValidationFailedException(
                new ArrayCollection([
                    UserRegistrationDto::USER_PASSWORD_CONFIRMATION => 'Passwords must be equal!'
                ])
            );
        }
    }

    protected function mapPath(string $path): ?string
    {
        return $this->mapPaths[$path] ?? null;
    }
}
