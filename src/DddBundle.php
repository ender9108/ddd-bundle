<?php

namespace EnderLab\DddBundle;

use EnderLab\DddBundle\Ddd\Event\AsDomainEvent;
use EnderLab\DddBundle\Ddd\Event\AsDomainEventHandler;
use EnderLab\DddBundle\Cqrs\Query\AsQueryHandler;
use EnderLab\DddBundle\Cqrs\Command\AsCommandHandler;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class DddBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');

        $builder->registerAttributeForAutoconfiguration(AsCommandHandler::class, static function (ChildDefinition $definition): void {
            $definition
                ->addTag('messenger.message_handler', ['bus' => 'command.bus'])
                ->setLazy(true)
            ;
        });

        $builder->registerAttributeForAutoconfiguration(AsQueryHandler::class, static function (ChildDefinition $definition): void {
            $definition
                ->addTag('messenger.message_handler', ['bus' => 'query.bus'])
                ->setLazy(true)
            ;
        });

        $builder->registerAttributeForAutoconfiguration(AsDomainEventHandler::class, static function (ChildDefinition $definition): void {
            $definition
                ->addTag('messenger.message_handler', ['bus' => 'domain_events.bus'])
                ->setLazy(true)
            ;
        });

        $builder->registerAttributeForAutoconfiguration(AsDomainEvent::class, static function (ChildDefinition $definition, AsDomainEvent $attribute, \ReflectionClass $reflector): void {
            $definition
                ->addTag('domain.event.listener', ['priority' => $attribute->priority])
                ->setLazy(true)
            ;
        });
    }
}
