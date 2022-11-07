<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HotelSearchRequest
 *
 * @ORM\Table(name="hotel_search_request")
 * @ORM\Entity(repositoryClass="HotelBundle\Repository\HotelRepository")
 */
class HotelSearchRequest
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=true)
     */
    private $creationDate = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="hotel_city_name", type="string", length=255, nullable=false)
     */
    private $hotelCityName = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="hotel_id", type="integer", nullable=false)
     */
    private $hotelId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="location_id", type="integer", nullable=false)
     */
    private $locationId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="hotel_city_code", type="string", length=255, nullable=true)
     */
    private $hotelCityCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     */
    private $country = '';

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="decimal", precision=10, scale=6, nullable=false)
     */
    private $longitude = '0.000000';

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="decimal", precision=10, scale=6, nullable=false)
     */
    private $latitude = '0.000000';

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_type", type="smallint", nullable=false)
     */
    private $entityType = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="from_date", type="date", nullable=false)
     */
    private $fromDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="to_date", type="date", nullable=false)
     */
    private $toDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="single_rooms", type="smallint", nullable=false)
     */
    private $singleRooms = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="double_rooms", type="smallint", nullable=false)
     */
    private $doubleRooms = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="adult_count", type="smallint", nullable=false)
     */
    private $adultCount = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="child_count", type="smallint", nullable=false)
     */
    private $childCount = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="max_price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $maxPrice = '0.00';

    /**
     * @var string
     *
     * @ORM\Column(name="max_distance", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $maxDistance = '0.00';

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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return HotelSearchRequest
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set hotelCityName
     *
     * @param string $hotelCityName
     *
     * @return HotelSearchRequest
     */
    public function setHotelCityName($hotelCityName)
    {
        $this->hotelCityName = $hotelCityName;

        return $this;
    }

    /**
     * Get hotelCityName
     *
     * @return string
     */
    public function getHotelCityName()
    {
        return $this->hotelCityName;
    }

    /**
     * Set hotelId
     *
     * @param integer $hotelId
     *
     * @return HotelSearchRequest
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
     * Set locationId
     *
     * @param integer $locationId
     *
     * @return HotelSearchRequest
     */
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * Get locationId
     *
     * @return integer
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * Set hotelCityCode
     *
     * @param string $hotelCityCode
     *
     * @return HotelSearchRequest
     */
    public function setHotelCityCode($hotelCityCode)
    {
        $this->hotelCityCode = $hotelCityCode;

        return $this;
    }

    /**
     * Get hotelCityCode
     *
     * @return string
     */
    public function getHotelCityCode()
    {
        return $this->hotelCityCode;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return HotelSearchRequest
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return HotelSearchRequest
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return HotelSearchRequest
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set entityType
     *
     * @param integer $entityType
     *
     * @return HotelSearchRequest
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * Get entityType
     *
     * @return integer
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Set fromDate
     *
     * @param \DateTime $fromDate
     *
     * @return HotelSearchRequest
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Get fromDate
     *
     * @return \DateTime
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param \DateTime $toDate
     *
     * @return HotelSearchRequest
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Get toDate
     *
     * @return \DateTime
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Set singleRooms
     *
     * @param integer $singleRooms
     *
     * @return HotelSearchRequest
     */
    public function setSingleRooms($singleRooms)
    {
        $this->singleRooms = $singleRooms;

        return $this;
    }

    /**
     * Get singleRooms
     *
     * @return integer
     */
    public function getSingleRooms()
    {
        return $this->singleRooms;
    }

    /**
     * Set doubleRooms
     *
     * @param integer $doubleRooms
     *
     * @return HotelSearchRequest
     */
    public function setDoubleRooms($doubleRooms)
    {
        $this->doubleRooms = $doubleRooms;

        return $this;
    }

    /**
     * Get doubleRooms
     *
     * @return integer
     */
    public function getDoubleRooms()
    {
        return $this->doubleRooms;
    }

    /**
     * Set adultCount
     *
     * @param integer $adultCount
     *
     * @return HotelSearchRequest
     */
    public function setAdultCount($adultCount)
    {
        $this->adultCount = $adultCount;

        return $this;
    }

    /**
     * Get adultCount
     *
     * @return integer
     */
    public function getAdultCount()
    {
        return $this->adultCount;
    }

    /**
     * Set childCount
     *
     * @param integer $childCount
     *
     * @return HotelSearchRequest
     */
    public function setChildCount($childCount)
    {
        $this->childCount = $childCount;

        return $this;
    }

    /**
     * Get childCount
     *
     * @return integer
     */
    public function getChildCount()
    {
        return $this->childCount;
    }

    /**
     * Set maxPrice
     *
     * @param string $maxPrice
     *
     * @return HotelSearchRequest
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * Get maxPrice
     *
     * @return string
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * Set maxDistance
     *
     * @param string $maxDistance
     *
     * @return HotelSearchRequest
     */
    public function setMaxDistance($maxDistance)
    {
        $this->maxDistance = $maxDistance;

        return $this;
    }

    /**
     * Get maxDistance
     *
     * @return string
     */
    public function getMaxDistance()
    {
        return $this->maxDistance;
    }
}