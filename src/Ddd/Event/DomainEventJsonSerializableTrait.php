<?php

namespace EnderLab\DddBundle\Ddd\Event;

use ReflectionClass;

trait DomainEventJsonSerializableTrait
{
    public function __toArray(): array
    {
        $reflection = new ReflectionClass($this);
        $result = [];

        foreach ($reflection->getProperties() as $property) {
            $result[$property->getName()] = $this->{$property->getName()};
        }

        return $result;
    }

    public function __toJson(): string
    {
        return json_encode($this->__toArray());
    }
}
