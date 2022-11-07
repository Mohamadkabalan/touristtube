<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HotelSearchResponse
 *
 * @ORM\Table(name="hotel_search_response")
 * @ORM\Entity(repositoryClass="HotelBundle\Repository\HotelRepository")
 */
class HotelSearchResponse
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
     * @var integer
     *
     * @ORM\Column(name="hotel_search_request_id", type="integer", nullable=false)
     */
    private $hotelSearchRequestId;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotel_id", type="integer", nullable=false)
     */
    private $hotelId;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotel_key", type="integer", nullable=true)
     */
    private $hotelKey;

    /**
     * @var string
     *
     * @ORM\Column(name="hotel_code", type="string", length=255, nullable=true)
     */
    private $hotelCode;

    /**
     * @var string
     *
     * @ORM\Column(name="hotel_name", type="string", length=255, nullable=false)
     */
    private $hotelName;

    /**
     * @var string
     *
     * @ORM\Column(name="hotel_name_clean_title", type="string", length=255, nullable=false)
     */
    private $hotelNameURL;

    /**
     * @var integer
     *
     * @ORM\Column(name="category", type="smallint", nullable=false)
     */
    private $category = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="district", type="string", length=255, nullable=true)
     */
    private $district;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=3, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_currency", type="string", length=3, nullable=false)
     */
    private $isoCurrency;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $price = '0.00';

    /**
     * @var string
     *
     * @ORM\Column(name="avg_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $avgPrice = '0.00';

    /**
     * @var integer
     *
     * @ORM\Column(name="distance", type="integer", nullable=false)
     */
    private $distance = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="distances", type="text", length=65535, nullable=true)
     */
    private $distances;

    /**
     * @var string
     *
     * @ORM\Column(name="map_image_url", type="string", length=255, nullable=false)
     */
    private $mapImageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="main_image", type="string", length=255, nullable=false)
     */
    private $mainImage;

    /**
     * @var string
     *
     * @ORM\Column(name="main_image_mobile", type="string", length=255, nullable=false)
     */
    private $mainImageMobile;

    /**
     * @var integer
     *
     * @ORM\Column(name="cancelable", type="smallint", nullable=false)
     */
    private $cancelable = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="breakfast", type="smallint", nullable=false)
     */
    private $breakfast = '0';

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
     * Set hotelSearchRequestId
     *
     * @param integer $hotelSearchRequestId
     *
     * @return HotelSearchResponse
     */
    public function setHotelSearchRequestId($hotelSearchRequestId)
    {
        $this->hotelSearchRequestId = $hotelSearchRequestId;

        return $this;
    }

    /**
     * Get hotelSearchRequestId
     *
     * @return integer
     */
    public function getHotelSearchRequestId()
    {
        return $this->hotelSearchRequestId;
    }

    /**
     * Set hotelId
     *
     * @param integer $hotelId
     *
     * @return HotelSearchResponse
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
     * Set hotelKey
     *
     * @param integer $hotelKey
     *
     * @return HotelSearchResponse
     */
    public function setHotelKey($hotelKey)
    {
        $this->hotelKey = $hotelKey;

        return $this;
    }

    /**
     * Get hotelKey
     *
     * @return integer
     */
    public function getHotelKey()
    {
        return $this->hotelKey;
    }

    /**
     * Set hotelCode
     *
     * @param string $hotelCode
     *
     * @return HotelSearchResponse
     */
    public function setHotelCode($hotelCode)
    {
        $this->hotelCode = $hotelCode;

        return $this;
    }

    /**
     * Get hotelCode
     *
     * @return string
     */
    public function getHotelCode()
    {
        return $this->hotelCode;
    }

    /**
     * Set hotelName
     *
     * @param string $hotelName
     *
     * @return HotelSearchResponse
     */
    public function setHotelName($hotelName)
    {
        $this->hotelName = $hotelName;

        return $this;
    }

    /**
     * Get hotelName
     *
     * @return string
     */
    public function getHotelName()
    {
        return $this->hotelName;
    }

    /**
     * Set hotelNameURL
     *
     * @param string $hotelNameURL
     *
     * @return HotelSearchResponse
     */
    public function setHotelNameURL($hotelNameURL)
    {
        $this->hotelNameURL = $hotelNameURL;

        return $this;
    }

    /**
     * Get hotelNameURL
     *
     * @return string
     */
    public function getHotelNameURL()
    {
        return $this->hotelNameURL;
    }

    /**
     * Set category
     *
     * @param integer $category
     *
     * @return HotelSearchResponse
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
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
     * Get district
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return HotelSearchResponse
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
     * Set country
     *
     * @param string $country
     *
     * @return HotelSearchResponse
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
     * Set isoCurrency
     *
     * @param string $isoCurrency
     *
     * @return HotelSearchResponse
     */
    public function setIsoCurrency($isoCurrency)
    {
        $this->isoCurrency = $isoCurrency;

        return $this;
    }

    /**
     * Get isoCurrency
     *
     * @return string
     */
    public function getIsoCurrency()
    {
        return $this->isoCurrency;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return HotelSearchResponse
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set avgPrice
     *
     * @param string $avgPrice
     *
     * @return HotelSearchResponse
     */
    public function setAvgPrice($avgPrice)
    {
        $this->avgPrice = $avgPrice;

        return $this;
    }

    /**
     * Get avgPrice
     *
     * @return string
     */
    public function getAvgPrice()
    {
        return $this->avgPrice;
    }

    /**
     * Set distance
     *
     * @param integer $distance
     *
     * @return HotelSearchResponse
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

    /**
     * Set distances
     *
     * @param string $distances
     *
     * @return HotelSearchResponse
     */
    public function setDistances($distances)
    {
        $this->distances = $distances;

        return $this;
    }

    /**
     * Get distances
     *
     * @return string
     */
    public function getDistances()
    {
        return $this->distances;
    }

    /**
     * Set mapImageUrl
     *
     * @param string $mapImageUrl
     *
     * @return HotelSearchResponse
     */
    public function setMapImageUrl($mapImageUrl)
    {
        $this->mapImageUrl = $mapImageUrl;

        return $this;
    }

    /**
     * Get mapImageUrl
     *
     * @return string
     */
    public function getMapImageUrl()
    {
        return $this->mapImageUrl;
    }

    /**
     * Set mainImage
     *
     * @param string $mainImage
     *
     * @return HotelSearchResponse
     */
    public function setMainImage($mainImage)
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    /**
     * Get mainImage
     *
     * @return string
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * Set mainImageMobile
     *
     * @param string $mainImageMobile
     *
     * @return HotelSearchResponse
     */
    public function setMainImageMobile($mainImageMobile)
    {
        $this->mainImageMobile = $mainImageMobile;

        return $this;
    }

    /**
     * Get mainImageMobile
     *
     * @return string
     */
    public function getMainImageMobile()
    {
        return $this->mainImageMobile;
    }

    /**
     * Set cancelable
     *
     * @param integer $cancelable
     *
     * @return HotelSearchResponse
     */
    public function setCancelable($cancelable)
    {
        $this->cancelable = $cancelable;

        return $this;
    }

    /**
     * Get cancelable
     *
     * @return integer
     */
    public function getCancelable()
    {
        return $this->cancelable;
    }

    /**
     * Set breakfast
     *
     * @param integer $breakfast
     *
     * @return HotelSearchResponse
     */
    public function setBreakfast($breakfast)
    {
        $this->breakfast = $breakfast;

        return $this;
    }

    /**
     * Get breakfast
     *
     * @return integer
     */
    public function getBreakfast()
    {
        return $this->breakfast;
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
