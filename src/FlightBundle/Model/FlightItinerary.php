<?php

namespace FlightBundle\Model;


/**
 * Flight Itenirary
 *
 * This class serves as getter and setter for Flight Itinerary
 */
class FlightItinerary {

    /**
     * @var tripType
     */
    protected $tripType;

    /**
     * @var segmentNumber
     */
    protected $segmentNumber;

    /**
     * @var flightItinerarySegment
     */
    public $flightItinerarySegment;

    /**
     * @var flightSegments
     */
    protected $flightSegments;

    /**
     * @var extra
     */
    protected $extra;

    /**
     * @var totalflightSegments
     */
    protected $totalflightSegments = 0;

    /**
     * @var baseFare
     */
    protected $baseFare;

    /**
     * @var taxes
     */
    protected $taxes;

    /**
     * @var amount
     */
    protected $amount;

    /**
     * @var currencyCode
     */
    protected $currencyCode;

    /**
     * @var decimalPlaces
     */
    protected $decimalPlaces;    

    /**
     * @var nonRefundable
     */
    protected $nonRefundable;    

    /**
     * @var seatsRemaining
     */
    protected $seatsRemaining;    

    /**
     * @var passengerInfos
     */
    protected $passengerInfos;  

    /**
     * @var fairInfos
     */
    protected $fairInfos;

    /**
     * @var penaltiesInfo
     */
    protected $penaltiesInfo;


    function __construct(){
        $this->flightSegments = array();
        $this->flightItinerarySegment = array();
        $this->passengerInfos = array();
        $this->fairInfos = array();
    }

    /**
     * Get segment number
     *
     * @return integer
     */
    public function getSegmentNumber() {
        return $this->segmentNumber;
    }

    /**
     * Set segment number
     *
     * @param integer $segmentNumber
     */
    public function setSegmentNumber($segmentNumber) {
        $this->segmentNumber = $segmentNumber;
    }

    /**
     * Set flightSegments
     *
     * @param object flightSegments
     */
    public function setFlightSegments($flightSegments) {
        $this->flightSegments[] = $flightSegments;
    }

    /**
     * Get flightSegments
     *
     * @return object flightSegments
     */
    public function getFlightSegments() {
        return $this->flightSegments;
    }

    /**
     * Set FlightItinerarySegment
     *
     * @param object FlightItinerarySegment
     */
    public function setFlightItinerarySegment(FlightItinerarySegment $flightItinerarySegment) {
        $this->flightItinerarySegment[] = $flightItinerarySegment;
        $this->totalflightSegments += 1;
    }

    /**
     * Get FlightItinerarySegment
     *
     * @return object FlightItinerarySegment
     */
    public function getFlightItinerarySegment() {
        return $this->flightItinerarySegment;
    }
    
    /**
     * Set tripType
     *
     * @param string $tripType oneway|return
     */
    public function setTripType($tripType) {
        $this->tripType = $tripType;
    }

    /**
     * Get tripType
     *
     * @return string oneway|return
     */
    public function getTripType() {
        return $this->tripType;
    }

    /**
     * Set extra
     *
     * @param mixed $extra 
     */
    public function setExtra($extra) {
        $this->extra = $extra;
    }

    /**
     * Get extra
     *
     * @return mixed $extra 
     */
    public function getExtra() {
        return $this->extra;
    }

    /**
     * Get Total flight Segments
     *
     * @return int $totalflightSegments 
     */
    public function getTotalflightSegments() {
        return $this->totalflightSegments;
    }

    /**
     * Set passengerInfos
     *
     * @param object FlightItineraryPassengerInfo
     */
    public function setPassengerInfo(FlightItineraryPassengerInfo $passengerInfo) {
        $this->passengerInfos[] = $passengerInfo;
    }

    /**
     * Get passengerInfos
     *
     * @return array of object FlightItineraryPassengerInfo
     */
    public function getPassengerInfo() {
        return $this->passengerInfos;
    }

    /**
     * Set baseFare
     *
     * @param string $baseFare
     */
    public function setBaseFare($baseFare) {
        $this->baseFare = $baseFare;
    }

    /**
     * Get baseFare
     *
     * @return string baseFare
     */
    public function getBaseFare() {
        return $this->baseFare;
    }

    /**
     * Set taxes
     *
     * @param string $taxes
     */
    public function setTaxes($taxes) {
        $this->taxes = $taxes;
    }

    /**
     * Get taxes
     *
     * @return string taxes
     */
    public function getTaxes() {
        return $this->taxes;
    }

    /**
     * Set amount
     *
     * @param string $amount
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }

    /**
     * Get amount
     *
     * @return string amount
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * Set currencyCode
     *
     * @param string $currencyCode
     */
    public function setCurrencyCode($currencyCode) {
        $this->currencyCode = $currencyCode;
    }

    /**
     * Get currencyCode
     *
     * @return string currencyCode
     */
    public function getCurrencyCode() {
        return $this->currencyCode;
    }

    /**
     * Set decimalPlaces
     *
     * @param string $decimalPlaces
     */
    public function setDecimalPlaces($decimalPlaces) {
        $this->decimalPlaces = $decimalPlaces;
    }

    /**
     * Get decimalPlaces
     *
     * @return string decimalPlaces
     */
    public function getDecimalPlaces() {
        return $this->decimalPlaces;
    }

    /**
     * Set nonRefundable
     *
     * @param string $nonRefundable
     */
    public function setNonRefundable($nonRefundable) {
        $this->nonRefundable = $nonRefundable;
    }

    /**
     * Get nonRefundable
     *
     * @return string nonRefundable
     */
    public function getNonRefundable() {
        return $this->nonRefundable;
    }

    /**
     * Set seatsRemaining
     *
     * @param string $seatsRemaining
     */
    public function setSeatsRemaining($seatsRemaining) {
        $this->seatsRemaining = $seatsRemaining;
    }

    /**
     * Get seatsRemaining
     *
     * @return string seatsRemaining
     */
    public function getSeatsRemaining() {
        return $this->seatsRemaining;
    }

    /**
     * Set fairInfos
     *
     * @param object FlightItineraryFairInfo
     */
    public function setFairInfo(FlightItineraryFairInfo $fairInfo) {
        $this->fairInfos[] = $fairInfo;
    }

    /**
     * Get fairInfos
     *
     * @return array of object FlightItineraryFairInfo
     */
    public function getFairInfo() {
        return $this->fairInfos;
    }

    /**
     * Set penaltiesInfo
     *
     */
    public function setPenaltiesInfo($penaltiesInfo) {
        $this->penaltiesInfo = $penaltiesInfo;
    }

    /**
     * Get penaltiesInfo
     *
     * @return array of object PenaltiesInfo
     */
    public function getPenaltiesInfo() {
        return $this->penaltiesInfo;
    }


}
