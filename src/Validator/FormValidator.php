<?php

namespace App\Validator;

use App\Exceptions\ValidationFailedException;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

abstract class FormValidator implements ValidatorInterface
{
    abstract protected function mapPath(string $path): ?string;

    /**
     * @param SymfonyValidatorInterface $validator
     */
    public function __construct(private SymfonyValidatorInterface $validator)
    {
    }

    public function validate(mixed $data): void
    {
        $validation = $this->validator->validate($data);

        if (count($validation) > 0) {
            $errorMessages = $this->serialize($validation);

            throw new ValidationFailedException($errorMessages);
        }
    }

    /**
     * @param ConstraintViolationListInterface $validation
     * @return ArrayCollection
     */
    protected function serialize(ConstraintViolationListInterface $validation): ArrayCollection
    {
        $errorMessages = [];

        foreach ($validation as $val) {
            $mappedPath = $this->mapPath($val->getPropertyPath());
            $errorMessages[$mappedPath] = $val->getMessage();
        }

        return new ArrayCollection($errorMessages);
    }
}
