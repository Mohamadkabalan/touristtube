<?php

namespace HotelBundle\Model;

/**
 * The Hotel Search Criteria(SC) model class
 *
 *
 */
class HotelSC
{
    private $hotelSearchRequestId = 0;
    private $hotelCode            = '';
    private $hotelId              = 0;
    private $hotelKey             = 0;
    private $hotelName            = '';
    private $hotelNameURL         = '';
    private $locationId           = 0;
    private $city;
    private $country              = '';
    private $iso3CountryCode      = '';
    private $longitude            = 0;
    private $latitude             = 0;
    private $perimeter            = 0;
    private $fromDate             = '';
    private $toDate               = '';
    private $singleRooms          = 0;
    private $doubleRooms          = 1;
    private $adultCount           = 2;
    private $childCount           = 0;
    private $maxChildCount        = 6;
    private $childAge             = array();
    private $childBed             = array();
    private $maxChildAge          = 17;
    private $page                 = 1;
    private $sortBy               = '';
    private $sortOrder            = '';
    private $nbrStars             = '';
    private $district             = '';
    private $distanceRange        = '';
    private $distance             = '';
    private $maxDistance          = 0;
    private $budgetRange          = '';
    private $priceRange           = array();
    private $maxPrice             = 0;
    private $currency             = '';
    private $limit                = 0;
    private $entityType           = 0;
    private $geoLocationSearch    = false;
    private $infoSource           = '';
    private $session              = '';
    private $useTTApi             = false;
    private $isCancelable         = 0;
    private $hasBreakfast         = 0;
    private $has360               = 0;
    private $published            = 0;
    private $prepaidOnly          = false;
    private $fromMobile           = false;
    private $refererURL           = '';
    private $hotelSource          = '';

    public function __construct()
    {
        $this->city = new City();
    }

    /**
     * Set hotelSearchRequestId
     *
     * @param int $hotelSearchRequestId
     *
     * @return HotelSC
     */
    public function setHotelSearchRequestId($hotelSearchRequestId)
    {
        $this->hotelSearchRequestId = intval($hotelSearchRequestId);
    }

    /**
     * Get hotelSearchRequestId
     *
     * @return int
     */
    public function getHotelSearchRequestId()
    {
        return $this->hotelSearchRequestId;
    }

    /**
     * Set hotelCode
     *
     * @param int $hotelCode
     *
     * @return HotelSC
     */
    public function setHotelCode($hotelCode)
    {
        $this->hotelCode = $hotelCode;
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
     * Set hotelId
     *
     * @param int $hotelId
     *
     * @return HotelSC
     */
    public function setHotelId($hotelId)
    {
        $this->hotelId = intval($hotelId);
    }

    /**
     * Get hotelId
     *
     * @return int
     */
    public function getHotelId()
    {
        return $this->hotelId;
    }

    /**
     * Set hotelKey
     *
     * @param int $hotelKey
     *
     * @return HotelSC
     */
    public function setHotelKey($hotelKey)
    {
        $this->hotelKey = intval($hotelKey);

        return $this;
    }

    /**
     * Get hotelKey
     *
     * @return int
     */
    public function getHotelKey()
    {
        return $this->hotelKey;
    }

    /**
     * Set hotelName
     *
     * @param int $hotelName
     *
     * @return HotelSC
     */
    public function setHotelName($hotelName)
    {
        $this->hotelName = $hotelName;
    }

    /**
     * Get hotelName
     *
     * @return int
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
     * @return HotelSC
     */
    public function setHotelNameURL($hotelNameURL)
    {
        $this->hotelNameURL = $hotelNameURL;
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
     * Set locationId
     *
     * @param string $locationId
     *
     * @return HotelSC
     */
    public function setLocationId($locationId)
    {
        $this->locationId = intval($locationId);

        return $this;
    }

    /**
     * Get locationId
     *
     * @return string
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * Set singleRooms
     *
     * @param int $singleRooms
     *
     * @return HotelSC
     */
    public function setSingleRooms($singleRooms)
    {
        $this->singleRooms = intval($singleRooms);
    }

    /**
     * Get singleRooms
     *
     * @return int
     */
    public function getSingleRooms()
    {
        return $this->singleRooms;
    }

    /**
     * Set doubleRooms
     *
     * @param int $doubleRooms
     *
     * @return HotelSC
     */
    public function setDoubleRooms($doubleRooms)
    {
        $this->doubleRooms = intval($doubleRooms);
    }

    /**
     * Get doubleRooms
     *
     * @return int
     */
    public function getDoubleRooms()
    {
        return $this->doubleRooms;
    }

    /**
     * Set adultCount
     *
     * @param int $adultCount
     *
     * @return HotelSC
     */
    public function setAdultCount($adultCount)
    {
        $this->adultCount = intval($adultCount);
    }

    /**
     * Get adultCount
     *
     * @return int
     */
    public function getAdultCount()
    {
        return $this->adultCount;
    }

    /**
     * Set childCount
     *
     * @param int $childCount
     *
     * @return HotelSC
     */
    public function setChildCount($childCount)
    {
        $this->childCount = intval($childCount);
    }

    /**
     * Get childCount
     *
     * @return int
     */
    public function getChildCount()
    {
        return $this->childCount;
    }

    /**
     * Set page
     *
     * @param int $page
     *
     * @return HotelSC
     */
    public function setPage($page)
    {
        $this->page = intval($page);
    }

    /**
     * Get page
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set sortBy
     *
     * @param string $sortBy
     *
     * @return HotelSC
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    /**
     * Get sortBy
     *
     * @return string
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * Set sortOrder
     *
     * @param string $sortOrder
     *
     * @return HotelSC
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * Get sortOrder
     *
     * @return string
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Set nbrStars
     *
     * @param string $nbrStars
     *
     * @return HotelSC
     */
    public function setNbrStars($nbrStars)
    {
        $this->nbrStars = $nbrStars;
    }

    /**
     * Get nbrStars
     *
     * @return string
     */
    public function getNbrStars()
    {
        return $this->nbrStars;
    }

    /**
     * Set district
     *
     * @param string $district
     *
     * @return HotelSC
     */
    public function setDistrict($district)
    {
        $this->district = $district;
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
     * Set budgetRange
     *
     * @param array $budgetRange
     *
     * @return HotelSC
     */
    public function setBudgetRange($budgetRange)
    {
        $this->budgetRange = $budgetRange;
    }

    /**
     * Get budgetRange
     *
     * @return array
     */
    public function getBudgetRange()
    {
        return $this->budgetRange;
    }

    /**
     * Set priceRange
     *
     * @param array $priceRange
     *
     * @return HotelSC
     */
    public function setPriceRange($priceRange)
    {
        $this->priceRange = $priceRange;
    }

    /**
     * Get priceRange
     *
     * @return array
     */
    public function getPriceRange()
    {
        return $this->priceRange;
    }

    /**
     * Set maxPrice
     *
     * @param string $maxPrice
     *
     * @return HotelSC
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = floatval($maxPrice);
    }

    /**
     * Get maxPrice
     *
     * @return array
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return HotelSC
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set distanceRange
     *
     * @param string $distanceRange
     *
     * @return HotelSC
     */
    public function setDistanceRange($distanceRange)
    {
        $this->distanceRange = $distanceRange;
    }

    /**
     * Get distanceRange
     *
     * @return string
     */
    public function getDistanceRange()
    {
        return $this->distanceRange;
    }

    /**
     * Set limit
     *
     * @param int $limit
     *
     * @return HotelSC
     */
    public function setLimit($limit)
    {
        $this->limit = intval($limit);
    }

    /**
     * Get limit
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Determines if we are doing a new search or not.
     *
     * @return int
     */
    public function isNewSearch()
    {
        $filterPrice = 0;
        if (!empty($this->getPriceRange())) {
            $range = $this->getPriceRange();
            if (is_array($range) && count($range)) {
                $filterPrice = 1;
            }
        }

        $filterDistance = 0;
        if (!empty($this->getDistanceRange())) {
            $range = $this->getDistanceRange();
            if (is_array($range) && count($range)) {
                $filterDistance = 1;
            }
        }


        if (intval($this->getHotelSearchRequestId()) > 0 ||
            !empty($this->getNbrStars()) ||
            $this->isCancelable() ||
            $this->hasBreakfast() ||
            $this->has360() ||
            !empty($this->getBudgetRange()) ||
            $filterPrice ||
            $filterDistance ||
            !empty($this->getDistrict()) ||
            !empty($this->getSortBy()) ||
            intval($this->getPage()) > 1) {
            return 0;
        }

        return 1;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return HotelSC
     */
    public function setCountry($country)
    {
        $this->country = $country;
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
     * Set iso3CountryCode
     *
     * @param string $iso3CountryCode
     *
     * @return HotelSC
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
     * Set longitude
     *
     * @param int $longitude
     *
     * @return HotelSC
     */
    public function setLongitude($longitude)
    {
        $this->longitude = floatval($longitude);
    }

    /**
     * Get longitude
     *
     * @return int
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param int $latitude
     *
     * @return HotelSC
     */
    public function setLatitude($latitude)
    {
        $this->latitude = floatval($latitude);
    }

    /**
     * Get latitude
     *
     * @return int
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set perimeter
     *
     * @param int $perimeter
     *
     * @return HotelSC
     */
    public function setPerimeter($perimeter)
    {
        $this->perimeter = floatval($perimeter);

        return $this;
    }

    /**
     * Get perimeter
     *
     * @return int
     */
    public function getPerimeter()
    {
        return $this->perimeter;
    }

    /**
     * Set distance
     *
     * @param int $distance
     *
     * @return HotelSC
     */
    public function setDistance($distance)
    {
        $this->distance = floatval($distance);
    }

    /**
     * Get distance
     *
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set maxDistance
     *
     * @param int $maxDistance
     *
     * @return HotelSC
     */
    public function setMaxDistance($maxDistance)
    {
        $this->maxDistance = floatval($maxDistance);
    }

    /**
     * Get maxDistance
     *
     * @return int
     */
    public function getMaxDistance()
    {
        return $this->maxDistance;
    }

    /**
     * Set entityType
     *
     * @param int $entityType
     *
     * @return HotelSC
     */
    public function setEntityType($entityType)
    {
        $this->entityType = intval($entityType);
    }

    /**
     * Get entityType
     *
     * @return int
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Set geoLocationSearch
     *
     * @param int $geoLocationSearch
     *
     * @return HotelSC
     */
    public function setGeoLocationSearch($geoLocationSearch)
    {
        $this->geoLocationSearch = boolval($geoLocationSearch);
    }

    /**
     * Get geoLocationSearch
     *
     * @return Boolean
     */
    public function isGeoLocationSearch()
    {
        return $this->geoLocationSearch;
    }

    /**
     * Set infoSource
     *
     * @param string $infoSource
     *
     * @return HotelSC
     */
    public function setInfoSource($infoSource)
    {
        $this->infoSource = $infoSource;
    }

    /**
     * Get infoSource
     *
     * @return string
     */
    public function getInfoSource()
    {
        return $this->infoSource;
    }

    /**
     * Set session
     *
     * @param string $session
     *
     * @return HotelSC
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * Get session
     *
     * @return string
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set city information.
     * @param \HotelBundle\Model\City $city
     * @return $this
     */
    public function setCity(City $city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city information.
     * @return \HotelBundle\Model\City.
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set fromDate
     *
     * @param string $fromDate
     *
     * @return HotelSC
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;
    }

    /**
     * Get fromDate
     *
     * @return string
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param string $toDate
     *
     * @return HotelSC
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;
    }

    /**
     * Get toDate
     *
     * @return string
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Set childAge
     *
     * @param array $childAge
     *
     * @return HotelSC
     */
    public function setChildAge($childAge)
    {
        $this->childAge = $childAge;
    }

    /**
     * Get childAge
     *
     * @return array
     */
    public function getChildAge()
    {
        return $this->childAge;
    }

    /**
     * Set childBed
     *
     * @param array $childBed
     *
     * @return HotelSC
     */
    public function setChildBed(array $childBed)
    {
        $this->childBed = $childBed;

        return $this;
    }

    /**
     * Get childBed
     *
     * @return array
     */
    public function getChildBed()
    {
        return $this->childBed;
    }

    /**
     * Set maxChildCount
     *
     * @param array $maxChildCount
     *
     * @return HotelSC
     */
    public function setMaxChildCount($maxChildCount)
    {
        $this->maxChildCount = intval($maxChildCount);
    }

    /**
     * Get maxChildCount
     *
     * @return array
     */
    public function getMaxChildCount()
    {
        return $this->maxChildCount;
    }

    /**
     * Set maxChildAge
     *
     * @param array $maxChildAge
     *
     * @return HotelSC
     */
    public function setMaxChildAge($maxChildAge)
    {
        $this->maxChildAge = intval($maxChildAge);
    }

    /**
     * Get maxChildAge
     *
     * @return array
     */
    public function getMaxChildAge()
    {
        return $this->maxChildAge;
    }

    /**
     * Set useTTApi
     *
     * @param array $useTTApi
     * @return $this
     */
    public function setUseTTApi($useTTApi)
    {
        $this->useTTApi = boolval($useTTApi);
        return $this;
    }

    /**
     * Get useTTApi
     *
     * @return array
     */
    public function isUseTTApi()
    {
        return $this->useTTApi;
    }

    /**
     * Determines if to show only those that are cancelable or show all.
     * @return Integer  1 (true); otherwise 0 (false).
     */
    public function isCancelable()
    {
        return $this->isCancelable;
    }

    /**
     * Set if to show only those that are cancelable or show all.
     * @param Integer $isCancelable Value 1 (true); otherwise 0 (false).
     * @return $this
     */
    public function setIsCancelable($isCancelable)
    {
        $this->isCancelable = intval($isCancelable);
        return $this;
    }

    /**
     * Determines if to show only those that includes breakfast or show all.
     * @return Integer  Value 1 (true); otherwise 0 (false).
     */
    public function hasBreakfast()
    {
        return $this->hasBreakfast;
    }

    /**
     * Set if to show only those that includes breakfast or show all.
     * @param Integer $hasBreakfast Value 1 (true); otherwise 0 (false).
     * @return $this
     */
    public function setHasBreakfast($hasBreakfast)
    {
        $this->hasBreakfast = intval($hasBreakfast);
        return $this;
    }

    /**
     * Determines if to show only those hotels with 360 images or show all.
     * @return Integer  Value 1 (true); otherwise 0 (false).
     */
    public function has360()
    {
        return $this->has360;
    }

    /**
     * Set if to show only those hotels with 360 images or show all.
     * @param Integer $has360 Value 1 (true); otherwise 0 (false).
     * @return $this
     */
    public function setHas360($has360)
    {
        $this->has360 = intval($has360);
        return $this;
    }

    /**
     * Get published.
     * @return boolean
     */
    public function isPublished()
    {
        return ($this->published == 1);
    }

    /**
     * Set published
     * @param Integer $published
     * @return $this
     */
    public function setPublished($published)
    {
        $this->published = $published;
        return $this;
    }

    public function isPrepaidOnly()
    {
        return $this->prepaidOnly;
    }

    public function setPrepaidOnly($prepaidOnly)
    {
        $this->prepaidOnly = $prepaidOnly;
        return $this;
    }

    /**
     * Set from mobile flag
     * @param  Boolean/Integer $maxResultsPerPage true/false OR 1/0
     * @return HotelSC
     */
    public function setFromMobile($fromMobile)
    {
        $this->fromMobile = $fromMobile;

        return $this;
    }

    /**
     * Get from mobile flag
     * @return Integer
     */
    public function isFromMobile()
    {
        return $this->fromMobile;
    }

    /**
     * Set referer url
     * @param  Boolean/Integer $maxResultsPerPage true/false OR 1/0
     * @return HotelSC
     */
    public function setRefererURL($refererURL)
    {
        $this->refererURL = $refererURL;

        return $this;
    }

    /**
     * Get referer url
     * @return Integer
     */
    public function getRefererURL()
    {
        return $this->refererURL;
    }

    /**
     * Set hotelSource.
     * @param String $hotelSource
     * @return $this
     */
    public function setHotelSource($hotelSource)
    {
        $this->hotelSource = strtolower($hotelSource);
        return $this;
    }

    /**
     * Get htoelSource
     * @return String
     */
    public function getHotelSource()
    {
        return $this->hotelSource;
    }

    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $value) {
            if (is_object($value) && method_exists($value, 'toArray')) {
                $toreturn[$key] = $value->toArray();
            } else {
                $toreturn[$key] = $value;
            }
        }
        return $toreturn;
    }
}
