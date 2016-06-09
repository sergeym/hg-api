<?php
namespace ApiBundle\Repository;

use ApiBundle\Entity\Client;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ActivityRepository extends AbstractRepository
{
    /**
     * @param Client $client
     * @param int $page
     * @param int $per_page
     * @param bool|null $fetchJoinCollection
     * @return Paginator
     */
    public function getPaginatedByClient(Client $client, int $page, int $per_page, bool $fetchJoinCollection = null):Paginator
    {
        $queryBuilder = $this->createPaginatedQueryBuilder($page, $per_page)
            ->innerJoin($this->_entityName.'.user', 'user')
            ->innerJoin('user.channels', 'channel')
            ->andWhere('channel.client = :client')
            ->orderBy($this->_entityName.'.date', 'DESC')
            ->setParameter('client', $client);

        return new Paginator($queryBuilder->getQuery(), $fetchJoinCollection);
    }
}