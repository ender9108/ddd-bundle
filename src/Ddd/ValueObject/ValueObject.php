<?php

namespace EnderLab\DddBundle\Ddd\ValueObject;

abstract class ValueObject implements ValueObjectInterface
{
    public function getValue(): mixed
    {
        return $this->value;
    }

    public function __get(string $name): mixed
    {
        return $this->getValue();
    }

    public function isEqualTo(ValueObjectInterface $valueObject): bool
    {
        return $valueObject->value === $this->value;
    }
}
