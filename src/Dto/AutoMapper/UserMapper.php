<?php

namespace App\Dto\AutoMapper;

use App\Dto\DataTransferObjectTemplate;
use App\Dto\User\UserDto;
use App\Dto\User\UserRegistrationDto;
use App\Entity\User;

class UserMapper extends MapperTemplate
{
    protected $mapFields = [
        UserDto::USER_Id_KEY => User::USER_ID_KEY,
        UserDto::USER_FIRSTNAME_KEY => User::USER_FIRSTNAME_KEY,
        UserDto::USER_LASTNAME_KEY => User::USER_LASTNAME_KEY,
        UserDto::USER_EMAIL_KEY => User::USER_EMAIL_KEY,
        UserDto::USER_IS_CONFIRMED_KEY => User::USER_IS_CONFIRMED_KEY
    ];

    /**
     * @param $object
     * @return DataTransferObjectTemplate
     */
    public function mapReverse($object): DataTransferObjectTemplate
    {
        $mappedData = $this->mapData($object->toArray(), true);

        return new UserDto($mappedData);
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
