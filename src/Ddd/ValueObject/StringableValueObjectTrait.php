<?php

namespace EnderLab\DddBundle\Ddd\ValueObject;

use DateTimeInterface;

trait StringableValueObjectTrait
{
    public const DATETIME_FORMAT = 'Y-m-d H:i:s';

    public function __toString(): string
    {
        if ($this->value instanceof DateTimeInterface) {
            return $this->value->format(self::DATETIME_FORMAT);
        }

        return (string) $this->value;
    }
}
