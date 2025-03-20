<?php

namespace EnderLab\DddBundle\Ddd\ValueObject;

interface ValueObjectInterface
{
    public function isEqualTo(ValueObjectInterface $valueObject): bool;
}
