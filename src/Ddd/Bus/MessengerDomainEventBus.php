<?php

declare(strict_types=1);

namespace EnderLab\DddBundle\Ddd\Bus;

use EnderLab\DddBundle\Ddd\Event\AsDomainEvent;
use EnderLab\DddBundle\Ddd\Event\DomainEventBusInterface;
use EnderLab\DddBundle\Ddd\Event\DomainEventJsonSerializableInterface;
use EnderLab\ToolsBundle\Service\MappingArgs;
use Psr\Cache\InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final readonly class MessengerDomainEventBus implements DomainEventBusInterface
{
    public function __construct(
        private MessageBusInterface $bus,
        private CacheInterface $cache,
        private ParameterBagInterface $parameters,
        private ValidatorInterface $validator,
        #[AutowireIterator('domain.event.listener')]
        private iterable $listeners = []
    ) {

    }

    /**
     * @throws ExceptionInterface|InvalidArgumentException
     */
    public function dispatch(array $domainEvents): void
    {
        foreach ($domainEvents as $currentEvent) {
            if ($currentEvent instanceof DomainEventJsonSerializableInterface) {
                $this->validator->validate($currentEvent);

                $events = $this->getMappingEvents($currentEvent->getEventType());

                foreach ($events as $event) {
                    $eventMessage = new $event($currentEvent->__toArray());
                    $this->validator->validate($eventMessage);

                    $this->bus->dispatch(
                        (new Envelope($eventMessage))->with(new DispatchAfterCurrentBusStamp())
                    );
                }
            }
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function getMappingEvents(string $eventType): array
    {
        $key = md5($eventType);

        return $this->cache->get($key, function (ItemInterface $item) use ($eventType): array {
            $item->expiresAfter($this->parameters->get('cache_timeout'));
            $mapping = [];

            foreach ($this->listeners as $listener) {
                $reflectionClass = new ReflectionClass($listener);
                $attributes = $reflectionClass->getAttributes(name: AsDomainEvent::class);

                foreach ($attributes as $attribute) {
                    $arguments = $attribute->getArguments();

                    if (count($arguments) === 0) {
                        continue;
                    }

                    if ($arguments['eventListened'] === $eventType) {
                        $mapping[] = get_class($listener);
                    }
                }
            }

            return $mapping;
        });
    }
}
