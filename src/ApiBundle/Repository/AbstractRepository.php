<?php
namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use JMS\Serializer\Annotation as JMS;

abstract class AbstractRepository extends EntityRepository
{
    protected function createPaginatedQueryBuilder(int $page, int $per_page):QueryBuilder
    {
        return $this->createQueryBuilder($this->_entityName)
            ->setFirstResult($page-1)
            ->setMaxResults($per_page);
    }
}