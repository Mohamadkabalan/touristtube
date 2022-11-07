<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * AmadeusHotel
 *
 * @ORM\Table(name="amadeus_hotel")
 * @ORM\Entity
 */
class AmadeusHotel
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="HotelBundle\Entity\AmadeusHotelCity", inversedBy="hotels")
     * @ORM\JoinColumn(name="amadeus_city_id", referencedColumnName="id")
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="HotelBundle\Entity\AmadeusHotelSource", mappedBy="hotel", cascade={"persist"})
     */
    private $sources;

    /**
     * @var integer
     */
    private $dupePoolId;

    /**
     * @var integer
     */
    private $amadeusCityId;

    /**
     * @var integer
     */
    private $cityId;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var string
     */
    private $address1;

    /**
     * @var string
     */
    private $address2;

    /**
     * @var string
     */
    private $latitude = '0.000000';

    /**
     * @var string
     */
    private $longitude = '0.000000';

    /**
     * @var string
     */
    private $district;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $fax;

    /**
     * @var integer
     */
    private $stars = '0';

    /**
     * @var string
     *
     */
    private $logo;

    /**
     * @var string
     *
     */
    private $mapImage;

    /**
     * @var integer
     */
    private $selfRating = '1';

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $transportation;

    /**
     * @var integer
     */
    private $popularity = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="smallint", nullable=false)
     */
    private $published = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_updated", type="datetime", nullable=false)
     */
    private $lastUpdated;

    /**
     * @var boolean
     *
     * @ORM\Column(name="images_downloaded", type="boolean", nullable=false)
     */
    private $imagesDownloaded = false;

    /**
     * @var string
     */
    private $description;
    
    /**
     * @var boolean
     */
    private $has360 = 0;

    public function __construct()
    {
        $this->sources = new ArrayCollection();
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
     * Get city
     *
     * @return AmadeusHotelCity $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city
     *
     * @param AmadeusHotelCity $city
     */
    public function setCity(AmadeusHotelCity $city)
    {
        $this->city = $city;
    }

    /**
     * @return Collection|Sources[]
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * Set $dupePoolId
     *
     * @param string $dupePoolId
     *
     * @return AmadeusHotel
     */
    public function setDupePoolId($dupePoolId)
    {
        $this->dupePoolId = $dupePoolId;

        return $this;
    }

    /**
     * Get dupePoolId
     *
     * @return string
     */
    public function getDupePoolId()
    {
        return $this->dupePoolId;
    }

    /**
     * Set amadeusCityId
     *
     * @param integer $amadeusCityId
     *
     * @return AmadeusHotel
     */
    public function setAmadeusCityId($amadeusCityId)
    {
        $this->amadeusCityId = $amadeusCityId;

        return $this;
    }

    /**
     * Get amadeusCityId
     *
     * @return integer
     */
    public function getAmadeusCityId()
    {
        return $this->amadeusCityId;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return AmadeusHotel
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
     * Set $propertyName
     *
     * @param string $propertyName
     *
     * @return AmadeusHotel
     */
    public function setPropertyName($propertyName)
    {
        $this->propertyName = $propertyName;

        return $this;
    }

    /**
     * Get propertyName
     *
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * Set address1
     *
     * @param string $address1
     *
     * @return AmadeusHotel
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return AmadeusHotel
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return AmadeusHotel
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
     * Set longitude
     *
     * @param string $longitude
     *
     * @return AmadeusHotel
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
     * Set district
     *
     * @param string $district
     *
     * @return AmadeusHotel
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return AmadeusHotel
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return AmadeusHotel
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return AmadeusHotel
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
     * Set stars
     *
     * @param integer $stars
     *
     * @return AmadeusHotel
     */
    public function setStars($stars)
    {
        $this->stars = $stars;

        return $this;
    }

    /**
     * Get stars
     *
     * @return integer
     */
    public function getStars()
    {
        return $this->stars;
    }

    /**
     * Set mapImage
     *
     * @param string $mapImage
     *
     * @return AmadeusHotel
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

    /**
     * Set Logo
     *
     * @param string $logo
     *
     * @return AmadeusHotel
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get Logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set selfRating
     *
     * @param string $selfRating
     *
     * @return AmadeusHotel
     */
    public function setSelfRating($selfRating)
    {
        $this->selfRating = $selfRating;

        return $this;
    }

    /**
     * Get selfRating
     *
     * @return string
     */
    public function getSelfRating()
    {
        return $this->selfRating;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return AmadeusHotel
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set transportation
     *
     * @param string $transportation
     *
     * @return AmadeusHotel
     */
    public function setTransportation($transportation)
    {
        $this->transportation = $transportation;

        return $this;
    }

    /**
     * Get transportation
     *
     * @return string
     */
    public function getTransportation()
    {
        return $this->transportation;
    }

    /**
     * Set popularity
     *
     * @param integer $popularity
     *
     * @return AmadeusHotel
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
     * Set published
     *
     * @param integer $published
     *
     * @return AmadeusHotel
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return integer
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set lastUpdated
     *
     * @param datetime $lastUpdated
     *
     * @return AmadeusHotel
     */
    public function setLastUpdated(\DateTime $lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    /**
     * Get lastUpdated
     *
     * @return datetime
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * Set imagesDownloaded
     *
     * @param boolean $imagesDownloaded
     *
     * @return AmadeusHotel
     */
    public function setImagesDownloaded($imagesDownloaded)
    {
        $this->imagesDownloaded = $imagesDownloaded;

        return $this;
    }

    /**
     * Get imagesDownloaded
     *
     * @return boolean
     */
    public function getImagesDownloaded()
    {
        return $this->imagesDownloaded;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CmsHotel
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set has360
     *
     * @param boolean $has360
     *
     * @return CmsHotel
     */
    public function setHas360($has360)
    {
        $this->has360 = $has360;

        return $this;
    }

    /**
     * Get has360
     *
     * @return boolean
     */
    public function has360()
    {
        return $this->has360;
    }
}
