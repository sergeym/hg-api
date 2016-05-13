<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Model\ClientInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(
 *     name="oauth_authorized_client",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="user_id_client_id", columns={"user_id", "client_id"}),
 *     }
 * )
 * @ORM\Entity
 */
class AuthorizedClient
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", nullable=false, referencedColumnName="user_id")
     */
    protected $user;

    /**
     * @var string
     * @ORM\Column(name="scope", type="string", nullable=true)
     */
    protected $scope;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param $scope
     * @return bool
     */
    public function isScopeValid($scope)
    {
        $clientScopes = explode(' ', $this->scope);
        $targetScope = explode(' ', $scope);

        $result = array_intersect($clientScopes, $targetScope);

        return count($result) == count($targetScope);
    }

    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

}

