<?php

namespace ApiBundle;

use ApiBundle\Doctrine\Extensions\TablePrefix;
use ApiBundle\Doctrine\Type\LocationTypeEnum;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use ApiBundle\Doctrine\Type\UserSexEnum;
use Doctrine\DBAL\Types\Type;

class ApiBundle extends Bundle
{
    public function boot()
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');

        // Adding missing types to doctrine
        Type::addType('enum_user_sex', UserSexEnum::class);
        Type::addType('enum_location_type', LocationTypeEnum::class);

        $tablePrefix = new TablePrefix($this->container->getParameter('database_prefix'));
        $entityManager->getEventManager()->addEventListener(Events::loadClassMetadata, $tablePrefix);
    }
}
