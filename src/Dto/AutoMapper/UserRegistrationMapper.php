<?php

namespace App\Dto\AutoMapper;

use App\Dto\DataTransferObjectTemplate;
use App\Dto\User\UserDto;
use App\Dto\User\UserRegistrationDto;
use App\Entity\User;

class UserRegistrationMapper extends MapperTemplate
{
    protected $mapFields = [
        UserDto::USER_FIRSTNAME_KEY => User::USER_FIRSTNAME_KEY,
        UserDto::USER_LASTNAME_KEY => User::USER_LASTNAME_KEY,
        UserDto::USER_EMAIL_KEY => User::USER_EMAIL_KEY,
        UserRegistrationDto::USER_PASSWORD => User::USER_PASSWORD_KEY,
    ];

    /**
     * @param $object
     * @return DataTransferObjectTemplate
     */
    public function mapReverse($object): DataTransferObjectTemplate
    {
        $mappedData = $this->mapData($object->toArray(), true);

        return new UserRegistrationDto($mappedData);
    }

    /**
     * @param DataTransferObjectTemplate $dto
     * @return User
     */
    public function map(DataTransferObjectTemplate $dto): User
    {
        $dataFromDto = $dto->toArray();
        $mappedData = $this->mapData($dataFromDto);

        return new User($mappedData);
    }
}
