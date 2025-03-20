<?php

declare(strict_types=1);

namespace EnderLab\DddBundle\Ddd\Event;

use Attribute;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[Attribute(Attribute::TARGET_CLASS)]
class AsDomainEventHandler extends AsMessageHandler
{

}
