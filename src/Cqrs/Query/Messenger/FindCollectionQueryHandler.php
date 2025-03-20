<?php

namespace EnderLab\DddBundle\Cqrs\Query\Messenger;

use Doctrine\ORM\EntityManagerInterface;
use EnderLab\DddBundle\Ddd\Repository\RepositoryInterface;
use EnderLab\DddBundle\Cqrs\Query\AsQueryHandler;

#[AsQueryHandler]
class FindCollectionQueryHandler
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(FindCollectionQuery $query): RepositoryInterface
    {
        $repository = $this->em->getRepository($query->className);

        if (
            null !== $query->page &&
            null !== $query->itemsPerPage &&
            method_exists($repository, 'withPagination')
        ) {
            $repository = $repository->withPagination($query->page, $query->itemsPerPage);
        }

        return $repository;
    }
}
