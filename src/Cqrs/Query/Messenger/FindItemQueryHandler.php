<?php

namespace EnderLab\DddBundle\Cqrs\Query\Messenger;

use EnderLab\DddBundle\Ddd\Exception\MissingModelException;
use Doctrine\ORM\EntityManagerInterface;
use EnderLab\DddBundle\Cqrs\Query\AsQueryHandler;

#[AsQueryHandler]
class FindItemQueryHandler
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(FindItemQuery $query): object
    {
        $entity = $this->em->getRepository($query->className)->find($query->id);

        if (!$entity) {
            throw new MissingModelException($query->id, $query->className);
        }

        return $entity;
    }
}
