<?php

namespace HotelBundle\Model;

/**
 * The Hotel model class
 *
 *
 */
class Hotel
{
    private $hotelId                        = 0;
    private $hotelCode                      = '';
    private $chainCode                      = '';
    private $hotelCityCode                  = '';
    private $name                           = '';
    private $hotelNameURL                   = '';
    private $namealt                        = '';
    private $description                    = '';
    private $category                       = 0;
    private $street                         = '';
    private $district                       = '';
    private $zipCode                        = '';
    private $city                           = null;
    private $latitude                       = null;
    private $longitude                      = null;
    private $gpsLatitude                    = '';
    private $gpsLongitude                   = '';
    private $checkInEarliest                = '14:00';
    private $checkOutLatest                 = '12:00-13:00';
    private $contacts                       = array();
    private $email                          = '';
    private $phone                          = '';
    private $fax                            = '';
    private $mainImage                      = '/media/images/hotel-icon-image2.jpg';
    private $mainImageBig                   = '/media/images/hotel-icon-image2.jpg';
    private $images                         = array();
    private $has360                         = false;
    private $mapImage                       = '';
    private $mapImageUrl                    = '';
    private $relatedPhotosVideos            = array();
    private $acceptedCreditCards            = array();
    private $creditCardDetails              = array();
    private $creditCardSecurityCodeRequired = false;
    private $distances                      = array();
    private $distanceRefPoints              = null;
    private $amenities                      = array();
    private $facilities                     = array();
    private $amenityInfo                    = array();
    private $freeServices                   = array();
    private $cancellationAndPrepayment      = '';
    private $childrenAndExtraBeds           = '';
    private $basicPropertyInfo              = array();
    private $totalNumOffers                 = 0;
    private $roomOffers                     = array();
    private $includedTaxAndFees             = array();
    private $gds                            = false;
    private $groupSell                      = null;
    private $trustyou                       = array();
    private $published                      = 0;
    private $nearbyAttraction               = array();
    private $hotelSource                    = '';

    /**
     * Get hotelId
     * @return Integer
     */
    public function getHotelId()
    {
        return $this->hotelId;
    }

    /**
     * Set hotelId
     * @param Integer $hotelId
     */
    public function setHotelId($hotelId)
    {
        $this->hotelId = $hotelId;
    }

    /**
     * Get hotelCode.
     * @return String
     */
    public function getHotelCode()
    {
        return $this->hotelCode;
    }

    /**
     * Set hotelCode.
     * @param String $hotelCode
     */
    public function setHotelCode($hotelCode)
    {
        $this->hotelCode = $hotelCode;
    }

    /**
     * Get chainCode
     * @return String
     */
    public function getChainCode()
    {
        return $this->chainCode;
    }

    /**
     * Set chainCode
     * @param String $chainCode
     */
    public function setChainCode($chainCode)
    {
        $this->chainCode = $chainCode;
    }

    /**
     * Get hotelCityCode
     * @return String
     */
    public function getHotelCityCode()
    {
        return $this->hotelCityCode;
    }

    /**
     * Set hotelCityCode
     * @param String $hotelCityCode
     */
    public function setHotelCityCode($hotelCityCode)
    {
        $this->hotelCityCode = $hotelCityCode;
    }

    /**
     * Get name
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get hotelNameURL
     * @return String
     */
    public function getHotelNameURL()
    {
        return $this->hotelNameURL;
    }

    /**
     * Set hotelNameURL
     * @param String $hotelNameURL
     */
    public function setHotelNameURL($hotelNameURL)
    {
        $this->hotelNameURL = $hotelNameURL;
    }

    /**
     * Get namealt
     * @return String
     */
    public function getNamealt()
    {
        return $this->namealt;
    }

    /**
     * Set namealt
     * @param String $namealt
     */
    public function setNamealt($namealt)
    {
        $this->namealt = $namealt;
    }

    /**
     * Get description.
     * @return String
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description.
     * @param String $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get category
     * @return Integer
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set category
     * @param Integer $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Get street
     * @return String
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set street
     * @param String $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * Get district
     * @return String
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set district
     * @param String $district
     */
    public function setDistrict($district)
    {
        $this->district = $district;
    }

    /**
     * Get zipCode
     * @return String
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set zipCode
     * @param String $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * Get city
     * @return AmadeusHoteCity
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city
     * @param AmadeusHoteCity $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * This method prepares the full address line as a concatenation of several address elements
     * @return String A comma-separated full address.
     */
    public function getAddress()
    {
        $cityName    = '';
        $countryName = '';

        if (!empty($this->getCity())) {
            $cityName    = $this->getCity()->getName();
            $countryName = $this->getCity()->getCountryName();
        }

        $address = array(
            $this->getStreet(),
            $this->getDistrict(),
            $this->getZipCode(),
            $cityName,
            $countryName
        );

        return implode(", ", array_filter($address));
    }

    /**
     * Get latitude
     * @return Float
     */
    public function getLatitude()
    {
        return floatval($this->latitude);
    }

    /**
     * Set latitude
     * @param Float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Get longitude
     * @return Float
     */
    public function getLongitude()
    {
        return floatval($this->longitude);
    }

    /**
     * Set longitude
     * @param Float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get gpsLatitude
     * @return String
     */
    public function getGpsLatitude()
    {
        if (empty($this->gpsLatitude) && !empty($this->getLatitude())) {
            $this->gpsLatitude = $this->decToDMS($this->getLatitude(), 1);
        }
        return $this->gpsLatitude;
    }

    /**
     * Get gpsLongitude
     * @return String
     */
    public function getGpsLongitude()
    {
        if (empty($this->gpsLongitude) && !empty($this->getLongitude())) {
            $this->gpsLongitude = $this->decToDMS($this->getLongitude(), 1);
        }
        return $this->gpsLongitude;
    }

    /**
     * Converts decimal longitude / latitude to DMS ( Degrees / minutes / seconds )
     * This is the piece of code which may appear to be inefficient, but to avoid issues with floating point math we extract the integer part and the float part by using a string function.
     *
     * @param Float $dec
     * @return String
     */
    private function decToDMS($dec)
    {
        $vars   = explode(".", $dec);
        $deg    = $vars[0];
        $tempma = "0.".$vars[1];

        $tempma = $tempma * 3600;
        $min    = floor($tempma / 60);
        $sec    = $tempma - ($min * 60);

        return html_entity_decode("{$deg}&deg;{$min}'{$sec}\"");
    }

    /**
     * Get checkInEarliest.
     * @return String
     */
    public function getCheckInEarliest()
    {
        return $this->checkInEarliest;
    }

    /**
     * Set checkInEarliest.
     * @param String $checkInEarliest
     */
    public function setCheckInEarliest($checkInEarliest)
    {
        $this->checkInEarliest = $checkInEarliest;
    }

    /**
     * Get checkOutLatest.
     * @return String
     */
    public function getCheckOutLatest()
    {
        return $this->checkOutLatest;
    }

    /**
     * Set. checkOutLatest.
     * @param String $checkOutLatest
     */
    public function setCheckOutLatest($checkOutLatest)
    {
        $this->checkOutLatest = $checkOutLatest;
    }

    /**
     * Get contacts.
     * @return array
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Set contacts.
     * @param array $contacts
     */
    public function setContacts(array $contacts)
    {
        $this->contacts = $contacts;
    }

    /**
     * Get email
     * @return String
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     * @param String $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get phone
     * @return String
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone
     * @param String $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get fax
     * @return String
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set fax
     * @param String $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * Get mainImage
     * @return String
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * Set mainImage
     * @param String $mainImage
     */
    public function setMainImage($mainImage)
    {
        $this->mainImage = $mainImage;
    }

    /**
     * Get mainImageBig
     * @return String
     */
    public function getMainImageBig()
    {
        return $this->mainImageBig;
    }

    /**
     * Set mainImageBig
     * @param String $mainImageBig
     */
    public function setMainImageBig($mainImageBig)
    {
        $this->mainImageBig = $mainImageBig;
    }

    /**
     * Get images
     * @return Array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set images
     * @param Array $images
     */
    public function setImages(array $images)
    {
        $this->images = $images;
    }

    /**
     * Get has360
     * @return Boolean
     */
    public function getHas360()
    {
        return $this->has360;
    }

    /**
     * Set has360
     * @param Boolean $has360
     */
    public function setHas360($has360)
    {
        $this->has360 = $has360;
    }

    /**
     * Get mapImage
     * @return String
     */
    public function getMapImage()
    {
        return $this->mapImage;
    }

    /**
     * Set mapImage
     * @param String $mapImage
     */
    public function setMapImage($mapImage)
    {
        $this->mapImage = $mapImage;
    }

    /**
     * Get mapImageUrl
     * @return String
     */
    public function getMapImageUrl()
    {
        return $this->mapImageUrl;
    }

    /**
     * Set mapImageUrl
     * @param String $mapImageUrl
     */
    public function setMapImageUrl($mapImageUrl)
    {
        $this->mapImageUrl = $mapImageUrl;
    }

    /**
     * Get relatedPhotosVideos
     * @return Array
     */
    public function getRelatedPhotosVideos()
    {
        return $this->relatedPhotosVideos;
    }

    /**
     * Set relatedPhotosVideos
     * @param Array $relatedPhotosVideos
     */
    public function setRelatedPhotosVideos(array $relatedPhotosVideos)
    {
        $this->relatedPhotosVideos = $relatedPhotosVideos;
    }

    /**
     * Get acceptedCreditCards
     * @return Array
     */
    public function getAcceptedCreditCards()
    {
        return $this->acceptedCreditCards;
    }

    /**
     * Set acceptedCreditCards
     * @param Array $acceptedCreditCards
     */
    public function setAcceptedCreditCards(array $acceptedCreditCards)
    {
        $this->acceptedCreditCards = $acceptedCreditCards;
    }

    /**
     * Get creditCardDetails
     * @return Array
     */
    public function getCreditCardDetails()
    {
        return $this->creditCardDetails;
    }

    /**
     * Set creditCardDetails
     * @param Array $creditCardDetails
     */
    public function setCreditCardDetails(array $creditCardDetails)
    {
        $this->creditCardDetails = $creditCardDetails;
    }

    /**
     * Get creditCardSecurityCodeRequired
     * @return Boolean
     */
    public function getCreditCardSecurityCodeRequired()
    {
        return $this->creditCardSecurityCodeRequired;
    }

    /**
     * Set creditCardSecurityCodeRequired
     * @param Boolean $creditCardSecurityCodeRequired
     */
    public function setCreditCardSecurityCodeRequired($creditCardSecurityCodeRequired)
    {
        $this->creditCardSecurityCodeRequired = $creditCardSecurityCodeRequired;
    }

    /**
     * Get distances
     * @return Array
     */
    public function getDistances()
    {
        return $this->distances;
    }

    /**
     * Set distances
     * @param Array $distances
     */
    public function setDistances($distances)
    {
        $this->distances = $distances;
    }

    /**
     * Get distanceRefPoints.
     * @return \DOMNodeList
     */
    public function getDistanceRefPoints()
    {
        return $this->distanceRefPoints;
    }

    /**
     * Set distanceRefPoints.
     * @param \DOMNodeList $distanceRefPoints
     */
    public function setDistanceRefPoints(\DOMNodeList $distanceRefPoints)
    {
        $this->distanceRefPoints = $distanceRefPoints;
    }

    /**
     * Get amenities;
     * @return Array
     */
    public function getAmenities()
    {
        return $this->amenities;
    }

    /**
     * Set amenities.
     * @param Array $amenities
     */
    public function setAmenities(array $amenities)
    {
        $this->amenities = $amenities;
    }

    /**
     * Get facilities
     * @return Array
     */
    public function getFacilities()
    {
        return $this->facilities;
    }

    /**
     * Set facilities
     * @param Array $facilities
     */
    public function setFacilities(array $facilities)
    {
        $this->facilities = $facilities;
    }

    /**
     * Get amenityInfo
     * @return Array
     */
    public function getAmenityInfo()
    {
        return $this->amenityInfo;
    }

    /**
     * Set amenityInfo
     * @param Array $amenityInfo
     */
    public function setAmenityInfo(array $amenityInfo)
    {
        $this->amenityInfo = $amenityInfo;
    }

    /**
     * Add amenityInfo
     *
     * @param \DOMNodeList $amenities
     * @param String $otaCategory
     * @param String $attribute
     * @param String $facilityType
     */
    public function addAmenityInfo(\DOMNodeList $amenities, $otaCategory, $attribute, $facilityType)
    {
        $this->amenityInfo[] = array(
            'type' => $facilityType,
            'otaCategory' => $otaCategory,
            'nodeAttribute' => $attribute,
            'nodeList' => $amenities,
        );
    }

    /**
     * Get freeServices
     * @return Array
     */
    public function getFreeServices()
    {
        return $this->freeServices;
    }

    /**
     * Set freeServices
     * @param Array $freeServices
     */
    public function setFreeServices(array $freeServices)
    {
        $this->freeServices = $freeServices;
    }

    /**
     * Get cancellationAndPrepayment
     * @return String
     */
    public function getCancellationAndPrepayment()
    {
        return $this->cancellationAndPrepayment;
    }

    /**
     * Set cancellationAndPrepayment
     * @param String $cancellationAndPrepayment
     */
    public function setCancellationAndPrepayment($cancellationAndPrepayment)
    {
        $this->cancellationAndPrepayment = $cancellationAndPrepayment;
    }

    /**
     * Get childrenAndExtraBeds
     * @return String
     */
    public function getChildrenAndExtraBeds()
    {
        return $this->childrenAndExtraBeds;
    }

    /**
     * Set childrenAndExtraBeds
     * @param String $childrenAndExtraBeds
     */
    public function setChildrenAndExtraBeds($childrenAndExtraBeds)
    {
        $this->childrenAndExtraBeds = $childrenAndExtraBeds;
    }

    /**
     * Get basicPropertyInfo.
     * @return array
     */
    public function getBasicPropertyInfo()
    {
        return $this->basicPropertyInfo;
    }

    /**
     * Set basicPropertyInfo.
     * @param array $basicPropertyInfo
     */
    public function setBasicPropertyInfo(array $basicPropertyInfo)
    {
        $this->basicPropertyInfo = $basicPropertyInfo;
    }

    /**
     * get totalNumOffers.
     * @return Integer
     */
    public function getTotalNumOffers()
    {
        return $this->totalNumOffers;
    }

    /**
     * Set totalNumOffers.
     * @param Integer $totalNumOffers
     */
    public function setTotalNumOffers($totalNumOffers)
    {
        $this->totalNumOffers = $totalNumOffers;
    }

    /**
     * Get roomOffers.
     * @return array
     */
    public function getRoomOffers()
    {
        return $this->roomOffers;
    }

    /**
     * Set roomOffers
     * @param array $roomOffers
     */
    public function setRoomOffers(array $roomOffers)
    {
        $this->roomOffers = $roomOffers;
    }

    /**
     * Get includedTaxAndFees.
     * @return array
     */
    public function getIncludedTaxAndFees()
    {
        return $this->includedTaxAndFees;
    }

    /**
     * Set includedTaxAndFees.
     * @param array $includedTaxAndFees
     */
    public function setIncludedTaxAndFees(array $includedTaxAndFees)
    {
        $this->includedTaxAndFees = $includedTaxAndFees;
    }

    /**
     * Get gds.
     * @return Boolean
     */
    public function isGds()
    {
        return $this->gds;
    }

    /**
     * Set gds.
     * @param Boolean $gds
     */
    public function setGds($gds)
    {
        $this->gds = $gds;
    }

    /**
     * Get groupSell.
     * @return Integer
     */
    public function getGroupSell()
    {
        return $this->groupSell;
    }

    /**
     * Set groupSell.
     * @param Integer $groupSell
     */
    public function setGroupSell($groupSell)
    {
        $this->groupSell = $groupSell;
    }

    /**
     * Get trustyou.
     * @return array
     */
    public function getTrustyou()
    {
        return $this->trustyou;
    }

    /**
     * Set trustyou
     * @param array $trustyou
     */
    public function setTrustyou(array $trustyou)
    {
        $this->trustyou = $trustyou;
    }

    /**
     * Determines if hotel is published
     * @return type
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

    /**
     * Get nearbyAttraction.
     * @return array
     */
    public function getNearbyAttraction()
    {
        return $this->nearbyAttraction;
    }

    /**
     * Set nearbyAttraction
     * @param array $nearbyAttraction
     */
    public function setNearbyAttraction($nearbyAttraction)
    {
        $this->nearbyAttraction = $nearbyAttraction;
    }

    /**
     * Get hotelSource.
     * @return String
     */
    public function getHotelSource()
    {
        return $this->hotelSource;
    }

    /**
     * Set hotelSource
     * @param String $hotelSource
     */
    public function setHotelSource($hotelSource)
    {
        $this->hotelSource = $hotelSource;
    }

    /**
     * If it's active or not.
     * @return type
     */
    public function isActive()
    {
        return $this->isPublished();
    }

    public function merge(Hotel $hotel, $overrideAmenities = true)
    {
        foreach ($hotel as $key => $value) {
            if (!empty($value)) {
                if (!$overrideAmenities && in_array($key, array('amenities', 'facilities'))) {
                    continue;
                }
                $setter = 'set'.ucfirst($key);
                $this->$setter($value);
            }
        }
        return $this;
    }

    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $attribute) {
            if ($this->getCity() != null && $key == 'city') {
                $cityAttributes = array('id', 'name', 'code', 'countryCode', 'countryName');
                foreach ($cityAttributes as $value) {
                    $getter                 = 'get'.ucfirst($value);
                    $toreturn[$key][$value] = $this->getCity()->$getter();
                }
            } else {
                $toreturn[$key] = $attribute;
            }
        }

        $toreturn['active'] = $this->isActive();

        if (!isset($toreturn['address'])) {
            $toreturn['address'] = $this->getAddress();
        }

        $toreturn['roomOffers'] = array();
        foreach ($this->getRoomOffers() as $offer) {
            $toreturn['roomOffers'] = $offer->toArray();
        }

        return $toreturn;
    }
}
