<?php

namespace HotelBundle\Model;

class HotelPOI
{
    private $id;
    private $hotel;
    private $name;
    private $distancePoiTypeId;
    private $distancePoiTypeName;
    private $distance;

    public function __construct()
    {
        $this->hotel = null;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Hotel
     *
     * @return HotelInfo
     */
    public function getHotel()
    {
        return $this->hotel;
    }

    public function setHotel(HotelInfo $hotel)
    {
        $this->hotel = $hotel;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getDistancePoiTypeId()
    {
        return $this->distancePoiTypeId;
    }

    public function setDistancePoiTypeId($distancePoiTypeId)
    {
        $this->distancePoiTypeId = $distancePoiTypeId;
        return $this;
    }

    public function getDistancePoiTypeName()
    {
        return $this->distancePoiTypeName;
    }

    public function setDistancePoiTypeName($distancePoiTypeName)
    {
        $this->distancePoiTypeName = $distancePoiTypeName;
        return $this;
    }

    public function getDistance()
    {
        return $this->distance;
    }

    public function setDistance($distance)
    {
        $this->distance = $distance;
        return $this;
    }
}
