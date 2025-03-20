<?php

namespace EnderLab\DddBundle\Ddd\Event;

interface DomainEventJsonSerializableInterface
{
    public function __toArray(): array;
    public function __toJson(): string;
}
