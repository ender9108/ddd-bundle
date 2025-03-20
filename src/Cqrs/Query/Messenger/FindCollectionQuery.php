<?php

namespace EnderLab\DddBundle\Cqrs\Query\Messenger;

use EnderLab\DddBundle\Cqrs\Query\QueryInterface;

class FindCollectionQuery implements QueryInterface
{
    public function __construct(
        public string $className,
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
