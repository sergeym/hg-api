<?php

namespace ApiBundle\Entity;

use OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_client")
 */
class Client extends BaseClient
{
    /**
     * @JMS\Type("integer")
     * @JMS\Groups({"client-private"})
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @JMS\Type("string")
     * @JMS\Groups({"client-private"})
     * @ORM\Column(name="name",type="string",length=255,nullable=true)
     */
    protected $name;

    /**
     * @JMS\Type("array")
     * @JMS\Groups({"client-private"})
     * @ORM\Column(name="allowed_origins",type="json_array")
     */
    protected $allowedOrigins;

    /**
     * @JMS\Type("string")
     * @JMS\Groups({"client-private"})
     */
    protected $randomId;

    /**
     * @JMS\Type("array")
     * @JMS\Groups({"client-private"})
     */
    protected $allowedGrantTypes;

    /**
     * @JMS\Type("array")
     * @JMS\Groups({"client-private"})
     */
    protected $redirectUris;

    public function __construct()
    {
        $this->allowedOrigins = [];
        parent::__construct();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getAllowedOrigins()
    {
        return $this->allowedOrigins;
    }

    /**
     * @param array $allowedOrigins
     */
    public function setAllowedOrigins($allowedOrigins)
    {
        $this->allowedOrigins = $allowedOrigins;
    }

    /**
     * @param string $origin
     */
    public function addAllowedOrigins($origin)
    {
        $this->allowedOrigins[] = $origin;
    }
}
