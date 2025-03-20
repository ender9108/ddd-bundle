<?php

declare(strict_types=1);

namespace EnderLab\DddBundle\Ddd\Event;

use DateTimeImmutable;

abstract class DomainEvent implements DomainEventInterface, DomainEventJsonSerializableInterface
{
    private const string DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct(private ?string $occurredOn = null)
    {
        $this->occurredOn = $occurredOn ?? (new DateTimeImmutable())->format(self::DATE_FORMAT);
    }

    public function getOccurredOn(): string
    {
        return $this->occurredOn;
    }

    public function __toJson(): string
    {
        return json_encode($this->__toArray());
    }

    abstract public function getEventType(): string;
}
