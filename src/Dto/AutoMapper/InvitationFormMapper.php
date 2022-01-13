<?php

namespace App\Dto\AutoMapper;

use App\Dto\DataTransferObjectTemplate;
use App\Dto\Invitation\InvitationDtoInterface;
use App\Dto\Invitation\InvitationFormDto;
use App\Entity\Invitation;
use App\Repository\UserRepository;

class InvitationFormMapper extends MapperTemplate
{
    protected $mapFields = [
        InvitationDtoInterface::INVITATION_INVITED_AT => Invitation::INVITATION_INVITED_AT,
        InvitationDtoInterface::INVITATION_INVITED_BY => Invitation::INVITATION_INVITED_BY,
        InvitationDtoInterface::INVITATION_USER_INVITED => Invitation::INVITATION_USER_INVITED,
    ];

    public function __construct(private UserRepository $repository)
    {
    }

    /**
     * @param $object
     * @return DataTransferObjectTemplate
     */
    public function mapReverse($object): DataTransferObjectTemplate
    {
        $mappedData = $this->mapData($object->toArray(), true);

        return new InvitationFormDto($mappedData);
    }

    /**
     * @param DataTransferObjectTemplate $dto
     * @return Invitation
     */
    public function map(DataTransferObjectTemplate $dto): Invitation
    {
        $dataFromDto = $dto->toArray();
        $mappedData = $this->mapData($dataFromDto);
        $mappedData[Invitation::INVITATION_USER_INVITED] = $this->repository->getUserByEmail($mappedData[Invitation::INVITATION_USER_INVITED] ?? '');

        return new Invitation($mappedData);
    }
}
