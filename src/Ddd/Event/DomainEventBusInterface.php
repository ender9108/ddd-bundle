<?php

declare(strict_types=1);

namespace EnderLab\DddBundle\Ddd\Event;

interface DomainEventBusInterface
{
    public function dispatch(array $domainEvents): void;
}
