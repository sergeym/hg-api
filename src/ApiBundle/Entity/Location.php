<?php
namespace ApiBundle\Entity;

use CrEOF\Spatial\PHP\Types\Geography\Point;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\LocationRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="xc_location")
 */
class Location
{
    /**
     * @var integer
     *
     * @JMS\Groups({"location-id", "location"})
     * @JMS\Type("integer")
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @var integer
     *
     * @JMS\Groups({"location-id", "location"})
     * @JMS\Type("integer")
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    protected $parentId;

    /**
     * @var string
     *
     * @JMS\Groups({"location"})
     * @JMS\Type("string")
     *
     * @ORM\Column(name="location", type="point", nullable=false)
     */
    protected $point;

    /**
     * @var string
     *
     * @JMS\Groups({"location"})
     * @JMS\Type("string")
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @JMS\Groups({"location"})
     * @JMS\Type("string")
     *
     * @ORM\Column(name="local_name", type="string", nullable=true, length=255)
     */
    protected $localName;

    /**
     * @JMS\Groups({"location"})
     * @JMS\Type("string")
     *
     * @ORM\Column(name="type", nullable=false, type="enum_location_type")
     */
    protected $type;

    /**
     * @var string
     *
     * @JMS\Groups({"location"})
     * @JMS\Type("string")
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;


    /**
     * @var Location
     *
     * @JMS\Groups({"location-parent"})
     * @JMS\Type("Location")
     *
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="children", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @JMS\Groups({"location-children"})
     * @JMS\Type("ArrayCollection<Location>")
     *
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Location", mappedBy="parent", fetch="EXTRA_LAZY")
     */
    protected $children;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * @return string
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * @param Point $point
     */
    public function setPoint($point)
    {
        $this->point = $point;
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

    /**
     * @return string
     */
    public function getLocalName()
    {
        return $this->localName;
    }

    /**
     * @param string $localName
     */
    public function setLocalName($localName)
    {
        $this->localName = $localName;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return Location
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Location $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

}