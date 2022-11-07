<?php

namespace NewFlightBundle\Model;

class FlightSegment extends flightVO
{
    /**
     * 
     */
    private $segmentNumber = '';

    /**
     * 
     */
    private $flightDeparture;

    /**
     * 
     */
    private $flightArrival;

    /**
     * 
     */
    private $marketingAirline;

    /**
     *
     */
    private $operatingAirline;

    /**
     * 
     */
    private $flightNumber = '';

    /**
     *
     */
    private $duration = '';

    /**
     * 
     */
    private $fareBasisCode = '';

    /**
     * 
     */
    private $cabinSelected = '';

    /**
     * 
     */
    private $refundable = '';

    /**
     * 
     */
    private $penalty = array();

    /**
     * 
     */
    private $baggageAllowance;

    /**
     * 
     */
    private $flightType;

    /**
     * 
     */
    private $resBookDesigCode;

    /**
     * @var FlightStops
     */
    private $flightStops;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->flightDeparture  = new FlightCriteria();
        $this->flightArrival    = new FlightCriteria();
        $this->marketingAirline = new Airline();
        $this->operatingAirline = new Airline();
        $this->Penalty          = new Penalty();
        $this->baggageAllowance = new PassengerInfoBaggage();
        $this->flightStops      = [];
    }

    /**
     * Get Flight Departure object
     * @return FlightDeparture object
     */
    public function getFlightDeparture()
    {
        return $this->flightDeparture;
    }

    /**
     * Get Flight Arrival object
     * @return FlightArrival object
     */
    public function getFlightArrival()
    {
        return $this->flightArrival;
    }

    /**
     * Get Marketing Airline object
     * @return Airline object
     */
    public function getMarketingAirline()
    {
        return $this->marketingAirline;
    }

    /**
     * Get Operating Airline object
     * @return Airline object
     */
    public function getOperatingAirline()
    {
        return $this->operatingAirline;
    }

    /**
     * Get Penalty object
     * @return Penalty object
     */
    public function getPenalty()
    {
        return $this->penalty;
    }

    /**
     * Get PassengerInfoBaggage object
     * @return PassengerInfoBaggage object
     */
    public function getBaggageAllowance()
    {
        return $this->baggageAllowance;
    }

    /**
     * Get segmentNumber
     * @return integer
     */
    public function getSegmentNumber()
    {
        return $this->segmentNumber;
    }

    /**
     * Get flightNumber
     * @return string
     */
    public function getFlightNumber()
    {
        return $this->flightNumber;
    }

    /**
     * Get duration
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Get fareBasisCode
     * @return string
     */
    public function getFareBasisCode()
    {
        return $this->fareBasisCode;
    }

    /**
     * Get cabinSelected
     * @return string
     */
    public function getCabinSelected()
    {
        return $this->cabinSelected;
    }

    /**
     * Get flightType
     * @return string
     */
    public function getFlightType()
    {
        return $this->flightType;
    }

    /**
     * Get resBookDesigCode
     * @return string
     */
    public function getResBookDesigCode()
    {
        return $this->resBookDesigCode;
    }

    /**
     * Get refundable
     * @return boolean
     */
    public function isRefundable()
    {
        return $this->refundable;
    }

    /**
     * Get resBookDesignCode
     * @return string
     */
    public function getResBookDesignCode()
    {
        return $this->resBookDesignCode;
    }

    /**
     * Get fareCalcLine
     * @return string
     */
    public function getFareCalcLine()
    {
        return $this->fareCalcLine;
    }

    /**
     * Get FlightStops object
     * @return FlightStops object
     */
    function getFlightStops()
    {
        return $this->flightStops;
    }

    /**
     * Set resBookDesignCode
     * @param string $resBookDesignCode
     */
    public function setResBookDesignCode($resBookDesignCode)
    {
        $this->resBookDesignCode = $resBookDesignCode;
    }

    /**
     * Set fareCalcLine
     * @param $fareCalcLine
     */
    public function setFareCalcLine($fareCalcLine)
    {
        $this->fareCalcLine = $fareCalcLine;
    }

    /**
     * Set isStop
     * @param $isStop
     */
    public function setIsStop($isStop)
    {
        $this->isStop = $isStop;
    }

    /**
     * Set segmentNumber
     * @param integer $segmentNumber
     */
    public function setSegmentNumber($segmentNumber)
    {
        $this->segmentNumber = $segmentNumber;
    }

    /**
     * Set flightNumber
     * @param string $flightNumber
     */
    public function setFlightNumber($flightNumber)
    {
        $this->flightNumber = $flightNumber;
    }

    /**
     * Set duration
     * @param integer $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * Set fareBasisCode
     * @param string $fareBasisCode
     */
    public function setFareBasisCode($fareBasisCode)
    {
        $this->fareBasisCode = $fareBasisCode;
    }

    /**
     * Set cabinSelected
     * @param string $cabinSelected
     */
    public function setCabinSelected($cabinSelected)
    {
        $this->cabinSelected = $cabinSelected;
    }

    /**
     * Set refundable
     * @param boolean $refundable
     */
    public function setRefundable($refundable)
    {
        $this->refundable = $refundable;
    }

    /**
     * Set flightType
     * @param boolean $flightType
     */
    public function setFlightType($flightType)
    {
        $this->flightType = $flightType;
    }

    /**
     * Set resBookDesigCode
     * @param boolean $resBookDesigCode
     */
    public function setResBookDesigCode($resBookDesigCode)
    {
        $this->resBookDesigCode = $resBookDesigCode;
    }

    /**
     * Set marketingAirline
     * @param Airline $marketingAirline
     */
    public function setMarketingAirline(Airline $marketingAirline)
    {
        $this->marketingAirline = $marketingAirline;
    }

    /**
     * Set operatingAirline
     * @param Airline $operatingAirline
     */
    public function setOperatingAirline(Airline $operatingAirline)
    {
        $this->operatingAirline = $operatingAirline;
    }

    /**
     * Set penalty
     * @param object $penalty
     */
    public function setPenalty($penalty)
    {
        $this->penalty = $penalty;
    }

    /**
     * Set baggageAllowance
     * @param object $baggageAllowance
     */
    public function setBaggageAllowance($baggageAllowance)
    {
        $this->baggageAllowance = $baggageAllowance;
    }

    /**
     * Set flightStops
     * @param $flightStops
     */
    function setFlightStops(FlightStops $flightStops)
    {
        $this->flightStops = $flightStops;
    }
}
