<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * AmadeusHotelCity
 *
 * @ORM\Table(name="amadeus_hotel_city")
 * @ORM\Entity
 */
class AmadeusHotelCity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="HotelBundle\Entity\AmadeusHotel", mappedBy="city", cascade={"persist"})
     */
    private $hotels;

    /**
     * @var string
     */
    private $code = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $stateCode;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $countryName = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=true)
     */
    private $cityId = '0';

    /**
     * @var integer
     */
    private $popularity = '1';

    /**
     * @var integer
     */
    private $vendorId;

    public function __construct()
    {
        $this->hotels = new ArrayCollection();
    }

    /**
     * @return Collection|Hotels[]
     */
    public function getHotels()
    {
        return $this->hotels;
    }

    /**
     * Set id
     * 
     * @param integer $id
     * @return AmadeusHotelCity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

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
     * Set code
     *
     * @param string $code
     *
     * @return AmadeusHotelCity
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AmadeusHotelCity
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
     * Set stateCode
     *
     * @param string $stateCode
     *
     * @return AmadeusHotelCity
     */
    public function setStateCode($stateCode)
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    /**
     * Get stateCode
     *
     * @return string
     */
    public function getStateCode()
    {
        return $this->stateCode;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return AmadeusHotelCity
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set countryName
     *
     * @param string $countryName
     *
     * @return AmadeusHotelCity
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * Get countryName
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;
        return $this;
    }

    /**
     * Get cityId
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set popularity
     *
     * @param integer $popularity
     *
     * @return AmadeusHotelCity
     */
    public function setPopularity($popularity)
    {
        $this->popularity = $popularity;

        return $this;
    }

    /**
     * Get popularity
     *
     * @return integer
     */
    public function getPopularity()
    {
        return $this->popularity;
    }

    /**
     * Set vendorId
     *
     * @param integer $vendorId
     *
     */
    public function setVendorId($vendorId)
    {
        $this->vendorId = $vendorId;
        return $this;
    }

    /**
     * Get vendorId
     *
     * @return integer
     */
    public function getVendorId()
    {
        return $this->vendorId;
    }
}
