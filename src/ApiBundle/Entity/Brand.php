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
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\EquipmentManufacturerRepository")
 * @ORM\Table(name="xc_brand")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Brand
{
    use SoftDeleteableEntity;

    /**
     * @JMS\Type("integer")
     * @JMS\Groups({"brand"})
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @JMS\Type("date")
     * @JMS\Groups({"brand"})
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @var Equipment[]
     * @JMS\Groups({"brand-equipment"})
     * @JMS\Type("ArrayCollection<Equipment>")
     * @ORM\ManyToMany(targetEntity="Equipment", mappedBy="brand")
     */
    protected $equipment;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


}
