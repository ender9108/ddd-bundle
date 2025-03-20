<?php

declare(strict_types=1);

namespace EnderLab\DddBundle\Ddd\Event;

/**
 * @template T
 */
interface DomainEventInterface
{
    public function getEventType(): ?string;
}
