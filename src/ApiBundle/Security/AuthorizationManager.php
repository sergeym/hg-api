<?php

namespace ApiBundle\Security;

use ApiBundle\Entity\AuthorizedClient;
use ApiBundle\Entity\User;
use FOS\OAuthServerBundle\Entity\Client;
use FOS\OAuthServerBundle\Model\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\RememberMe\RememberMeServicesInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;

/**
 * Class AuthorizationManager
 * @package ApiBundle\Security
 */
class AuthorizationManager
{
    /** @var \Doctrine\ORM\EntityRepository */
    protected $repository;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
        $this->repository = $container->get('doctrine.orm.default_entity_manager')->getRepository(AuthorizedClient::class);
    }

    /**
     * @param ClientInterface $client
     * @param UserInterface $user
     * @return AuthorizedClient
     */
    public function findByClientAndUser(ClientInterface $client, UserInterface $user)
    {
        return $this->repository->findOneBy([
            'user' => $user,
            'client' => $client
        ]);
    }

    public function create(ClientInterface $client, UserInterface $user, $scope)
    {
        $authorizedClient = new AuthorizedClient();
        $authorizedClient->setUser($user);
        $authorizedClient->setClient($client);
        $authorizedClient->setScope($scope);
        $this->entityManager->persist($authorizedClient);
        $this->entityManager->flush($authorizedClient);

        return $authorizedClient;
    }

    /**
     * @param AuthorizedClient $authorizedClient
     * @param string $scope
     */
    public function updateScope(AuthorizedClient $authorizedClient, $scope)
    {
        $targetScope = $scope ? explode(' ', $scope) : [];
        $currentScope = $authorizedClient->getScope() ? explode(' ', $authorizedClient->getScope()) : [];

        if (count(array_intersect($currentScope, $targetScope)) < count($targetScope)) {
            $merged = array_merge($targetScope, $currentScope);
            $merged = array_unique($merged);
            $authorizedClient->setScope(join(' ', $merged));
            $this->entityManager->persist($authorizedClient);
            $this->entityManager->flush($authorizedClient);
        }
    }


}