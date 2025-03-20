<?php

namespace EnderLab\DddBundle\Ddd\Model;

use EnderLab\DddBundle\Ddd\Event\DomainEvent;

abstract class ModelDomainEvent implements ModelDomainEventInterface
{
    private array $domainEvents = [];

    final protected function recordEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    final public function isEmpty(): bool
    {
        return empty($this->domainEvents);
    }

    final public function pullDomainEvents(): array
    {
        $recordedEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $recordedEvents;
    }
}
