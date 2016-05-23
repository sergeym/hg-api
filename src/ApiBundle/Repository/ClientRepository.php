<?php
namespace ApiBundle\Repository;

use ApiBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools\Pagination\Paginator;
use JMS\Serializer\Annotation as JMS;

class ClientRepository extends AbstractRepository
{

    public function getPaginatedByUser(User $user, int $page, int $per_page, bool $fetchJoinCollection = null):Paginator
    {
        $queryBuilder = $this->createPaginatedQueryBuilder($page, $per_page)
            ->innerJoin($this->_entityName.'.users', 'user')
            ->andWhere('user = :user')
            ->setParameter('user', $user);

        return new Paginator($queryBuilder->getQuery(), $fetchJoinCollection);
    }
}