<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Airport
 *
 * @ORM\Table(name="airport", uniqueConstraints={@ORM\UniqueConstraint(name="airport_code", columns={"airport_code"})}, indexes={@ORM\Index(name="country_abbreviation", columns={"country", "city"}), @ORM\Index(name="world_area_code", columns={"world_area_code", "city"}), @ORM\Index(name="city", columns={"city"}), @ORM\Index(name="country_abbreviation_2", columns={"country"}), @ORM\Index(name="longitude_dec", columns={"longitude"}), @ORM\Index(name="latitude_dec", columns={"latitude"}), @ORM\Index(name="state_code", columns={"state_code"}), @ORM\Index(name="city_2", columns={"city"})})
 */
class Airport
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="airport_code", type="string", length=255, nullable=false)
     */
    private $airportCode;

    /**
     * @var string
     *
     * @ORM\Column(name="world_area_code", type="string", length=3, nullable=false)
     */
    private $worldAreaCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=2, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="runway_length", type="string", length=255, nullable=false)
     */
    private $runwayLength;

    /**
     * @var string
     *
     * @ORM\Column(name="runway_elevation", type="string", length=255, nullable=false)
     */
    private $runwayElevation;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=false)
     */
    private $city;

    /**
     * @var integer
     *
     * @ORM\Column(name="zoom_order", type="integer", nullable=false)
     */
    private $zoomOrder = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="show_on_map", type="boolean", nullable=false)
     */
    private $showOnMap = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="state_code", type="string", length=10, nullable=false)
     */
    private $stateCode;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="gmt_offset", type="string", length=255, nullable=false)
     */
    private $gmtOffset;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=false)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=false)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=false)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var float
     *
     * @ORM\Column(name="stars", type="float", precision=10, scale=0, nullable=false)
     */
    private $stars = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="bigint", nullable=true)
     */
    private $cityId = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=false)
     */
    private $lastModified = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="map_image", type="string", length=255, nullable=false)
     */
    private $mapImage;



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
     * Set name
     *
     * @param string $name
     *
     * @return Airport
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
     * Set airportCode
     *
     * @param string $airportCode
     *
     * @return Airport
     */
    public function setAirportCode($airportCode)
    {
        $this->airportCode = $airportCode;

        return $this;
    }

    /**
     * Get airportCode
     *
     * @return string
     */
    public function getAirportCode()
    {
        return $this->airportCode;
    }

    /**
     * Set worldAreaCode
     *
     * @param string $worldAreaCode
     *
     * @return Airport
     */
    public function setWorldAreaCode($worldAreaCode)
    {
        $this->worldAreaCode = $worldAreaCode;

        return $this;
    }

    /**
     * Get worldAreaCode
     *
     * @return string
     */
    public function getWorldAreaCode()
    {
        return $this->worldAreaCode;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Airport
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
     * Set runwayLength
     *
     * @param string $runwayLength
     *
     * @return Airport
     */
    public function setRunwayLength($runwayLength)
    {
        $this->runwayLength = $runwayLength;

        return $this;
    }

    /**
     * Get runwayLength
     *
     * @return string
     */
    public function getRunwayLength()
    {
        return $this->runwayLength;
    }

    /**
     * Set runwayElevation
     *
     * @param string $runwayElevation
     *
     * @return Airport
     */
    public function setRunwayElevation($runwayElevation)
    {
        $this->runwayElevation = $runwayElevation;

        return $this;
    }

    /**
     * Get runwayElevation
     *
     * @return string
     */
    public function getRunwayElevation()
    {
        return $this->runwayElevation;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Airport
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set zoomOrder
     *
     * @param integer $zoomOrder
     *
     * @return Airport
     */
    public function setZoomOrder($zoomOrder)
    {
        $this->zoomOrder = $zoomOrder;

        return $this;
    }

    /**
     * Get zoomOrder
     *
     * @return integer
     */
    public function getZoomOrder()
    {
        return $this->zoomOrder;
    }

    /**
     * Set showOnMap
     *
     * @param boolean $showOnMap
     *
     * @return Airport
     */
    public function setShowOnMap($showOnMap)
    {
        $this->showOnMap = $showOnMap;

        return $this;
    }

    /**
     * Get showOnMap
     *
     * @return boolean
     */
    public function getShowOnMap()
    {
        return $this->showOnMap;
    }

    /**
     * Set stateCode
     *
     * @param string $stateCode
     *
     * @return Airport
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
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Airport
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Airport
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set gmtOffset
     *
     * @param string $gmtOffset
     *
     * @return Airport
     */
    public function setGmtOffset($gmtOffset)
    {
        $this->gmtOffset = $gmtOffset;

        return $this;
    }

    /**
     * Get gmtOffset
     *
     * @return string
     */
    public function getGmtOffset()
    {
        return $this->gmtOffset;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Airport
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Airport
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Airport
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Airport
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set stars
     *
     * @param float $stars
     *
     * @return Airport
     */
    public function setStars($stars)
    {
        $this->stars = $stars;

        return $this;
    }

    /**
     * Get stars
     *
     * @return float
     */
    public function getStars()
    {
        return $this->stars;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return Airport
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
     * Set published
     *
     * @param boolean $published
     *
     * @return Airport
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set lastModified
     *
     * @param \DateTime $lastModified
     *
     * @return Airport
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    /**
     * Get lastModified
     *
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Airport
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set mapImage
     *
     * @param string $mapImage
     *
     * @return Airport
     */
    public function setMapImage($mapImage)
    {
        $this->mapImage = $mapImage;

        return $this;
    }

    /**
     * Get mapImage
     *
     * @return string
     */
    public function getMapImage()
    {
        return $this->mapImage;
    }
}
