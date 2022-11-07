<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverHotels1
 *
 * @ORM\Table(name="discover_hotels1", indexes={@ORM\Index(name="cityName", columns={"cityName"}), @ORM\Index(name="stateName", columns={"stateName"}), @ORM\Index(name="countryCode", columns={"countryCode"}), @ORM\Index(name="countryName", columns={"countryName"}), @ORM\Index(name="cityName_2", columns={"cityName", "countryCode"}), @ORM\Index(name="stateName_2", columns={"stateName", "countryCode"}), @ORM\Index(name="countryCode_2", columns={"countryCode", "countryName"}), @ORM\Index(name="latitude", columns={"latitude", "longitude"})})
 * @ORM\Entity
 */
class DiscoverHotels1
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="hotelName", type="text", nullable=true)
     */
    private $hotelname;

    /**
     * @var integer
     *
     * @ORM\Column(name="stars", type="integer", nullable=true)
     */
    private $stars;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="cityName", type="string", length=255, nullable=true)
     */
    private $cityname;

    /**
     * @var string
     *
     * @ORM\Column(name="stateName", type="string", length=255, nullable=true)
     */
    private $statename;

    /**
     * @var string
     *
     * @ORM\Column(name="countryCode", type="string", length=255, nullable=true)
     */
    private $countrycode;

    /**
     * @var string
     *
     * @ORM\Column(name="countryName", type="string", length=255, nullable=true)
     */
    private $countryname;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="text", nullable=true)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text", nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="tripadvisorUrl", type="text", nullable=true)
     */
    private $tripadvisorurl;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="latlong", type="integer", nullable=true)
     */
    private $latlong;

    /**
     * @var string
     *
     * @ORM\Column(name="propertyType", type="text", nullable=true)
     */
    private $propertytype;

    /**
     * @var string
     *
     * @ORM\Column(name="chainId", type="text", nullable=true)
     */
    private $chainid;

    /**
     * @var string
     *
     * @ORM\Column(name="rooms", type="text", nullable=true)
     */
    private $rooms;

    /**
     * @var string
     *
     * @ORM\Column(name="facilities", type="text", nullable=true)
     */
    private $facilities;

    /**
     * @var string
     *
     * @ORM\Column(name="checkIn", type="text", nullable=true)
     */
    private $checkin;

    /**
     * @var string
     *
     * @ORM\Column(name="checkOut", type="text", nullable=true)
     */
    private $checkout;

    /**
     * @var string
     *
     * @ORM\Column(name="rating", type="text", nullable=true)
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", length=65535, nullable=false)
     */
    private $about;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="general_facilities", type="text", length=65535, nullable=false)
     */
    private $generalFacilities;

    /**
     * @var string
     *
     * @ORM\Column(name="services", type="text", length=65535, nullable=false)
     */
    private $services;

    /**
     * @var integer
     *
     * @ORM\Column(name="zoom_order", type="integer", nullable=false)
     */
    private $zoomOrder;

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
     * @var \DateTime
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=false)
     */
    private $lastModified;

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
     * @ORM\Column(name="h_id", type="integer", nullable=false)
     */
    private $hId = '0';



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
     * Set hotelname
     *
     * @param string $hotelname
     *
     * @return DiscoverHotels1
     */
    public function setHotelname($hotelname)
    {
        $this->hotelname = $hotelname;

        return $this;
    }

    /**
     * Get hotelname
     *
     * @return string
     */
    public function getHotelname()
    {
        return $this->hotelname;
    }

    /**
     * Set stars
     *
     * @param integer $stars
     *
     * @return DiscoverHotels1
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
     * Set price
     *
     * @param integer $price
     *
     * @return DiscoverHotels1
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
     * Set cityname
     *
     * @param string $cityname
     *
     * @return DiscoverHotels1
     */
    public function setCityname($cityname)
    {
        $this->cityname = $cityname;

        return $this;
    }

    /**
     * Get cityname
     *
     * @return string
     */
    public function getCityname()
    {
        return $this->cityname;
    }

    /**
     * Set statename
     *
     * @param string $statename
     *
     * @return DiscoverHotels1
     */
    public function setStatename($statename)
    {
        $this->statename = $statename;

        return $this;
    }

    /**
     * Get statename
     *
     * @return string
     */
    public function getStatename()
    {
        return $this->statename;
    }

    /**
     * Set countrycode
     *
     * @param string $countrycode
     *
     * @return DiscoverHotels1
     */
    public function setCountrycode($countrycode)
    {
        $this->countrycode = $countrycode;

        return $this;
    }

    /**
     * Get countrycode
     *
     * @return string
     */
    public function getCountrycode()
    {
        return $this->countrycode;
    }

    /**
     * Set countryname
     *
     * @param string $countryname
     *
     * @return DiscoverHotels1
     */
    public function setCountryname($countryname)
    {
        $this->countryname = $countryname;

        return $this;
    }

    /**
     * Get countryname
     *
     * @return string
     */
    public function getCountryname()
    {
        return $this->countryname;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return DiscoverHotels1
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
     * Set location
     *
     * @param string $location
     *
     * @return DiscoverHotels1
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
     * Set url
     *
     * @param string $url
     *
     * @return DiscoverHotels1
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set tripadvisorurl
     *
     * @param string $tripadvisorurl
     *
     * @return DiscoverHotels1
     */
    public function setTripadvisorurl($tripadvisorurl)
    {
        $this->tripadvisorurl = $tripadvisorurl;

        return $this;
    }

    /**
     * Get tripadvisorurl
     *
     * @return string
     */
    public function getTripadvisorurl()
    {
        return $this->tripadvisorurl;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return DiscoverHotels1
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
     * Set longitude
     *
     * @param float $longitude
     *
     * @return DiscoverHotels1
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
     * Set latlong
     *
     * @param integer $latlong
     *
     * @return DiscoverHotels1
     */
    public function setLatlong($latlong)
    {
        $this->latlong = $latlong;

        return $this;
    }

    /**
     * Get latlong
     *
     * @return integer
     */
    public function getLatlong()
    {
        return $this->latlong;
    }

    /**
     * Set propertytype
     *
     * @param string $propertytype
     *
     * @return DiscoverHotels1
     */
    public function setPropertytype($propertytype)
    {
        $this->propertytype = $propertytype;

        return $this;
    }

    /**
     * Get propertytype
     *
     * @return string
     */
    public function getPropertytype()
    {
        return $this->propertytype;
    }

    /**
     * Set chainid
     *
     * @param string $chainid
     *
     * @return DiscoverHotels1
     */
    public function setChainid($chainid)
    {
        $this->chainid = $chainid;

        return $this;
    }

    /**
     * Get chainid
     *
     * @return string
     */
    public function getChainid()
    {
        return $this->chainid;
    }

    /**
     * Set rooms
     *
     * @param string $rooms
     *
     * @return DiscoverHotels1
     */
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * Get rooms
     *
     * @return string
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set facilities
     *
     * @param string $facilities
     *
     * @return DiscoverHotels1
     */
    public function setFacilities($facilities)
    {
        $this->facilities = $facilities;

        return $this;
    }

    /**
     * Get facilities
     *
     * @return string
     */
    public function getFacilities()
    {
        return $this->facilities;
    }

    /**
     * Set checkin
     *
     * @param string $checkin
     *
     * @return DiscoverHotels1
     */
    public function setCheckin($checkin)
    {
        $this->checkin = $checkin;

        return $this;
    }

    /**
     * Get checkin
     *
     * @return string
     */
    public function getCheckin()
    {
        return $this->checkin;
    }

    /**
     * Set checkout
     *
     * @param string $checkout
     *
     * @return DiscoverHotels1
     */
    public function setCheckout($checkout)
    {
        $this->checkout = $checkout;

        return $this;
    }

    /**
     * Get checkout
     *
     * @return string
     */
    public function getCheckout()
    {
        return $this->checkout;
    }

    /**
     * Set rating
     *
     * @param string $rating
     *
     * @return DiscoverHotels1
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set about
     *
     * @param string $about
     *
     * @return DiscoverHotels1
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return DiscoverHotels1
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
     * Set generalFacilities
     *
     * @param string $generalFacilities
     *
     * @return DiscoverHotels1
     */
    public function setGeneralFacilities($generalFacilities)
    {
        $this->generalFacilities = $generalFacilities;

        return $this;
    }

    /**
     * Get generalFacilities
     *
     * @return string
     */
    public function getGeneralFacilities()
    {
        return $this->generalFacilities;
    }

    /**
     * Set services
     *
     * @param string $services
     *
     * @return DiscoverHotels1
     */
    public function setServices($services)
    {
        $this->services = $services;

        return $this;
    }

    /**
     * Get services
     *
     * @return string
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Set zoomOrder
     *
     * @param integer $zoomOrder
     *
     * @return DiscoverHotels1
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
     * Set mapImage
     *
     * @param string $mapImage
     *
     * @return DiscoverHotels1
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
     * @return DiscoverHotels1
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
     * Set lastModified
     *
     * @param \DateTime $lastModified
     *
     * @return DiscoverHotels1
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
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return DiscoverHotels1
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
     * @return DiscoverHotels1
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
     * @return DiscoverHotels1
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
     * @return DiscoverHotels1
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
     * @return DiscoverHotels1
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
     * Set hId
     *
     * @param integer $hId
     *
     * @return DiscoverHotels1
     */
    public function setHId($hId)
    {
        $this->hId = $hId;

        return $this;
    }

    /**
     * Get hId
     *
     * @return integer
     */
    public function getHId()
    {
        return $this->hId;
    }
}
