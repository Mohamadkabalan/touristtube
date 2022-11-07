<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverHotels
 *
 * @ORM\Table(name="discover_hotels",  indexes={@ORM\Index(name="country_sub_id", columns={"country_sub_id"}), @ORM\Index(name="hotel_name", columns={"hotelName"}), @ORM\Index(name="chain_id", columns={"chain_id"}), @ORM\Index(name="property_type_id", columns={"propertyType"}), @ORM\Index(name="city_id", columns={"city_id"})})
 * @ORM\Entity
 */
class DiscoverHotels
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="hotelName", type="string", length=255, nullable=false)
     */
    private $hotelname;


    /**
     * @var integer
     *
     * @ORM\Column(name="country_sub_id", type="integer", nullable=false)
     */
    private $countrySubId;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="address_short", type="string", length=255, nullable=false)
     */
    private $addressShort;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=false)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

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
     * @var float
     *
     * @ORM\Column(name="stars", type="float", precision=10, scale=0, nullable=false)
     */
    private $stars;

    /**
     * @var string
     *
     * @ORM\Column(name="star_self_rated", type="string", length=255, nullable=false)
     */
    private $starSelfRated;

    /**
     * @var string
     *
     * @ORM\Column(name="rooms", type="string", length=255, nullable=false)
     */
    private $rooms;

    /**
     * @var string
     *
     * @ORM\Column(name="local_currency_code", type="string", length=255, nullable=false)
     */
    private $localCurrencyCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer", nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="price_from", type="string", length=255, nullable=false)
     */
    private $priceFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="check_in", type="string", length=255, nullable=false)
     */
    private $checkIn;

    /**
     * @var string
     *
     * @ORM\Column(name="check_out", type="string", length=255, nullable=false)
     */
    private $checkOut;

    /**
     * @var integer
     *
     * @ORM\Column(name="propertyType", type="integer", nullable=false)
     */
    private $propertytype;

    /**
     * @var integer
     *
     * @ORM\Column(name="chain_id", type="integer", nullable=false)
     */
    private $chainId;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", precision=10, scale=0, nullable=false)
     */
    private $rating = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="rating_overall_text", type="string", length=255, nullable=false)
     */
    private $ratingOverallText;

    /**
     * @var string
     *
     * @ORM\Column(name="rating_cleanliness", type="string", length=255, nullable=false)
     */
    private $ratingCleanliness;

    /**
     * @var string
     *
     * @ORM\Column(name="rating_dining", type="string", length=255, nullable=false)
     */
    private $ratingDining;

    /**
     * @var string
     *
     * @ORM\Column(name="rating_facilities", type="string", length=255, nullable=false)
     */
    private $ratingFacilities;

    /**
     * @var string
     *
     * @ORM\Column(name="rating_location", type="string", length=255, nullable=false)
     */
    private $ratingLocation;

    /**
     * @var string
     *
     * @ORM\Column(name="rating_rooms", type="string", length=255, nullable=false)
     */
    private $ratingRooms;

    /**
     * @var string
     *
     * @ORM\Column(name="rating_service", type="string", length=255, nullable=false)
     */
    private $ratingService;

    /**
     * @var string
     *
     * @ORM\Column(name="rating_points", type="string", length=255, nullable=false)
     */
    private $ratingPoints;

    /**
     * @var string
     *
     * @ORM\Column(name="reviews_count", type="string", length=255, nullable=false)
     */
    private $reviewsCount;

    /**
     * @var string
     *
     * @ORM\Column(name="reviews_summary_positive", type="text", length=65535, nullable=false)
     */
    private $reviewsSummaryPositive;

    /**
     * @var string
     *
     * @ORM\Column(name="reviews_summary_negative", type="text", length=65535, nullable=false)
     */
    private $reviewsSummaryNegative;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=16777215, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="FAQ", type="text", length=16777215, nullable=false)
     */
    private $faq;

    /**
     * @var integer
     *
     * @ORM\Column(name="images_count", type="integer", nullable=false)
     */
    private $imagesCount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=false)
     */
    private $lastModified = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", length=65535, nullable=false)
     */
    private $about;

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
    private $zoomOrder = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     */
    private $cityId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="cityName", type="string", length=255, nullable=false)
     */
    private $cityname;

    /**
     * @var string
     *
     * @ORM\Column(name="countryCode", type="string", length=255, nullable=false)
     */
    private $countrycode;

    /**
     * @var string
     *
     * @ORM\Column(name="stateName", type="string", length=255, nullable=false)
     */
    private $statename;

    /**
     * @var string
     *
     * @ORM\Column(name="map_image", type="string", length=255, nullable=false)
     */
    private $mapImage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="avg_rating", type="string", length=255, nullable=false)
     */
    private $avgRating;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_votes", type="integer", nullable=false)
     */
    private $nbVotes;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotel_id", type="integer", nullable=false)
     */
    private $hotelId;



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
     * Set title
     *
     * @param string $title
     *
     * @return DiscoverHotels
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set hotelname
     *
     * @param string $hotelname
     *
     * @return DiscoverHotels
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
     * Set countrySubId
     *
     * @param integer $countrySubId
     *
     * @return DiscoverHotels
     */
    public function setCountrySubId($countrySubId)
    {
        $this->countrySubId = $countrySubId;

        return $this;
    }

    /**
     * Get countrySubId
     *
     * @return integer
     */
    public function getCountrySubId()
    {
        return $this->countrySubId;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return DiscoverHotels
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
     * Set addressShort
     *
     * @param string $addressShort
     *
     * @return DiscoverHotels
     */
    public function setAddressShort($addressShort)
    {
        $this->addressShort = $addressShort;

        return $this;
    }

    /**
     * Get addressShort
     *
     * @return string
     */
    public function getAddressShort()
    {
        return $this->addressShort;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return DiscoverHotels
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
     * Set email
     *
     * @param string $email
     *
     * @return DiscoverHotels
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
     * Set url
     *
     * @param string $url
     *
     * @return DiscoverHotels
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
     * Set phone
     *
     * @param string $phone
     *
     * @return DiscoverHotels
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
     * @return DiscoverHotels
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
     * Set longitude
     *
     * @param float $longitude
     *
     * @return DiscoverHotels
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
     * @return DiscoverHotels
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
     * Set stars
     *
     * @param float $stars
     *
     * @return DiscoverHotels
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
     * Set starSelfRated
     *
     * @param string $starSelfRated
     *
     * @return DiscoverHotels
     */
    public function setStarSelfRated($starSelfRated)
    {
        $this->starSelfRated = $starSelfRated;

        return $this;
    }

    /**
     * Get starSelfRated
     *
     * @return string
     */
    public function getStarSelfRated()
    {
        return $this->starSelfRated;
    }

    /**
     * Set rooms
     *
     * @param string $rooms
     *
     * @return DiscoverHotels
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
     * Set localCurrencyCode
     *
     * @param string $localCurrencyCode
     *
     * @return DiscoverHotels
     */
    public function setLocalCurrencyCode($localCurrencyCode)
    {
        $this->localCurrencyCode = $localCurrencyCode;

        return $this;
    }

    /**
     * Get localCurrencyCode
     *
     * @return string
     */
    public function getLocalCurrencyCode()
    {
        return $this->localCurrencyCode;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return DiscoverHotels
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
     * Set priceFrom
     *
     * @param string $priceFrom
     *
     * @return DiscoverHotels
     */
    public function setPriceFrom($priceFrom)
    {
        $this->priceFrom = $priceFrom;

        return $this;
    }

    /**
     * Get priceFrom
     *
     * @return string
     */
    public function getPriceFrom()
    {
        return $this->priceFrom;
    }

    /**
     * Set checkIn
     *
     * @param string $checkIn
     *
     * @return DiscoverHotels
     */
    public function setCheckIn($checkIn)
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    /**
     * Get checkIn
     *
     * @return string
     */
    public function getCheckIn()
    {
        return $this->checkIn;
    }

    /**
     * Set checkOut
     *
     * @param string $checkOut
     *
     * @return DiscoverHotels
     */
    public function setCheckOut($checkOut)
    {
        $this->checkOut = $checkOut;

        return $this;
    }

    /**
     * Get checkOut
     *
     * @return string
     */
    public function getCheckOut()
    {
        return $this->checkOut;
    }

    /**
     * Set propertytype
     *
     * @param integer $propertytype
     *
     * @return DiscoverHotels
     */
    public function setPropertytype($propertytype)
    {
        $this->propertytype = $propertytype;

        return $this;
    }

    /**
     * Get propertytype
     *
     * @return integer
     */
    public function getPropertytype()
    {
        return $this->propertytype;
    }

    /**
     * Set chainId
     *
     * @param integer $chainId
     *
     * @return DiscoverHotels
     */
    public function setChainId($chainId)
    {
        $this->chainId = $chainId;

        return $this;
    }

    /**
     * Get chainId
     *
     * @return integer
     */
    public function getChainId()
    {
        return $this->chainId;
    }

    /**
     * Set rating
     *
     * @param float $rating
     *
     * @return DiscoverHotels
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set ratingOverallText
     *
     * @param string $ratingOverallText
     *
     * @return DiscoverHotels
     */
    public function setRatingOverallText($ratingOverallText)
    {
        $this->ratingOverallText = $ratingOverallText;

        return $this;
    }

    /**
     * Get ratingOverallText
     *
     * @return string
     */
    public function getRatingOverallText()
    {
        return $this->ratingOverallText;
    }

    /**
     * Set ratingCleanliness
     *
     * @param string $ratingCleanliness
     *
     * @return DiscoverHotels
     */
    public function setRatingCleanliness($ratingCleanliness)
    {
        $this->ratingCleanliness = $ratingCleanliness;

        return $this;
    }

    /**
     * Get ratingCleanliness
     *
     * @return string
     */
    public function getRatingCleanliness()
    {
        return $this->ratingCleanliness;
    }

    /**
     * Set ratingDining
     *
     * @param string $ratingDining
     *
     * @return DiscoverHotels
     */
    public function setRatingDining($ratingDining)
    {
        $this->ratingDining = $ratingDining;

        return $this;
    }

    /**
     * Get ratingDining
     *
     * @return string
     */
    public function getRatingDining()
    {
        return $this->ratingDining;
    }

    /**
     * Set ratingFacilities
     *
     * @param string $ratingFacilities
     *
     * @return DiscoverHotels
     */
    public function setRatingFacilities($ratingFacilities)
    {
        $this->ratingFacilities = $ratingFacilities;

        return $this;
    }

    /**
     * Get ratingFacilities
     *
     * @return string
     */
    public function getRatingFacilities()
    {
        return $this->ratingFacilities;
    }

    /**
     * Set ratingLocation
     *
     * @param string $ratingLocation
     *
     * @return DiscoverHotels
     */
    public function setRatingLocation($ratingLocation)
    {
        $this->ratingLocation = $ratingLocation;

        return $this;
    }

    /**
     * Get ratingLocation
     *
     * @return string
     */
    public function getRatingLocation()
    {
        return $this->ratingLocation;
    }

    /**
     * Set ratingRooms
     *
     * @param string $ratingRooms
     *
     * @return DiscoverHotels
     */
    public function setRatingRooms($ratingRooms)
    {
        $this->ratingRooms = $ratingRooms;

        return $this;
    }

    /**
     * Get ratingRooms
     *
     * @return string
     */
    public function getRatingRooms()
    {
        return $this->ratingRooms;
    }

    /**
     * Set ratingService
     *
     * @param string $ratingService
     *
     * @return DiscoverHotels
     */
    public function setRatingService($ratingService)
    {
        $this->ratingService = $ratingService;

        return $this;
    }

    /**
     * Get ratingService
     *
     * @return string
     */
    public function getRatingService()
    {
        return $this->ratingService;
    }

    /**
     * Set ratingPoints
     *
     * @param string $ratingPoints
     *
     * @return DiscoverHotels
     */
    public function setRatingPoints($ratingPoints)
    {
        $this->ratingPoints = $ratingPoints;

        return $this;
    }

    /**
     * Get ratingPoints
     *
     * @return string
     */
    public function getRatingPoints()
    {
        return $this->ratingPoints;
    }

    /**
     * Set reviewsCount
     *
     * @param string $reviewsCount
     *
     * @return DiscoverHotels
     */
    public function setReviewsCount($reviewsCount)
    {
        $this->reviewsCount = $reviewsCount;

        return $this;
    }

    /**
     * Get reviewsCount
     *
     * @return string
     */
    public function getReviewsCount()
    {
        return $this->reviewsCount;
    }

    /**
     * Set reviewsSummaryPositive
     *
     * @param string $reviewsSummaryPositive
     *
     * @return DiscoverHotels
     */
    public function setReviewsSummaryPositive($reviewsSummaryPositive)
    {
        $this->reviewsSummaryPositive = $reviewsSummaryPositive;

        return $this;
    }

    /**
     * Get reviewsSummaryPositive
     *
     * @return string
     */
    public function getReviewsSummaryPositive()
    {
        return $this->reviewsSummaryPositive;
    }

    /**
     * Set reviewsSummaryNegative
     *
     * @param string $reviewsSummaryNegative
     *
     * @return DiscoverHotels
     */
    public function setReviewsSummaryNegative($reviewsSummaryNegative)
    {
        $this->reviewsSummaryNegative = $reviewsSummaryNegative;

        return $this;
    }

    /**
     * Get reviewsSummaryNegative
     *
     * @return string
     */
    public function getReviewsSummaryNegative()
    {
        return $this->reviewsSummaryNegative;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return DiscoverHotels
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
     * Set faq
     *
     * @param string $faq
     *
     * @return DiscoverHotels
     */
    public function setFaq($faq)
    {
        $this->faq = $faq;

        return $this;
    }

    /**
     * Get faq
     *
     * @return string
     */
    public function getFaq()
    {
        return $this->faq;
    }

    /**
     * Set imagesCount
     *
     * @param integer $imagesCount
     *
     * @return DiscoverHotels
     */
    public function setImagesCount($imagesCount)
    {
        $this->imagesCount = $imagesCount;

        return $this;
    }

    /**
     * Get imagesCount
     *
     * @return integer
     */
    public function getImagesCount()
    {
        return $this->imagesCount;
    }

    /**
     * Set lastModified
     *
     * @param \DateTime $lastModified
     *
     * @return DiscoverHotels
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
     * Set about
     *
     * @param string $about
     *
     * @return DiscoverHotels
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
     * Set generalFacilities
     *
     * @param string $generalFacilities
     *
     * @return DiscoverHotels
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
     * @return DiscoverHotels
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
     * @return DiscoverHotels
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
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return DiscoverHotels
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
     * Set cityname
     *
     * @param string $cityname
     *
     * @return DiscoverHotels
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
     * Set countrycode
     *
     * @param string $countrycode
     *
     * @return DiscoverHotels
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
     * Set statename
     *
     * @param string $statename
     *
     * @return DiscoverHotels
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
     * Set mapImage
     *
     * @param string $mapImage
     *
     * @return DiscoverHotels
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
     * Set published
     *
     * @param boolean $published
     *
     * @return DiscoverHotels
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
     * Set avgRating
     *
     * @param string $avgRating
     *
     * @return DiscoverHotels
     */
    public function setAvgRating($avgRating)
    {
        $this->avgRating = $avgRating;

        return $this;
    }

    /**
     * Get avgRating
     *
     * @return string
     */
    public function getAvgRating()
    {
        return $this->avgRating;
    }

    /**
     * Set nbVotes
     *
     * @param integer $nbVotes
     *
     * @return DiscoverHotels
     */
    public function setNbVotes($nbVotes)
    {
        $this->nbVotes = $nbVotes;

        return $this;
    }

    /**
     * Get nbVotes
     *
     * @return integer
     */
    public function getNbVotes()
    {
        return $this->nbVotes;
    }

    /**
     * Set hotelId
     *
     * @param integer $hotelId
     *
     * @return DiscoverHotels
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
}
