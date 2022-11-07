<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverPoi
 *
 * @ORM\Table(name="discover_poi", indexes={@ORM\Index(name="country", columns={"country"}), @ORM\Index(name="longitude", columns={"longitude"}), @ORM\Index(name="latitude", columns={"latitude"}), @ORM\Index(name="name", columns={"name"}), @ORM\Index(name="country_2", columns={"country"}), @ORM\Index(name="city", columns={"city"}), @ORM\Index(name="zoom_order", columns={"zoom_order"}), @ORM\Index(name="city_id", columns={"city_id"}), @ORM\Index(name="published", columns={"published"}), @ORM\Index(name="last_modified", columns={"last_modified"}), @ORM\Index(name="country_3", columns={"country"})})
 * @ORM\Entity
 */
class DiscoverPoi
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
     * @var string
     *
     * @ORM\Column(name="nearby_includes", type="string", length=10, nullable=false)
     */
    private $nearbyIncludes = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '1';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TTBundle\Entity\DiscoverPoiImages", mappedBy="poi")
     */
    private $images;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set longitude
     *
     * @param float $longitude
     *
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * @return DiscoverPoi
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
     * Set nearbyIncludes
     *
     * @param string $nearbyIncludes
     *
     * @return DiscoverPoi
     */
    public function setNearbyIncludes($nearbyIncludes)
    {
        $this->nearbyIncludes = $nearbyIncludes;

        return $this;
    }

    /**
     * Get nearbyIncludes
     *
     * @return string
     */
    public function getNearbyIncludes()
    {
        return $this->nearbyIncludes;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return DiscoverPoi
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add image
     *
     * @param \TTBundle\Entity\DiscoverPoiImages $image
     *
     * @return DiscoverPoi
     */
    public function addImage(\TTBundle\Entity\DiscoverPoiImages $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \TTBundle\Entity\DiscoverPoiImages $image
     */
    public function removeImage(\TTBundle\Entity\DiscoverPoiImages $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }
}
