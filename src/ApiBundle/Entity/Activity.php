<?php

namespace ApiBundle\Entity;

use CrEOF\Spatial\PHP\Types\Geography\Point;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ActivityRepository")
 * @ORM\Table(name="xc_activity", indexes={
 *     @ORM\Index(name="idx_activity_date", columns={"date"})
 * })
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Activity
{
    use SoftDeleteableEntity, TimestampableEntity;

    /**
     * @JMS\Type("integer")
     * @JMS\Groups({"activity"})
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     * @JMS\Type("DateTime<'Y-m-d','UTC'>")
     * @JMS\Groups({"activity"})
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    protected $date;

    /**
     * @var Point
     * @JMS\Type("string")
     * @JMS\Groups({"activity"})
     * @ORM\Column(name="first_point", type="point", nullable=false)
     */
    protected $firstPoint;

    /**
     * @var Point
     * @JMS\Type("string")
     * @JMS\Groups({"activity"})
     * @ORM\Column(name="last_point", type="point", nullable=false)
     */
    protected $lastPoint;

    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\Groups({"activity"})
     * @ORM\Column(name="duration", type="integer", nullable=false)
     */
    protected $duration;

    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\Groups({"activity"})
     * @ORM\Column(name="elevation_max", type="integer", nullable=true)
     */
    protected $elevationMax;

    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\Groups({"activity"})
     * @ORM\Column(name="elevation_min", type="integer", nullable=true)
     */
    protected $elevationMin;

    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\Groups({"activity"})
     * @ORM\Column(name="elevation_first_point", type="integer", nullable=true)
     */
    protected $elevationFirstPoint;

    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\Groups({"activity"})
     * @ORM\Column(name="elevation_last_point", type="integer", nullable=true)
     */
    protected $elevationLastPoint;

    /**
     * @var float
     * @JMS\Type("float")
     * @ORM\Column(name="vario_min", type="decimal", precision=4, scale=2, nullable=true)
     */
    protected $varioMin;

    /**
     * @var float
     * @JMS\Type("float")
     * @JMS\Groups({"activity"})
     * @ORM\Column(name="vario_max", type="decimal", precision=4, scale=2, nullable=true)
     */
    protected $varioMax;

    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\Groups({"activity"})
     * @ORM\Column(name="distance_linear", type="integer", nullable=true)
     */
    protected $distanceLinear;

    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\Groups({"activity"})
     * @ORM\Column(name="distance_max", type="integer", nullable=true)
     */
    protected $distanceMax;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\Groups({"activity"})
     * @ORM\Column(name="filename", type="string", length=15, nullable=true)
     */
    protected $filename;

    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\Groups({"activity"})
     * @ORM\Column(name="timezone", type="smallint", nullable=true)
     */
    protected $timezone;

    /**
     * @var User
     * @JMS\Groups({"activity-user"})
     * @JMS\Type(User::class)
     * @ORM\ManyToOne(targetEntity="User", inversedBy="activities", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false)
     */
    protected $user;

    /**
     * @var Location
     * @JMS\Type("ApiBundle\Entity\Location")
     * @JMS\Groups({"activity-first-location"})
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $firstLocation;

    /**
     * @var Location
     * @JMS\Type("ApiBundle\Entity\Location")
     * @JMS\Groups({"activity-last-location"})
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $lastLocation;

    /**
     * @var ArrayCollection|Equipment[]
     * @JMS\Type("ArrayCollection<ApiBundle\Entity\Equipment>")
     * @JMS\Groups({"activity-equipment"})
     * @ORM\ManyToMany(targetEntity="Equipment", inversedBy="activities", fetch="EXTRA_LAZY")
     */
    protected $equipment;

    /**
     * Activity constructor.
     */
    public function __construct()
    {
        $this->equipment = new ArrayCollection();
    }


    /**
     * @return Equipment
     */
    public function getEquipment()
    {
        return $this->equipment;
    }

    /**
     * @param Equipment $equipment
     * @return Activity
     */
    public function setEquipment($equipment)
    {
        $this->equipment = $equipment;

        return $this;
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
     * @return Activity
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Activity
     */
    public function setDate(\DateTime $date=null):Activity
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Point
     */
    public function getFirstPoint()
    {
        return $this->firstPoint;
    }

    /**
     * @param Point $firstPoint
     * @return Activity
     */
    public function setFirstPoint($firstPoint):Activity
    {
        $this->firstPoint = $firstPoint;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastPoint()
    {
        return $this->lastPoint;
    }

    /**
     * @param mixed $lastPoint
     * @return Activity
     */
    public function setLastPoint($lastPoint):Activity
    {
        $this->lastPoint = $lastPoint;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     * @return Activity
     */
    public function setDuration($duration):Activity
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return int
     */
    public function getElevationMax()
    {
        return $this->elevationMax;
    }

    /**
     * @param int $elevationMax
     * @return Activity
     */
    public function setElevationMax($elevationMax):Activity
    {
        $this->elevationMax = $elevationMax;

        return $this;
    }

    /**
     * @return int
     */
    public function getElevationMin()
    {
        return $this->elevationMin;
    }

    /**
     * @param int $elevationMin
     * @return Activity
     */
    public function setElevationMin($elevationMin):Activity
    {
        $this->elevationMin = $elevationMin;

        return $this;
    }

    /**
     * @return int
     */
    public function getElevationFirstPoint()
    {
        return $this->elevationFirstPoint;
    }

    /**
     * @param int $elevationFirstPoint
     * @return Activity
     */
    public function setElevationFirstPoint($elevationFirstPoint):Activity
    {
        $this->elevationFirstPoint = $elevationFirstPoint;

        return $this;
    }

    /**
     * @return int
     */
    public function getElevationLastPoint()
    {
        return $this->elevationLastPoint;
    }

    /**
     * @param int $elevationLastPoint
     * @return Activity
     */
    public function setElevationLastPoint($elevationLastPoint):Activity
    {
        $this->elevationLastPoint = $elevationLastPoint;

        return $this;
    }

    /**
     * @return float
     */
    public function getVarioMin()
    {
        return $this->varioMin;
    }

    /**
     * @param float $varioMin
     * @return Activity
     */
    public function setVarioMin($varioMin):Activity
    {
        $this->varioMin = $varioMin;

        return $this;
    }

    /**
     * @return float
     */
    public function getVarioMax()
    {
        return $this->varioMax;
    }

    /**
     * @param float $varioMax
     * @return Activity
     */
    public function setVarioMax($varioMax):Activity
    {
        $this->varioMax = $varioMax;

        return $this;
    }

    /**
     * @return int
     */
    public function getDistanceLinear()
    {
        return $this->distanceLinear;
    }

    /**
     * @param int $distanceLinear
     * @return Activity
     */
    public function setDistanceLinear($distanceLinear):Activity
    {
        $this->distanceLinear = $distanceLinear;

        return $this;
    }

    /**
     * @return int
     */
    public function getDistanceMax()
    {
        return $this->distanceMax;
    }

    /**
     * @param int $distanceMax
     * @return Activity
     */
    public function setDistanceMax($distanceMax):Activity
    {
        $this->distanceMax = $distanceMax;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return Activity
     */
    public function setFilename($filename):Activity
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param int $timezone
     * @return Activity
     */
    public function setTimezone($timezone):Activity
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser():User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Activity
     */
    public function setUser(User $user):Activity
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Location
     */
    public function getFirstLocation()
    {
        return $this->firstLocation;
    }

    /**
     * @param Location $firstLocation
     * @return Activity
     */
    public function setFirstLocation(Location $firstLocation=null):Activity
    {
        $this->firstLocation = $firstLocation;

        return $this;
    }

    /**
     * @return Location
     */
    public function getLastLocation()
    {
        return $this->lastLocation;
    }

    /**
     * @param Location $lastLocation
     * @return Activity
     */
    public function setLastLocation(Location $lastLocation=null):Activity
    {
        $this->lastLocation = $lastLocation;

        return $this;
    }

    /**
     * @param Equipment $equipment
     * @return $this
     */
    public function addEquipment(Equipment $equipment):Activity
    {
        if (!$this->equipment->contains($equipment)) {
            $this->equipment->add($equipment);
        }
        return $this;
    }

}
