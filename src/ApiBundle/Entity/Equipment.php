<?php

namespace ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\EquipmentRepository")
 * @ORM\Table(name="xc_equipment")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Equipment
{
    use SoftDeleteableEntity;

    /**
     * @JMS\Type("integer")
     * @JMS\Groups({"equipment", "equipment-id"})
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\Groups({"equipment"})
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @var EquipmentType
     * @JMS\Groups({"equipment-type"})
     * @JMS\Type("ApiBundle\Entity\EquipmentType")
     * @ORM\ManyToOne(targetEntity="EquipmentType", inversedBy="equipment", fetch="EXTRA_LAZY")
     */
    protected $type;

    /**
     * @var ArrayCollection|Activity[]
     * @JMS\Groups({"equipment-activity"})
     * @JMS\Type("ArrayCollection<ApiBundle\Entity\Activity>")
     * @ORM\ManyToMany(targetEntity="Activity", mappedBy="equipment", fetch="EXTRA_LAZY")
     */
    protected $activities;

    /**
     * @var Brand
     * @JMS\Groups({"equipment-brand"})
     * @JMS\Type("ApiBundle\Entity\Brand")
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="equipment", fetch="EXTRA_LAZY")
     */
    protected $brand;

    /**
     * Equipment constructor.
     */
    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }


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
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return EquipmentType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param EquipmentType $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }
}
