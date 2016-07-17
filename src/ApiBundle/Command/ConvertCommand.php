<?php

namespace ApiBundle\Command;

use ApiBundle\Doctrine\Type\LocationTypeEnum;
use ApiBundle\Entity\Activity;
use ApiBundle\Entity\Brand;
use ApiBundle\Entity\Channel;
use ApiBundle\Entity\Client;
use ApiBundle\Entity\Equipment;
use ApiBundle\Entity\EquipmentType;
use ApiBundle\Entity\Location;
use ApiBundle\Entity\User;
use CrEOF\Spatial\PHP\Types\Geography\Point;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertCommand extends ContainerAwareCommand
{
    /** @var EntityManager */
    protected $em;
    /** @var Connection */
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('api:convert:data')
            ->setDescription('Convert data into new format');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $this->connection = $this->em->getConnection();

        $this->waypointsToLocations();
        $this->brandsToBrands();
        $this->typesToEquipmentType();
        $this->modelsToEquipment();
        $this->flightsToActivities();
        $this->createChannel('main', 2);


        $output->writeln('Completed');
    }

    protected function waypointsToLocations()
    {
        $this->connection->exec('delete from hg05_xc_activity');
        $this->connection->exec('delete from hg05_xc_location');
        $data = $this->connection->fetchAll('SELECT * FROM hg05_leonardo_waypoints');
        foreach ($data as $waypoint) {
            $location = new Location();
            $location->setId($waypoint['ID']);
            $location->setLocalName($waypoint['name']);
            $location->setName($waypoint['intName']);
            $point = new Point($waypoint['lon'], $waypoint['lat']);
            $location->setPoint($point);
            $location->setType(LocationTypeEnum::LOCATION_TYPE_CITY);
            $this->em->persist($location);
        }

        $metadata = $this->em->getClassMetaData(Location::class);
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
        $this->em->flush();
    }

    protected function brandsToBrands()
    {
        $this->connection->exec('delete from hg05_xc_equipment');
        $this->connection->exec('delete from hg05_xc_brand');
        $data = $this->connection->fetchAll('SELECT * FROM hg05_leonardo_brands');
        foreach ($data as $b) {
            $brand = new Brand();
            $brand->setId($b['id']);
            $brand->setName($b['name']);
            $this->em->persist($brand);
        }

        $metadata = $this->em->getClassMetaData(Brand::class);
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
        $this->em->flush();
    }

    protected function typesToEquipmentType()
    {
        $this->connection->exec('delete from hg05_xc_equipment_type');
        $types = [1=>"Параплан",2=>"Дельтаплан",4=>"Жесткокрыл",8=>"Планер",16=>"Парамотор",32=>"Дельталет",64=>"Самолет"];
        foreach ($types as $id => $name) {
            $type = new EquipmentType();
            $type->setId($id);
            $type->setName($name);
            $this->em->persist($type);
        }

        $metadata = $this->em->getClassMetaData(EquipmentType::class);
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
        $this->em->flush();
    }

    protected function modelsToEquipment()
    {
        $data = $this->connection->fetchAll('SELECT * FROM hg05_leonardo_models');
        foreach ($data as $e) {
            $equipment = new Equipment();
            $equipment->setId($e['id']);
            $equipment->setName($e['name']);
            $equipment->setBrand($this->em->getReference(Brand::class, $e['brand']));
            $equipment->setType($this->em->getReference(EquipmentType::class, $e['type']));
            $this->em->persist($equipment);
        }

        $metadata = $this->em->getClassMetaData(Equipment::class);
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
        $this->em->flush();
    }

    protected function flightsToActivities()
    {

        $this->connection->exec('delete from hg05_xc_activity');
        $data = $this->connection->fetchAll('SELECT * FROM hg05_leonardo_flights');
        foreach ($data as $flight) {

            $date = (new \DateTime($flight['DATE']))->setTime(0,0,0);
            $equipment = $this->em->getReference(Equipment::class, $flight['glider']);
            $activity = new Activity();
            $activity->setId($flight['ID'])
                ->setDate($date)
                ->setTimezone($flight['timezone'])
                ->setFilename($flight['filename'])
                ->setFirstPoint((new Point($flight['firstLon'],$flight['firstLat'])))
                ->setLastPoint((new Point($flight['lastLon'],$flight['lastLat'])))
                ->setFirstLocation($flight['takeoffID'] ? $this->em->getReference(Location::class, $flight['takeoffID']) : null)
                ->setLastLocation($flight['landingID'] ? $this->em->getReference(Location::class, $flight['landingID']) : null)
                ->setDuration($flight['DURATION'])
                ->setElevationMax($flight['MAX_ALT'])
                ->setElevationMin($flight['MIN_ALT'])
                ->setElevationMin($flight['MIN_ALT'])
                ->setVarioMax($flight['MAX_VARIO'])
                ->setVarioMin($flight['MIN_VARIO'])
                ->setDistanceLinear($flight['LINEAR_DISTANCE'])
                ->setDistanceMax($flight['MAX_LINEAR_DISTANCE'])
                ->setUser($this->em->getReference(User::class, $flight['userID']))
                ->addEquipment($equipment);
            $this->em->persist($activity);
        }
        $metadata = $this->em->getClassMetaData(Activity::class);
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
        $this->em->flush();
    }

    protected function createChannel($name, $clientId)
    {
        $this->connection->exec('delete from hg05_xc_channel');
        $channel = (new Channel())
            ->setName($name)
            ->setClient($this->em->getReference(Client::class, $clientId));

        $users = $this->em->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $channel->addUser($user);
        }

        $this->em->persist($channel);
        $this->em->flush($channel);

    }
}
