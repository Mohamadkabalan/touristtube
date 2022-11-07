<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsHotel
 *
 * @ORM\Table(name="cms_hotel")
 * @ORM\Entity(repositoryClass="HotelBundle\Repository\HRSRepository")
 */
class CmsHotel
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $street;

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
    private $city;

    /**
     * @var integer
     */
    private $cityId;

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
    private $iso3CountryCode;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var boolean
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
     * @ORM\Column(name="map_image", type="string", length=255, nullable=false)
     */
    private $mapImage;

    /**
     * @var integer
     */
    private $popularity = '1';

    /**
     * @var integer
     */
    private $distanceFromDowntown;

    /**
     * @var string
     */
    private $downtown;

    /**
     * @var integer
     */
    private $distanceFromAirport;

    /**
     * @var string
     */
    private $airport;

    /**
     * @var integer
     */
    private $distanceFromTrainStation;

    /**
     * @var string
     */
    private $trainStation;

    /**
     * @var boolean
     */
    private $has360 = 0;

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
     * @return CmsHotel
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
     * Set address
     *
     * @param string $address
     *
     * @return CmsHotel
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return CmsHotel
     */
    public function setStreet($street)
    {
        $this->street = $street;

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
     * Set district
     *
     * @param string $district
     *
     * @return CmsHotel
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return CmsHotel
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
     * Set city
     *
     * @param string $city
     *
     * @return CmsHotel
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
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return CmsHotel
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
     * Set latitude
     *
     * @param string $latitude
     *
     * @return CmsHotel
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
     * @return CmsHotel
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
     * Set iso3CountryCode
     *
     * @param string $iso3CountryCode
     *
     * @return CmsHotel
     */
    public function setIso3CountryCode($iso3CountryCode)
    {
        $this->iso3CountryCode = $iso3CountryCode;

        return $this;
    }

    /**
     * Get iso3CountryCode
     *
     * @return string
     */
    public function getIso3CountryCode()
    {
        return $this->iso3CountryCode;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return CmsHotel
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
     * Set stars
     *
     * @param boolean $stars
     *
     * @return CmsHotel
     */
    public function setStars($stars)
    {
        $this->stars = $stars;

        return $this;
    }

    /**
     * Get stars
     *
     * @return boolean
     */
    public function getStars()
    {
        return $this->stars;
    }

    /**
     * Set Logo
     *
     * @param string $logo
     *
     * @return CmsHotel
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
     * Set mapImage
     *
     * @param string $mapImage
     *
     * @return CmsHotel
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
     * Set popularity
     *
     * @param integer $popularity
     *
     * @return CmsHotel
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
     * Set distanceFromDowntown
     *
     * @param integer $distanceFromDowntown
     *
     * @return CmsHotel
     */
    public function setDistanceFromDowntown($distanceFromDowntown)
    {
        $this->distanceFromDowntown = $distanceFromDowntown;

        return $this;
    }

    /**
     * Get distanceFromDowntown
     *
     * @return integer
     */
    public function getDistanceFromDowntown()
    {
        return $this->distanceFromDowntown;
    }

    /**
     * Set downtown
     *
     * @param string $downtown
     *
     * @return CmsHotel
     */
    public function setDowntown($downtown)
    {
        $this->downtown = $downtown;

        return $this;
    }

    /**
     * Get downtown
     *
     * @return string
     */
    public function getDowntown()
    {
        return $this->downtown;
    }

    /**
     * Set distanceFromAirport
     *
     * @param integer $distanceFromAirport
     *
     * @return CmsHotel
     */
    public function setDistanceFromAirport($distanceFromAirport)
    {
        $this->distanceFromAirport = $distanceFromAirport;

        return $this;
    }

    /**
     * Get distanceFromAirport
     *
     * @return integer
     */
    public function getDistanceFromAirport()
    {
        return $this->distanceFromAirport;
    }

    /**
     * Set airport
     *
     * @param string $airport
     *
     * @return CmsHotel
     */
    public function setAirport($airport)
    {
        $this->airport = $airport;

        return $this;
    }

    /**
     * Get airport
     *
     * @return string
     */
    public function getAirport()
    {
        return $this->airport;
    }

    /**
     * Set distanceFromTrainStation
     *
     * @param integer $distanceFromTrainStation
     *
     * @return CmsHotel
     */
    public function setDistanceFromTrainStation($distanceFromTrainStation)
    {
        $this->distanceFromTrainStation = $distanceFromTrainStation;

        return $this;
    }

    /**
     * Get distanceFromTrainStation
     *
     * @return integer
     */
    public function getDistanceFromTrainStation()
    {
        return $this->distanceFromTrainStation;
    }

    /**
     * Set trainStation
     *
     * @param string $trainStation
     *
     * @return CmsHotel
     */
    public function setTrainStation($trainStation)
    {
        $this->trainStation = $trainStation;

        return $this;
    }

    /**
     * Get trainStation
     *
     * @return string
     */
    public function getTrainStation()
    {
        return $this->trainStation;
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
    public function getHas360()
    {
        return $this->has360;
    }
}
