<?php
namespace ApiBundle\Doctrine\Type;


class LocationTypeEnum extends Enum
{
    const LOCATION_TYPE_WORLD = 'wrld';
    const LOCATION_TYPE_CONTINENT = 'cntn';
    const LOCATION_TYPE_COUNTRY = 'cntr';
    const LOCATION_TYPE_REGION = 'rgn';
    const LOCATION_TYPE_CITY = 'city';
    const LOCATION_TYPE_DISTRICT = 'dstr';

    protected $name = 'enum_location_type';

    protected $values = [
        self::LOCATION_TYPE_WORLD,
        self::LOCATION_TYPE_CONTINENT,
        self::LOCATION_TYPE_COUNTRY,
        self::LOCATION_TYPE_REGION,
        self::LOCATION_TYPE_CITY,
        self::LOCATION_TYPE_DISTRICT
    ];
}