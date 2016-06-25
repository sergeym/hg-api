<?php

namespace ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ChannelRepository")
 * @ORM\Table(name="xc_channel")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Channel
{
    use SoftDeleteableEntity;

    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\Groups({"channel"})
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\Groups({"channel"})
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @var ArrayCollection|User[]
     * @JMS\Groups({"equipment"})
     * @JMS\Type("ArrayCollecion<User>")
     * @ORM\ManyToMany(targetEntity="User", inversedBy="channels", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(
     *     joinColumns={@ORM\JoinColumn(name="channel_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id", onDelete="CASCADE")}
     * )
     */
    protected $users;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * Channel constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->activities = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getId():int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Channel
     */
    public function setName(string $name):Channel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Activity[]|ArrayCollection
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * @param Activity $activity
     * @return Channel
     */
    public function addActivity(Activity $activity):Channel
    {
        if ($this->activities->contains($activity)) {
            $this->activities->add($activity);
        }
        return $this;
    }

    /**
     * @return User[]|ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return Channel
     */
    public function addUser(User $user):Channel
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
        return $this;
    }

    /**
     * @return Client
     */
    public function getClient():Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return Channel
     */
    public function setClient($client):Channel
    {
        $this->client = $client;

        return $this;
    }
}
