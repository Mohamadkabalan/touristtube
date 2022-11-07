<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HotelPoi
 *
 * @ORM\Table(name="hotel_poi")
 * @ORM\Entity
 */
class HotelPoi
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $hotelId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $distancePoiTypeId;

    /**
     * @var integer
     */
    private $distance;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set hotelId
     *
     * @param integer $hotelId
     *
     * @return HotelPoi
     */
    public function setHotelId($hotelId)
    {
        $this->hotelId = $hotelId;

        return $this;
    }

    /**
     * Get hotelId
     *
     * @return integer
     */
    public function getHotelId()
    {
        return $this->hotelId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return HotelPoi
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set distancePoiTypeId
     *
     * @param integer $distancePoiTypeId
     *
     * @return HotelPoi
     */
    public function setDistancePoiTypeId($distancePoiTypeId)
    {
        $this->distancePoiTypeId = $distancePoiTypeId;

        return $this;
    }

    /**
     * Get distancePoiTypeId
     *
     * @return integer
     */
    public function getDistancePoiTypeId()
    {
        return $this->distancePoiTypeId;
    }

    /**
     * Set distance
     *
     * @param integer $distance
     *
     * @return HotelPoi
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get distance
     *
     * @return integer
     */
    public function getDistance()
    {
        return $this->distance;
    }
}
