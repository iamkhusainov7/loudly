<?php

namespace App\Dto\AutoMapper;

use Doctrine\Common\Collections\ArrayCollection;

abstract class MapperTemplate implements AutoMapperInterface
{
    protected $mapFields = [];

    public function mapMany(ArrayCollection $dtos): ArrayCollection
    {
        $result = new ArrayCollection();

        foreach ($dtos as $dto) {
            $result->add($this->map($dto));
        }

        return $result;
    }

    /**
     * @param ArrayCollection $objects
     * @return ArrayCollection
     */
    public function mapManyReverse(ArrayCollection $objects): ArrayCollection
    {
        $result = new ArrayCollection();

        foreach ($objects as $object) {
            $result->add($this->mapReverse($object));
        }

        return $result;
    }

    /**
     *
     * @param array $data
     * @param bool $reverse
     * @return array
     */
    protected function mapData(array $data, bool $reverse = false): array
    {
        $fields = $reverse ? array_flip($this->mapFields) : $this->mapFields;
        $result = [];

        foreach ($data as $key => $value) {
            if (!isset($fields[$key])) {
                continue;
            }

            $result[$fields[$key]] = $value;
        }

        return $result;
    }

    public function getFieldFromMap(string $field): ?string
    {
        return $this->mapFields[$field] ?? null;
    }
}
