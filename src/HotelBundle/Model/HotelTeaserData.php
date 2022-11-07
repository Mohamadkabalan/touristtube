<?php

namespace HotelBundle\Model;

use HotelBundle\Entity\AmadeusHotelSource;

/**
 * Description of HotelTeaserData
 *
 */
class HotelTeaserData extends AmadeusHotelSource
{
    private $hotelSearchRequestId;
    private $hotelKey;
    private $hotelNameURL;
    private $currencyCode;
    private $price;
    private $avgPrice;
    private $distance;
    private $distances;
    private $mapImageUrl;
    private $mainImage;
    private $mainImageMobile;
    private $cancelable;
    private $breakfast;

    public function __construct()
    {
        parent::__construct();

        $this->setHotel(new \HotelBundle\Entity\AmadeusHotel());
        $this->getHotel()->setCity(new \HotelBundle\Entity\AmadeusHotelCity());
    }

    public function getHotelSearchRequestId()
    {
        return $this->hotelSearchRequestId;
    }

    public function getHotelKey()
    {
        return $this->hotelKey;
    }

    public function getHotelNameURL()
    {
        return $this->hotelNameURL;
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getAvgPrice()
    {
        return $this->avgPrice;
    }

    public function getDistance()
    {
        return $this->distance;
    }

    public function getDistances()
    {
        return $this->distances;
    }

    public function getMapImageUrl()
    {
        return $this->mapImageUrl;
    }

    public function getMainImage()
    {
        return $this->mainImage;
    }

    public function getMainImageMobile()
    {
        return $this->mainImageMobile;
    }

    public function isCancelable()
    {
        return $this->cancelable;
    }

    public function hasBreakfast()
    {
        return $this->breakfast;
    }

    public function setHotelSearchRequestId($hotelSearchRequestId)
    {
        $this->hotelSearchRequestId = $hotelSearchRequestId;
        return $this;
    }

    public function setHotelKey($hotelKey)
    {
        $this->hotelKey = $hotelKey;
        return $this;
    }

    public function setHotelNameURL($hotelNameURL)
    {
        $this->hotelNameURL = $hotelNameURL;
        return $this;
    }

    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    public function setAvgPrice($avgPrice)
    {
        $this->avgPrice = $avgPrice;
        return $this;
    }

    public function setDistance($distance)
    {
        $this->distance = $distance;
        return $this;
    }

    public function setDistances($distances)
    {
        $this->distances = $distances;
        return $this;
    }

    public function setMapImageUrl($mapImageUrl)
    {
        $this->mapImageUrl = $mapImageUrl;
        return $this;
    }

    public function setMainImage($mainImage)
    {
        $this->mainImage = $mainImage;
        return $this;
    }

    public function setMainImageMobile($mainImageMobile)
    {
        $this->mainImageMobile = $mainImageMobile;
        return $this;
    }

    public function setCancelable($cancelable)
    {
        $this->cancelable = $cancelable;
        return $this;
    }

    public function setBreakfast($breakfast)
    {
        $this->breakfast = $breakfast;
        return $this;
    }
}
