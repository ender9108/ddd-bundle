<?php

namespace EnderLab\DddBundle\Ddd\Listener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use EnderLab\DddBundle\Ddd\Bus\MessengerDomainEventBus;
use EnderLab\DddBundle\Ddd\Model\ModelDomainEvent;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

#[AsDoctrineListener(event: Events::onFlush)]
final readonly class DomainEventListener
{
    public function __construct(
        private MessengerDomainEventBus $eventBus
    ) {
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $unitOfWork = $eventArgs->getObjectManager()->getUnitOfWork();

        foreach ($unitOfWork->getScheduledEntityInsertions() as $entity) {
            $this->publishDomainEvent($entity);
        }

        foreach ($unitOfWork->getScheduledEntityUpdates() as $entity) {
            $this->publishDomainEvent($entity);
        }

        foreach ($unitOfWork->getScheduledEntityDeletions() as $entity) {
            $this->publishDomainEvent($entity);
        }

        foreach ($unitOfWork->getScheduledCollectionDeletions() as $collection) {
            foreach ($collection as $entity) {
                $this->publishDomainEvent($entity);
            }
        }

        foreach ($unitOfWork->getScheduledCollectionUpdates() as $collection) {
            foreach ($collection as $entity) {
                $this->publishDomainEvent($entity);
            }
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    private function publishDomainEvent(object $entity): void
    {
        if ($entity instanceof ModelDomainEvent && !$entity->isEmpty()) {
            $this->eventBus->dispatch($entity->pullDomainEvents());
        }
    }
}
