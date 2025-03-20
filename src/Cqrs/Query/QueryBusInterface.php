<?php

declare(strict_types=1);

namespace EnderLab\DddBundle\Cqrs\Query;

interface QueryBusInterface
{
    /**
     * @template T
     *
     * @return T
     */
    public function ask(QueryInterface $query): mixed;
}
