<?php

namespace HotelBundle\Model;

/**
 * The HotelRoomOffer model class
 *
 *
 */
class HotelRoomOffer
{
    private $counter;
    private $bookableInfo;
    private $header;
    private $name;
    private $description;
    private $rates;
    private $roomCategory;
    private $roomId;
    private $roomType;
    private $roomTypeCode;
    private $roomTypeConverted;
    private $roomTypeInfo;
    private $bedTypeCode;
    private $withBreakfast;
    private $mealPlanCodes;
    private $breakfastType;
    private $breakfastRates;
    private $prepaid;
    private $prepaymentDetails  = '';
    private $prepaymentHoldTime;
    private $prepaymentType;
    private $prepaymentValueMode;
    private $cancellable;
    private $cancellationPenalties;
    private $maxRoomCount       = null;
    private $roomOfferXml;
    private $includedTaxAndFees = array();
    private $roomOfferType      = '';
    private $roomsLeftCount     = 1;
    private $roomSize           = '';

    /**
     * Get room offer counter/number.
     * @return Integer
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * Set room offer counter/number.
     * @param Integer $counter
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;
    }

    /**
     * Get room offer bookableInfo.
     * @return array
     */
    public function getBookableInfo()
    {
        return $this->bookableInfo;
    }

    /**
     * Set room offer bookableInfo.
     * @param array $bookableInfo
     */
    public function setBookableInfo(array $bookableInfo)
    {
        $this->bookableInfo = $bookableInfo;
    }

    /**
     * Get room offer header
     * @return Array
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set room offer header
     * @param Array $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * Get room offer name.
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set room offer name.
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get room offer description.
     * @return String
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set room offer description.
     * @param String $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get room offer rates.
     * @return array
     */
    public function getRates()
    {
        return $this->rates;
    }

    /**
     * Set room offer rates.
     * @param array $rates
     */
    public function setRates(array $rates)
    {
        $this->rates = $rates;
    }

    /**
     * Get room offer category.
     * @return String
     */
    public function getRoomCategory()
    {
        return $this->roomCategory;
    }

    /**
     * Set room offer category.
     * @param String $roomCategory
     */
    public function setRoomCategory($roomCategory)
    {
        $this->roomCategory = $roomCategory;
    }

    /**
     * Get room offer id.
     * @return Integer
     */
    public function getRoomId()
    {
        return $this->roomId;
    }

    /**
     * Set room offer id.
     * @param Integer $roomId
     */
    public function setRoomId($roomId)
    {
        $this->roomId = $roomId;
    }

    /**
     * Get offer room type.
     * @return String
     */
    public function getRoomType()
    {
        return $this->roomType;
    }

    /**
     * Set offer room type.
     * @param String $roomType
     */
    public function setRoomType($roomType)
    {
        $this->roomType = $roomType;
    }

    /**
     * Get offer room type code.
     * @return String
     */
    public function getRoomTypeCode()
    {
        return $this->roomTypeCode;
    }

    /**
     * Set offer room type code.
     * @param String $roomTypeCode
     */
    public function setRoomTypeCode($roomTypeCode)
    {
        $this->roomTypeCode = $roomTypeCode;
    }

    /**
     * Check if offer room type is amadeus converted
     * @return Boolean
     */
    public function isRoomTypeConverted()
    {
        return $this->roomTypeConverted;
    }

    /**
     * Set roomtypeConverted.
     * @param Boolean $roomTypeConverted
     */
    public function setRoomTypeConverted($roomTypeConverted)
    {
        $this->roomTypeConverted = $roomTypeConverted;
    }

    /**
     * Set roomTypeInfo.
     * @param Array $roomTypeInfo
     */
    public function getRoomTypeInfo()
    {
        return $this->roomTypeInfo;
    }

    /**
     * Get roomTypeInfo.
     * @return Array
     */
    public function setRoomTypeInfo($roomTypeInfo)
    {
        $this->roomTypeInfo = $roomTypeInfo;
    }

    /**
     * Get bedTypeCode.
     * @return Boolean
     */
    public function getBedTypeCode()
    {
        return $this->bedTypeCode;
    }

    /**
     * Set bedTypeCode.
     * @param Boolean $bedTypeCode
     */
    public function setBedTypeCode($bedTypeCode)
    {
        $this->bedTypeCode = $bedTypeCode;
    }

    /**
     * Check if room offer includes breakfast.
     * @return Boolean
     */
    public function isWithBreakfast()
    {
        return $this->withBreakfast;
    }

    /**
     * Set room offer if includes breakfast.
     * @param Boolean $withBreakfast
     */
    public function setWithBreakfast($withBreakfast)
    {
        $this->withBreakfast = $withBreakfast;
    }

    /**
     * Get room offer breakfast type
     * @return String
     */
    public function getBreakfastType()
    {
        return $this->breakfastType;
    }

    /**
     * Set room offer breakfast type
     * @param String $breakfastType
     */
    public function setBreakfastType($breakfastType)
    {
        $this->breakfastType = $breakfastType;
    }

    /**
     * Get room offer breakfast rates
     * @return Array
     */
    public function getBreakfastRates()
    {
        return $this->breakfastRates;
    }

    /**
     * Set room offer breakfast rates
     * @param Array $breakfastRates
     */
    public function setBreakfastRates($breakfastRates)
    {
        $this->breakfastRates = $breakfastRates;
    }

    /**
     * Get room offer meal plan codes.
     * @return String
     */
    public function getMealPlanCodes()
    {
        return $this->mealPlanCodes;
    }

    /**
     * Set room offer meal plan codes.
     * @param String $mealPlanCodes
     */
    public function setMealPlanCodes($mealPlanCodes)
    {
        $this->mealPlanCodes = $mealPlanCodes;
    }

    /**
     * Check if room offer is prepaid
     * @return Boolean
     */
    public function isPrepaid()
    {
        return $this->prepaid;
    }

    /**
     * Set room offer if prepaid.
     * @param Boolean $prepaid
     */
    public function setPrepaid($prepaid)
    {
        $this->prepaid = $prepaid;
    }

    /**
     * Get room offer prepayment information.
     * @return String
     */
    public function getPrepaymentDetails()
    {
        return $this->prepaymentDetails;
    }

    /**
     * Set room offer prepayment information.
     * @param String $prepaymentDetails
     */
    public function setPrepaymentDetails($prepaymentDetails)
    {
        $this->prepaymentDetails = $prepaymentDetails;
    }

    /**
     * Get room offer prepayment hold time.
     * @return String
     */
    public function getPrepaymentHoldTime()
    {
        return $this->prepaymentHoldTime;
    }

    /**
     * Set room offer prepayment hold time.
     * @param String $prepaymentHoldTime
     */
    public function setPrepaymentHoldTime($prepaymentHoldTime)
    {
        $this->prepaymentHoldTime = $prepaymentHoldTime;
    }

    /**
     * Get room offer prepayment type.
     * @return String
     */
    public function getPrepaymentType()
    {
        return $this->prepaymentType;
    }

    /**
     * Set room offer prepayment type.
     * @param String $prepaymentType
     */
    public function setPrepaymentType($prepaymentType)
    {
        $this->prepaymentType = $prepaymentType;
    }

    /**
     * Get room offer prepayment mode information
     * @return array
     */
    public function getPrepaymentValueMode()
    {
        return $this->prepaymentValueMode;
    }

    /**
     * Set room offer prepayment mode information.
     * @param array $prepaymentValueMode
     */
    public function setPrepaymentValueMode(array $prepaymentValueMode)
    {
        $this->prepaymentValueMode = $prepaymentValueMode;
    }

    /**
     * Check if room offer is cancelable.
     * @return Boolean
     */
    public function isCancellable()
    {
        return $this->cancellable;
    }

    /**
     * Set room offer if cancelable.
     * @param Boolean $cancellable
     */
    public function setCancellable($cancellable)
    {
        $this->cancellable = $cancellable;
    }

    /**
     * Get room offer cancellation penalties
     * @return array
     */
    public function getCancellationPenalties()
    {
        return $this->cancellationPenalties;
    }

    /**
     * Set room offer cancellation penalties.
     * @param array $cancellationPenalties
     */
    public function setCancellationPenalties(array $cancellationPenalties)
    {
        $this->cancellationPenalties = $cancellationPenalties;
    }

    /**
     * Check if room offer is without cancellation penalties
     * @return Boolean
     */
    public function isWithoutCancellationPenaltiesData()
    {
        return (count($this->cancellationPenalties) == 0);
    }

    /**
     * Get room offer maximum room count.
     * @return Integer
     */
    public function getMaxRoomCount()
    {
        return $this->maxRoomCount;
    }

    /**
     * Set room offer maximum room count.
     * @param Integer $maxRoomCount
     */
    public function setMaxRoomCount($maxRoomCount)
    {
        $this->maxRoomCount = $maxRoomCount;
    }

    /**
     * Get room offer xml response from API.
     * @return String
     */
    public function getRoomOfferXml()
    {
        return $this->roomOfferXml;
    }

    /**
     * Set room offer xml response from API.
     * @param String $roomOfferXml
     */
    public function setRoomOfferXml($roomOfferXml)
    {
        $this->roomOfferXml = $roomOfferXml;
    }

    /**
     * Get room offer included tax and fees from API.
     * @return Array
     */
    public function getIncludedTaxAndFees()
    {
        return $this->includedTaxAndFees;
    }

    /**
     * Set room offer included tax and fees from API.
     * @param Array $includedTaxAndFees
     */
    public function setIncludedTaxAndFees(array $includedTaxAndFees)
    {
        $this->includedTaxAndFees = $includedTaxAndFees;
    }

    /**
     * Get room offer type
     * @return String
     */
    public function getRoomOfferType()
    {
        return $this->roomOfferType;
    }

    /**
     * Set room offer type i.e. 'Hot', 'Flex', 'Basic' offer
     * @param String $roomOfferType
     */
    public function setRoomOfferType($roomOfferType)
    {
        $this->roomOfferType = $roomOfferType;
    }

    /**
     * Get rooms left count
     * @return Int
     */
    public function getRoomsLeftCount()
    {
        return $this->roomsLeftCount;
    }

    /**
     * Set room left count
     * @param Int $roomsLeftCount
     */
    public function setRoomsLeftCount($roomsLeftCount)
    {
        $this->roomsLeftCount = $roomsLeftCount;
    }

    /**
     * Get room size.
     * @return Array
     */
    public function getRoomSize()
    {
        return $this->roomSize;
    }

    /**
     * Set room size
     * @param array $roomSize
     * @return $this
     */
    public function setRoomSize(array $roomSize)
    {
        $this->roomSize = $roomSize;
        return $this;
    }
}
