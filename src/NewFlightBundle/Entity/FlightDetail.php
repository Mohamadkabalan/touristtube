<?php

namespace NewFlightBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FlightDetail
 * 
 * @ORM\Entity
 * @ORM\Table(name="flight_detail")
 */
class FlightDetail {

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
     * @ORM\Column(name="pnr_id", type="string", length=11, nullable=false)
     */
    private $pnrId;

    /**
     * @Assert\NotBlank()
     * @var integer
     *
     * @ORM\Column(name="segment_number", type="integer", nullable=false)
     */
    private $segmentNumber;

    /**
     * @Assert\NotBlank()
     * @var string
     *
     * @ORM\Column(name="departure_airport", type="string", length=3, nullable=false)
     */
    private $departureAirport;

    /**
     * @Assert\NotBlank()
     * @var string
     *
     * @ORM\Column(name="arrival_airport", type="string", length=3, nullable=false)
     */
    private $arrivalAirport;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @var \DateTime
     *
     * @ORM\Column(name="departure_datetime", type="datetime", nullable=false)
     */
    private $departureDateTime;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @var \DateTime
     *
     * @ORM\Column(name="arrival_datetime", type="datetime", nullable=false)
     */
    private $arrivalDateTime;

    /**
     * @Assert\NotBlank()
     * @var string
     *
     * @ORM\Column(name="airline", type="string", length=3, nullable=false)
     */
    private $airline;
    
    /**
     * @Assert\NotBlank()
     * @var string
     *
     * @ORM\Column(name="operating_airline", type="string", length=3, nullable=false)
     */
    private $operatingAirline;

    /**
     * @Assert\NotBlank()
     * @var string
     *
     * @ORM\Column(name="flight_number", type="string", length=30, nullable=false)
     */
    private $flightNumber;

    /**
     * @Assert\NotBlank()
     * @var string
     *
     * @ORM\Column(name="cabin", type="string", length=1, nullable=false)
     */
    private $cabin;

    /**
     * @var string
     *
     * @ORM\Column(name="flight_duration", type="string", length=30)
     */
    private $flightDuration;

    /**
     * @var boolean
     *
     * @ORM\Column(name="stop_indicator", type="boolean")
     */
    private $stopIndicator;

    /**
     * @var string
     *
     * @ORM\Column(name="res_book_design_code", type="string", length=3)
     */
    private $resBookDesignCode;

    /**
     * @var string
     *
     * @ORM\Column(name="stop_duration", type="string", length=30)
     */
    private $stopDuration;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=30)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="PassengerNameRecord", inversedBy="flightDetails")
     * @ORM\JoinColumn(name="pnr_id", referencedColumnName="id")
     */
    private $passengerNameRecord;

    /**
     * @var string
     *
     * @ORM\Column(name="fare_calc_line", type="string", length=1000, nullable=true)
     */
    private $fareCalcLine;

    /**
     * @var string
     *
     * @ORM\Column(name="leaving_baggage_info", type="string", length=100, nullable=true)
     */
    private $leavingBaggageInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="returning_baggage_info", type="string", length=100, nullable=true)
     */
    private $returningBaggageInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="departure_terminal_id", type="string", length=24, nullable=true)
     */
    private $departureTerminalId;

    /**
     * @var string
     *
     * @ORM\Column(name="arrival_terminal_id", type="string", length=24, nullable=true)
     */
    private $arrivalTerminalId;

    /**
     * @var string
     *
     * @ORM\Column(name="fare_basis_code", type="string", length=30, nullable=true)
     */
    private $fareBasisCode;

    /**
     * @var string
     *
     * @ORM\Column(name="airline_pnr", type="string", length=11, nullable=true)
     */
    private $airlinePnr;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get passenger name record ID
     *
     * @return string
     */
    public function getPnrId() {
        return $this->pnrId;
    }

    /**
     * Set passenger name record ID
     *
     * @param string $pnrId
     */
    public function setPnrId($pnrId) {
        $this->pnrId = $pnrId;
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
     * Get departure airport
     *
     * @return string
     */
    public function getDepartureAirport() {
        return $this->departureAirport;
    }

    /**
     * Set departure airport
     *
     * @param string $departureAirport
     */
    public function setDepartureAirport($departureAirport) {
        $this->departureAirport = $departureAirport;
    }

    /**
     * Get arrival airport
     *
     * @return string
     */
    public function getArrivalAirport() {
        return $this->arrivalAirport;
    }

    /**
     * Set arrival airport
     *
     * @param string $arrivalAirport
     */
    public function setArrivalAirport($arrivalAirport) {
        $this->arrivalAirport = $arrivalAirport;
    }

    /**
     * Get departure date time
     *
     * @return datetime
     */
    public function getDepartureDateTime() {
        return $this->departureDateTime;
    }

    /**
     * Set departure date time
     *
     * @param string $departureDateTime
     */
    public function setDepartureDateTime($departureDateTime) {
        $this->departureDateTime = new \DateTime($departureDateTime);
    }

    /**
     * Get arrival date time
     *
     * @return datetime
     */
    public function getArrivalDateTime() {
        return $this->arrivalDateTime;
    }

    /**
     * Set arrival date time
     *
     * @param string $arrivalDateTime
     */
    public function setArrivalDateTime($arrivalDateTime) {
        $this->arrivalDateTime = new \DateTime($arrivalDateTime);
    }

    /**
     * Get the airline
     *
     * @return string
     */
    public function getAirline() {
        return $this->airline;
    }

    /**
     * Set airline
     *
     * @param string $airline
     */
    public function setAirline($airline) {
        $this->airline = $airline;
    }

    /**
     * Get operating airline
     *
     * @return string
     */
    public function getOperatingAirline() {
        return $this->operatingAirline;
    }

    /**
     * Set operating airline
     *
     * @param string $operatingAirline
     */
    public function setOperatingAirline($operatingAirline) {
        $this->operatingAirline = $operatingAirline;
    }
    
    /**
     * Get flight number
     *
     * @return string
     */
    public function getFlightNumber() {
        return $this->flightNumber;
    }

    /**
     * Set flight number
     *
     * @param string $flightNumber
     */
    public function setFlightNumber($flightNumber) {
        $this->flightNumber = $flightNumber;
    }

    /**
     * Get cabin
     *
     * @return string
     */
    function getCabin() {
        return $this->cabin;
    }

    /**
     * Set cabin
     *
     * @param string $cabin
     */
    function setCabin($cabin) {
        $this->cabin = $cabin;
    }

    /**
     * Get flight duration
     *
     * @return string
     */
    function getFlightDuration() {
        return $this->flightDuration;
    }

    /**
     * Set flight duration
     *
     * @param string $flightDuration
     */
    function setFlightDuration($flightDuration) {
        $this->flightDuration = $flightDuration;
    }

    /**
     * Get stop indicator
     *
     * @return string
     */
    function getStopIndicator() {
        return $this->stopIndicator;
    }

    /**
     * Set stop indicator
     *
     * @param string $stopIndicator
     */
    function setStopIndicator($stopIndicator) {
        $this->stopIndicator = $stopIndicator;
    }

    /**
     * Is stop duration
     *
     * @return boolean
     */
    function isStopDuration() {
        return $this->stopDuration;
    }

    /**
     * Set stop duration
     *
     * @param boolean $stopDuration
     */
    function setStopDuration($stopDuration) {
        $this->stopDuration = $stopDuration;
    }

    /**
     * Get type
     *
     * @return string
     */
    function getType() {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    function setType($type) {
        $this->type = $type;
    }

    /**
     * Get passenger Name Record
     *
     * @return PassengerNameRecord
     */
    public function getPassengerNameRecord() {
        return $this->passengerNameRecord;
    }

    /**
     * Set passenger name record
     *
     * @param PassengerNameRecord $passengerNameRecord
     */
    public function setPassengerNameRecord(PassengerNameRecord $passengerNameRecord) {
        $this->passengerNameRecord = $passengerNameRecord;
    }
     /**
     * get ResBookDesignCode
     *
     * @param ResBookDesignCode $resBookDesignCode
     */
    
    function getResBookDesignCode()
    {
        return $this->resBookDesignCode;
    }
     /**
     * set ResBookDesignCode
     *
     * @param ResBookDesignCode $resBookDesignCode
     */
    function setResBookDesignCode($resBookDesignCode)
    {
        $this->resBookDesignCode = $resBookDesignCode;
    }

    /**
     * Get fare Calc Line
     *
     * @return string
     */
    public function getFareCalcLine() {
        return $this->fareCalcLine;
    }

    /**
     * Set fare Calc Line
     *
     * @param string $fareCalcLine
     */
    public function setFareCalcLine($fareCalcLine) {
        $this->fareCalcLine = $fareCalcLine;
    }

    /**
     * Get Leaving Baggage Info
     *
     * @return string
     */
    public function getLeavingBaggageInfo() {
        return $this->leavingBaggageInfo;
    }

    /**
     * Set Leaving Baggage Info
     *
     * @param string $leavingBaggageInfo
     */
    public function setLeavingBaggageInfo($leavingBaggageInfo) {
        $this->leavingBaggageInfo = $leavingBaggageInfo;
    }

    /**
     * Get Returning Baggage Info
     *
     * @return string
     */
    public function getReturningBaggageInfo() {
        return $this->returningBaggageInfo;
    }

    /**
     * Set Returning Baggage Info
     *
     * @param string $returningBaggageInfo
     */
    public function setReturningBaggageInfo($returningBaggageInfo) {
        $this->returningBaggageInfo = $returningBaggageInfo;
    }

    /**
     * Get departure Terminal Id
     *
     * @return string
     */
    public function getDepartureTerminalId() {
        return $this->departureTerminalId;
    }

    /**
     * Set departure Terminal Id
     *
     * @param string $departureTerminalId
     */
    public function setDepartureTerminalId($departureTerminalId) {
        $this->departureTerminalId = $departureTerminalId;
    }

    /**
     * Get arrival Terminal Id
     *
     * @return string
     */
    public function getArrivalTerminalId() {
        return $this->arrivalTerminalId;
    }

    /**
     * Set arrival Terminal Id
     *
     * @param string $arrivalTerminalId
     */
    public function setArrivalTerminalId($arrivalTerminalId) {
        $this->arrivalTerminalId = $arrivalTerminalId;
    }

    /**
     * Get Fare Basis Code
     *
     * @return string
     */
    public function getFareBasisCode() {
        return $this->fareBasisCode;
    }

    /**
     * Set Fare Basis Code
     *
     * @param string $fareBasisCode
     */
    public function setFareBasisCode($fareBasisCode) {
        $this->fareBasisCode = $fareBasisCode;
    }

    /**
     * Get Airline PNR
     *
     * @return string
     */
    public function getAirlinePnr() {
        return $this->airlinePnr;
    }

    /**
     * Set Airline PNR
     *
     * @param string $airlinePnr
     */
    public function setAirlinePnr($airlinePnr) {
        $this->airlinePnr = $airlinePnr;
    }
}