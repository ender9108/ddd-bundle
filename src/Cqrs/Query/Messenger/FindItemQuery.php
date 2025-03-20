<?php

namespace EnderLab\DddBundle\Cqrs\Query\Messenger;

use EnderLab\DddBundle\Cqrs\Query\QueryInterface;

class FindItemQuery implements QueryInterface
{
    public function __construct(
        public int $id,
        public string $className,
    ) {
    }
}
