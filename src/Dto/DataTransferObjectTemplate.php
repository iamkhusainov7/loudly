<?php

namespace App\Dto;

use ReflectionClass;
use ReflectionProperty;

abstract class DataTransferObjectTemplate
{
    /**
     * @return array
     */
    public abstract function toArray(): array;

    public function __construct(array $parameters = [])
    {
        $this->setFields($parameters);
    }

    public function setFields(array $parameters = []): self
    {
        $class = new ReflectionClass(static::class);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $property = $reflectionProperty->getName();
            $this->{$property} = $parameters[$property] ?? null;
        }

        return $this;
    }
}
