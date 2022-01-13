<?php

namespace App\Dto\AutoMapper;

use App\Dto\DataTransferObjectTemplate;
use Doctrine\Common\Collections\ArrayCollection;

interface AutoMapperInterface
{
    public function map(DataTransferObjectTemplate $dto);

    public function mapMany(ArrayCollection $dtos): ArrayCollection;

    public function mapReverse(mixed $object);

    public function mapManyReverse(ArrayCollection $objects): ArrayCollection;

    public function getFieldFromMap(string $field): ?string;
}
