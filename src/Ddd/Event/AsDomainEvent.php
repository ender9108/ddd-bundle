<?php

declare(strict_types=1);

namespace EnderLab\DddBundle\Ddd\Event;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
readonly class AsDomainEvent
{
    public function __construct(
        public string $eventListened,
        public int $priority = 255
    ) {

    }
}
