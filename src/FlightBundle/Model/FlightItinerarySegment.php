<?php

namespace FlightBundle\Model;


/**
 * FlightItinerarySegment
 *
 * This class serves as getter and setter for Flight Segment
 */
class FlightItinerarySegment {

	/**
     * @var originDestinationIndex
     */
    protected $originDestinationIndex;

    /**
     * @var flightDuration
     */
    protected $flightDuration;

    /**
     * @var flightSegmentIndex
     */
    protected $flightSegmentIndex;
    
    /**
     * @var flightDeparture
     */
    public $flightDeparture;

    /**
     * @var flightDeparture
     */
    public $flightArrival;
    
    /**
     * @var stopQuantity
     */
    protected $stopQuantity;
    
    /**
     * @var flightNumber
     */
    protected $flightNumber;
    
    /**
     * @var resBookDesigCode
     */
    protected $resBookDesigCode;
    
    /**
     * @var elapsedTime
     */
    protected $elapsedTime;
    

    /**
     * @var equipmentAirEquipType
     */
    protected $equipmentAirEquipType;
    
    /**
     * @var airline
     */
    public $airlineInfo;
    
    /**
     * @var marriageGrp
     */
    protected $marriageGrp;

    /**
     * @var TpaExtensions
     */
     public $tpaExtensions;

    /**
     * @var legacyArr
     */
    protected $legacyArr;

    /**
     * @var legacyArr
     */
    protected $fareBasisCode;

    function __construct(){

        return $this;
    }

    /**
     * Set Origin Destination Index
     *
     * @param string originDestinationIndex
     */
    public function setOriginDestinationIndex($originDestinationIndex) {
        $this->originDestinationIndex = $originDestinationIndex;
    }

    /**
     * Get Origin Destination Index
     *
     * @return string
     */
    public function getOriginDestinationIndex() {
        return $this->originDestinationIndex;
    }

    /**
     * Set Flight Duration
     *
     * @param string flightDuration
     */
    public function setFlightDuration($flightDuration) {
        $this->flightDuration = $flightDuration;
    }

    /**
     * Get FlightDuration
     *
     * @return string
     */
    public function getFlightDuration() {
        return $this->flightDuration;
    }

    /**
     * Set Flight Segment Index
     *
     * @param string flightSegmentIndex
     */
    public function setFlightSegmentIndex($flightSegmentIndex) {
        $this->flightSegmentIndex = $flightSegmentIndex;
    }

    /**
     * Get  Flight Segment Index
     *
     * @return string
     */
    public function getFlightSegmentIndex() {
        return $this->flightSegmentIndex;
    }
    /**
     * Set Stop Quantity
     *
     * @param string stopQuantity
     */
    public function setStopQuantity($stopQuantity) {
        $this->stopQuantity = $stopQuantity;
    }

    /**
     * Get Stop Quantity
     *
     * @return string
     */
    public function getStopQuantity() {
        return $this->stopQuantity;
    }

    /**
     * Set FlightNumber
     *
     * @param string flightNumber
     */
    public function setFlightNumber($flightNumber) {
        $this->flightNumber = $flightNumber;
    }

    /**
     * Get FlightNumber
     *
     * @return string
     */
    public function getFlightNumber() {
        return $this->flightNumber;
    }

    /**
     * Set ResBookDesigCode
     *
     * @param string resBookDesigCode
     */
    public function setResBookDesigCode($resBookDesigCode) {
        $this->resBookDesigCode = $resBookDesigCode;
    }

    /**
     * Get ResBookDesigCode
     *
     * @return string
     */
    public function getResBookDesigCode() {
        return $this->resBookDesigCode;
    }

    /**
     * Set ElapsedTime
     *
     * @param string elapsedTime
     */
    public function setElapsedTime($elapsedTime) {
        $this->elapsedTime = $elapsedTime;
    }

    /**
     * Get ElapsedTime
     *
     * @return string
     */
    public function getElapsedTime() {
        return $this->elapsedTime;
    }

    /**
     * Set Equipment Air EquipType
     *
     * @param string equipmentAirEquipType
     */
    public function setEquipmentAirEquipType($equipmentAirEquipType) {
        $this->equipmentAirEquipType = $equipmentAirEquipType;
    }

    /**
     * Get Equipment Air EquipType
     *
     * @return string equipmentAirEquipType
     */
    public function getEquipmentAirEquipType() {
        return $this->equipmentAirEquipType;
    }
    /**
     * Set Flight Departure
     *
     * @param Object FlightDeparture
     */
    public function setFlightDeparture(FlightDeparture $flightDeparture) {
        $this->flightDeparture = $flightDeparture;
    }
    /**
     * Get  Flight Departure
     *
     * @return Object FlightDeparture
     */
    public function getFlightDeparture() {
        return $this->flightDeparture;
    }
     /**
     * Set Flight Arrival
     *
     * @param Object FlightArrival
     */
    public function setFlightArrival(FlightArrival $flightArrival) {
        $this->flightArrival = $flightArrival;
    }

    /**
     * Get  Flight Departure
     *
     * @return Object FlightDeparture
     */
    public function getFlightArrival() {
        return $this->flightArrival;
    }
    /**
     * Set Airline Info
     *
     * @param Object airlineInfo
     */
    public function setAirlineInfo(AirlineInfo $airlineInfo) {
        $this->airlineInfo = $airlineInfo;
    }

    /**
     * Get  Airline Info
     *
     * @return Object airlineInfo
     */
    public function getAirlineInfo() {
        return $this->airlineInfo;
    }

    /**
     * Set Marriage Grp
     *
     * @param string marriageGrp
     */
    public function setMarriageGrp($marriageGrp) {
        $this->marriageGrp = $marriageGrp;
    }

    /**
     * Get Marriage Grp
     *
     * @return string marriageGrp
     */
    public function getMarriageGrp() {
        return $this->marriageGrp;
    }

    /**
     * Set Legacy array Structure
     *
     * @param string legacyArr
     */
    public function setLegacyArr($legacyArr) {
        $this->legacyArr = $legacyArr;
    }
    /**
     * Set TPA Extensions
     *
     * @param Object TPAExtensions
     */
    public function setTpaExtensions(TpaExtensions $tpaExtensions) {
        $this->tpaExtensions = $tpaExtensions;
    }
    /**
     * Get  TPA Extensions
     *
     * @return Object TPAExtensions
     */
    public function getTpaExtensions() {
        return $this->tpaExtensions;
    }
    /**
     * Get Legacy array Structure
     *
     * @return string legacyArr
     */
    public function getLegacyArr() {
        return $this->legacyArr;
    }

    /**
     * set fareBasisCode
     *
     * @return string fareBasisCode
     */
    public function setFareBasiscode($fareBasisCode) {
        return $this->fareBasisCode = $fareBasisCode;
    }

    /**
     * Get fareBasisCode
     *
     * @return string fareBasisCode
     */
    public function getFareBasisCode() {
        return $this->fareBasisCode;
    }
    
}
