<?php

namespace DealBundle\Model;

/**
 * DealResponse is the main or parent class that contains the main deals responses objects
 * with attributes for all response objects derived from other classes we need in deals section and also the deals object keys
 * that are specific in deal details for each deal
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealResponse
{
    private $productCode             = '';
    private $dealCountry             = '';
    private $dealCountyCode          = '';
    private $dealCity                = '';
    private $dealRating              = 0;
    private $dealHighlights          = '';
    private $dealName                = '';
    private $dealDescription         = '';
    private $dealDuration            = '';
    private $latitude                = '';
    private $longitude               = '';
    private $inclusion               = '';
    private $termsAndCondition       = '';
    private $changePolicy            = '';
    private $directions              = array();
    private $dealSchedules           = array();
    private $startingPlace           = array();
    private $notes                   = array();
    private $faq                     = array();
    private $dealBooking             = array();
    private $cancellationPolicy      = array();
    private $dealBookingCancellation = array();
    private $priceOptions            = array();
    private $reviews                 = array();
    private $transferCountries       = array();
    private $transferCities          = array();
    private $transferAirports        = array();
    private $transferVehicles        = array();
    private $transferBooking         = array();
    private $quote                   = array();
    private $mandatoryFields         = array();
    private $errorCode               = '';
    private $errorMessage            = '';
    private $amountFBC               = 0;
    private $amountSBC               = 0;
    private $amountACCurrency        = '';

    /**
     * Get productCode
     * @return String
     */
    function getProductCode()
    {
        return $this->productCode;
    }

    /**
     * Get dealCountry
     * @return String
     */
    function getDealCountry()
    {
        return $this->dealCountry;
    }

    /**
     * Get dealCountyCode
     * @return String
     */
    function getDealCountyCode()
    {
        return $this->dealCountyCode;
    }

    /**
     * Get dealCity
     * @return String
     */
    function getDealCity()
    {
        return $this->dealCity;
    }

    /**
     * Get dealRating
     * @return Integer
     */
    function getDealRating()
    {
        return $this->dealRating;
    }

    /**
     * Get dealHighlights
     * @return String
     */
    function getDealHighlights()
    {
        return $this->dealHighlights;
    }

    /**
     * Get dealName
     * @return String
     */
    function getDealName()
    {
        return $this->dealName;
    }

    /**
     * Get dealDescription
     * @return String
     */
    function getDealDescription()
    {
        return $this->dealDescription;
    }

    /**
     * Get dealDuration
     * @return String
     */
    function getDealDuration()
    {
        return $this->dealDuration;
    }

    /**
     * Get latitude
     * @return String
     */
    function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Get longitude
     * @return String
     */
    function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Get inclusion
     * @return String
     */
    function getInclusion()
    {
        return $this->inclusion;
    }

    /**
     * Get termsAndCondition
     * @return String
     */
    function getTermsAndCondition()
    {
        return $this->termsAndCondition;
    }

    /**
     * Get changePolicy
     * @return String
     */
    function getChangePolicy()
    {
        return $this->changePolicy;
    }

    /**
     * Get directions
     * @return Array
     */
    function getDirections()
    {
        return $this->directions;
    }

    /**
     * Get dealSchedules
     * @return Array
     */
    function getDealSchedules()
    {
        return $this->dealSchedules;
    }

    /**
     * Get startingPlace
     * @return Array
     */
    function getStartingPlace()
    {
        return $this->startingPlace;
    }

    /**
     * Get notes
     * @return Array
     */
    function getNotes()
    {
        return $this->notes;
    }

    /**
     * Get faq
     * @return Array
     */
    function getFaq()
    {
        return $this->faq;
    }

    /**
     * Get dealBooking
     * @return Array
     */
    function getDealBooking()
    {
        return $this->dealBooking;
    }

    /**
     * Get cancellationPolicy
     * @return Array
     */
    function getCancellationPolicy()
    {
        return $this->cancellationPolicy;
    }

    /**
     * Get dealBookingCancellation
     * @return Array
     */
    function getDealBookingCancellation()
    {
        return $this->dealBookingCancellation;
    }

    /**
     * Get priceOptions
     * @return Array
     */
    function getPriceOptions()
    {
        return $this->priceOptions;
    }

    /**
     * Get reviews
     * @return Array
     */
    function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Get transferCountries
     * @return Array
     */
    function getTransferCountries()
    {
        return $this->transferCountries;
    }

    /**
     * Get transferCities
     * @return Array
     */
    function getTransferCities()
    {
        return $this->transferCities;
    }

    /**
     * Get transferAirports
     * @return Array
     */
    function getTransferAirports()
    {
        return $this->transferAirports;
    }

    /**
     * Get transferVehicles
     * @return Array
     */
    function getTransferVehicles()
    {
        return $this->transferVehicles;
    }

    /**
     * Get transferBooking
     * @return Array
     */
    function getTransferBooking()
    {
        return $this->transferBooking;
    }

    /**
     * Get quote
     * @return Array
     */
    function getQuote()
    {
        return $this->quote;
    }

    /**
     * Get mandatoryFields
     * @return Array
     */
    function getMandatoryFields()
    {
        return $this->mandatoryFields;
    }

    /**
     * Get errorCode
     * @return String
     */
    function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Get errorMessage
     * @return String
     */
    function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Get amountFBC
     * @return Integer
     */
    function getAmountFBC()
    {
        return $this->amountFBC;
    }

    /**
     * Get amountSBC
     * @return Integer
     */
    function getAmountSBC()
    {
        return $this->amountSBC;
    }

    /**
     * Get amountACCurrency
     * @return String
     */
    function getAmountACCurrency()
    {
        return $this->amountACCurrency;
    }

    /**
     * Set productCode
     * @param String $productCode
     */
    function setProductCode($productCode)
    {
        $this->productCode = $productCode;
    }

    /**
     * Set dealCountry
     * @param String $dealCountry
     */
    function setDealCountry($dealCountry)
    {
        $this->dealCountry = $dealCountry;
    }

    /**
     * Set dealCountyCode
     * @param String $dealCountyCode
     */
    function setDealCountyCode($dealCountyCode)
    {
        $this->dealCountyCode = $dealCountyCode;
    }

    /**
     * Set dealCity
     * @param String $dealCity
     */
    function setDealCity($dealCity)
    {
        $this->dealCity = $dealCity;
    }

    /**
     * Set dealRating
     * @param Integer $dealRating
     */
    function setDealRating($dealRating)
    {
        $this->dealRating = $dealRating;
    }

    /**
     * Set dealHighlights
     * @param String $dealHighlights
     */
    function setDealHighlights($dealHighlights)
    {
        $this->dealHighlights = $dealHighlights;
    }

    /**
     * Set dealName
     * @param String $dealName
     */
    function setDealName($dealName)
    {
        $this->dealName = $dealName;
    }

    /**
     * Set dealDescription
     * @param String $dealDescription
     */
    function setDealDescription($dealDescription)
    {
        $this->dealDescription = $dealDescription;
    }

    /**
     * Set dealDuration
     * @param String $dealDuration
     */
    function setDealDuration($dealDuration)
    {
        $this->dealDuration = $dealDuration;
    }

    /**
     * Set latitude
     * @param String $latitude
     */
    function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Set longitude
     * @param String $longitude
     */
    function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Set inclusion
     * @param String $inclusion
     */
    function setInclusion($inclusion)
    {
        $this->inclusion = $inclusion;
    }

    /**
     * Set termsAndCondition
     * @param String $termsAndCondition
     */
    function setTermsAndCondition($termsAndCondition)
    {
        $this->termsAndCondition = $termsAndCondition;
    }

    /**
     * Set changePolicy
     * @param String $changePolicy
     */
    function setChangePolicy($changePolicy)
    {
        $this->changePolicy = $changePolicy;
    }

    /**
     * Set directions
     * @param Array $directions
     */
    function setDirections(array $directions)
    {
        $this->directions = $directions;
    }

    /**
     * Set dealSchedules
     * @param Array $dealSchedules
     */
    function setDealSchedules(array $dealSchedules)
    {
        $this->dealSchedules = $dealSchedules;
    }

    /**
     * Set startingPlace
     * @param Array $startingPlace
     */
    function setStartingPlace(array $startingPlace)
    {
        $this->startingPlace = $startingPlace;
    }

    /**
     * Set notes
     * @param Array $notes
     */
    function setNotes(array $notes)
    {
        $this->notes = $notes;
    }

    /**
     * Set faq
     * @param Array $faq
     */
    function setFaq(array $faq)
    {
        $this->faq = $faq;
    }

    /**
     * Set dealBooking
     * @param Array $dealBooking
     */
    function setDealBooking(array $dealBooking)
    {
        $this->dealBooking = $dealBooking;
    }

    /**
     * Set cancellationPolicy
     * @param Array $cancellationPolicy
     */
    function setCancellationPolicy(array $cancellationPolicy)
    {
        $this->cancellationPolicy = $cancellationPolicy;
    }

    /**
     * Set dealBookingCancellation
     * @param Array $dealBookingCancellation
     */
    function setDealBookingCancellation(array $dealBookingCancellation)
    {
        $this->dealBookingCancellation = $dealBookingCancellation;
    }

    /**
     * Set priceOptions
     * @param Array $priceOptions
     */
    function setPriceOptions(array $priceOptions)
    {
        $this->priceOptions = $priceOptions;
    }

    /**
     * Set reviews
     * @param Array $reviews
     */
    function setReviews(array $reviews)
    {
        $this->reviews = $reviews;
    }

    /**
     * Set transferCountries
     * @param Array $transferCountries
     */
    function setTransferCountries(array $transferCountries)
    {
        $this->transferCountries = $transferCountries;
    }

    /**
     * Set transferCities
     * @param Array $transferCities
     */
    function setTransferCities(array $transferCities)
    {
        $this->transferCities = $transferCities;
    }

    /**
     * Set transferAirports
     * @param Array $transferAirports
     */
    function setTransferAirports(array $transferAirports)
    {
        $this->transferAirports = $transferAirports;
    }

    /**
     * Set transferVehicles
     * @param Array $transferVehicles
     */
    function setTransferVehicles(array $transferVehicles)
    {
        $this->transferVehicles = $transferVehicles;
    }

    /**
     * Set transferBooking
     * @param Array $transferBooking
     */
    function setTransferBooking(array $transferBooking)
    {
        $this->transferBooking = $transferBooking;
    }

    /**
     * Set quote
     * @param Array $quote
     */
    function setQuote(array $quote)
    {
        $this->quote = $quote;
    }

    /**
     * Set mandatoryFields
     * @param Array $mandatoryFields
     */
    function setMandatoryFields(array $mandatoryFields)
    {
        $this->mandatoryFields = $mandatoryFields;
    }

    /**
     * Set errorCode
     * @param String $errorCode
     */
    function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * Set errorMessage
     * @param String $errorMessage
     */
    function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * Set amountFBC
     * @param Integer $amountFBC
     */
    function setAmountFBC($amountFBC)
    {
        $this->amountFBC = $amountFBC;
    }

    /**
     * Set amountSBC
     * @param Integer $amountSBC
     */
    function setAmountSBC($amountSBC)
    {
        $this->amountSBC = $amountSBC;
    }

    /**
     * Set amountACCurrency
     * @param String $amountACCurrency
     */
    function setAmountACCurrency($amountACCurrency)
    {
        $this->amountACCurrency = $amountACCurrency;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $value) {
            $toreturn[$key] = $value;
        }
        return $toreturn;
    }
}