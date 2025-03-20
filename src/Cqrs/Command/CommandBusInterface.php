<?php

declare(strict_types=1);

namespace EnderLab\DddBundle\Cqrs\Command;

interface CommandBusInterface
{
    /**
     * @template T
     *
     * @param CommandInterface<T> $command
     *
     * @return T
     */
    public function dispatch(CommandInterface $command): mixed;
}
