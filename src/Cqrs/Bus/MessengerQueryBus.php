<?php

declare(strict_types=1);

namespace EnderLab\DddBundle\Cqrs\Bus;

use EnderLab\DddBundle\Cqrs\Query\QueryBusInterface;
use EnderLab\DddBundle\Cqrs\Query\QueryInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * @template T
     *
     * @param QueryInterface<T> $query
     *
     * @return T
     */
    public function ask(QueryInterface $query): mixed
    {
        try {
            /** @var T */
            return $this->handle($query);
        } catch (HandlerFailedException $e) {
            if ($exception = current($e->getWrappedExceptions())) {
                throw $exception;
            }

            throw $e;
        }
    }
}
