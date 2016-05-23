<?php

namespace ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ClientRepository")
 * @ORM\Table(name="oauth_client")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Client extends BaseClient
{
    use SoftDeleteableEntity;

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

    /**
     * @var ArrayCollection|User[]
     * @JMS\Groups({"client-users"})
     * @JMS\Type("ArrayCollection<User>")
     * @ORM\ManyToMany(targetEntity="User", inversedBy="clients", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="oauth_client_user", inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")})
     */
    private $users;

    /**
     * @var \DateTime
     * @JMS\Type("DateTime")
     * @JMS\Groups({"client-private"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @JMS\Type("DateTime")
     * @JMS\Groups({"client-private"})
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

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

    /**
     * @return ArrayCollection|User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param UserInterface $user
     */
    public function addUser(UserInterface $user)
    {
        $this->users->add($user);
    }

    /**
     * @param UserInterface $user
     */
    public function removeUser(UserInterface $user)
    {
        $this->users->remove($user);
    }

    public function hasUser(UserInterface $user)
    {
        return $this->users->contains($user);
    }

    /**
     * Sets createdAt.
     *
     * @param  \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Returns createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets updatedAt.
     *
     * @param  \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Returns updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /** @ORM\PrePersist() */
    public function onPrePersist()
    {
        $this->allowedOrigins    = array_values($this->allowedOrigins);
        $this->allowedGrantTypes = array_values($this->allowedGrantTypes);
        $this->redirectUris      = array_values($this->redirectUris);
    }
}
