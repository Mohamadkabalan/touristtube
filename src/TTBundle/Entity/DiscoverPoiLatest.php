<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverPoiLatest
 *
 * @ORM\Table(name="discover_poi_latest", indexes={@ORM\Index(name="country", columns={"country"}), @ORM\Index(name="longitude", columns={"longitude"}), @ORM\Index(name="latitude", columns={"latitude"}), @ORM\Index(name="name", columns={"name"}), @ORM\Index(name="country_2", columns={"country"}), @ORM\Index(name="city", columns={"city"}), @ORM\Index(name="zoom_order", columns={"zoom_order"}), @ORM\Index(name="city_id", columns={"city_id"}), @ORM\Index(name="published", columns={"published"}), @ORM\Index(name="last_modified", columns={"last_modified"}), @ORM\Index(name="country_3", columns={"country"})})
 * @ORM\Entity
 */
class DiscoverPoiLatest
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
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="stars", type="float", precision=10, scale=0, nullable=false)
     */
    private $stars = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=2, nullable=false)
     */
    private $country;

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
    private $zoomOrder;

    /**
     * @var boolean
     *
     * @ORM\Column(name="show_on_map", type="boolean", nullable=false)
     */
    private $showOnMap = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="cat", type="string", length=255, nullable=false)
     */
    private $cat;

    /**
     * @var string
     *
     * @ORM\Column(name="sub_cat", type="string", length=255, nullable=false)
     */
    private $subCat;

    /**
     * @var string
     *
     * @ORM\Column(name="map_image", type="string", length=255, nullable=false)
     */
    private $mapImage;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     */
    private $cityId;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=255, nullable=false)
     */
    private $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=false)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=false)
     */
    private $website;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer", nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

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
     * @ORM\Column(name="address", type="string", length=255, nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="from_source", type="string", length=20, nullable=false)
     */
    private $fromSource = 'old';

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="State", type="string", length=50, nullable=false)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="state_code", type="string", length=10, nullable=false)
     */
    private $stateCode;

    /**
     * @var string
     *
     * @ORM\Column(name="n_city_local", type="string", length=50, nullable=false)
     */
    private $nCityLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="n_city_admin", type="string", length=50, nullable=false)
     */
    private $nCityAdmin;



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
     * Set longitude
     *
     * @param float $longitude
     *
     * @return DiscoverPoiLatest
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
     * @return DiscoverPoiLatest
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
     * Set name
     *
     * @param string $name
     *
     * @return DiscoverPoiLatest
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
     * Set stars
     *
     * @param float $stars
     *
     * @return DiscoverPoiLatest
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
     * Set country
     *
     * @param string $country
     *
     * @return DiscoverPoiLatest
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
     * Set city
     *
     * @param string $city
     *
     * @return DiscoverPoiLatest
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
     * @return DiscoverPoiLatest
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
     * @return DiscoverPoiLatest
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
     * Set cat
     *
     * @param string $cat
     *
     * @return DiscoverPoiLatest
     */
    public function setCat($cat)
    {
        $this->cat = $cat;

        return $this;
    }

    /**
     * Get cat
     *
     * @return string
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * Set subCat
     *
     * @param string $subCat
     *
     * @return DiscoverPoiLatest
     */
    public function setSubCat($subCat)
    {
        $this->subCat = $subCat;

        return $this;
    }

    /**
     * Get subCat
     *
     * @return string
     */
    public function getSubCat()
    {
        return $this->subCat;
    }

    /**
     * Set mapImage
     *
     * @param string $mapImage
     *
     * @return DiscoverPoiLatest
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
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return DiscoverPoiLatest
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
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return DiscoverPoiLatest
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return DiscoverPoiLatest
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
     * @return DiscoverPoiLatest
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
     * Set email
     *
     * @param string $email
     *
     * @return DiscoverPoiLatest
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
     * Set website
     *
     * @param string $website
     *
     * @return DiscoverPoiLatest
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
     * Set price
     *
     * @param integer $price
     *
     * @return DiscoverPoiLatest
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return DiscoverPoiLatest
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
     * Set published
     *
     * @param boolean $published
     *
     * @return DiscoverPoiLatest
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
     * @return DiscoverPoiLatest
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
     * Set address
     *
     * @param string $address
     *
     * @return DiscoverPoiLatest
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
     * Set fromSource
     *
     * @param string $fromSource
     *
     * @return DiscoverPoiLatest
     */
    public function setFromSource($fromSource)
    {
        $this->fromSource = $fromSource;

        return $this;
    }

    /**
     * Get fromSource
     *
     * @return string
     */
    public function getFromSource()
    {
        return $this->fromSource;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return DiscoverPoiLatest
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return DiscoverPoiLatest
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set stateCode
     *
     * @param string $stateCode
     *
     * @return DiscoverPoiLatest
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
     * Set nCityLocal
     *
     * @param string $nCityLocal
     *
     * @return DiscoverPoiLatest
     */
    public function setNCityLocal($nCityLocal)
    {
        $this->nCityLocal = $nCityLocal;

        return $this;
    }

    /**
     * Get nCityLocal
     *
     * @return string
     */
    public function getNCityLocal()
    {
        return $this->nCityLocal;
    }

    /**
     * Set nCityAdmin
     *
     * @param string $nCityAdmin
     *
     * @return DiscoverPoiLatest
     */
    public function setNCityAdmin($nCityAdmin)
    {
        $this->nCityAdmin = $nCityAdmin;

        return $this;
    }

    /**
     * Get nCityAdmin
     *
     * @return string
     */
    public function getNCityAdmin()
    {
        return $this->nCityAdmin;
    }
}
