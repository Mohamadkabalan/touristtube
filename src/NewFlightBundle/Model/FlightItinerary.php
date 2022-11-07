<?php

namespace NewFlightBundle\Model;

class FlightItinerary extends flightVO
{
    /**
     * @var string
     */
    private $flightType = '';

    /**
     * 
     */
    private $flightSegment;

    /**
     * 
     */
    private $totalSegment = '';

    /**
     * 
     */
    private $totalDuration = '';

    /**
     * 
     */
    private $currency;

    /**
     * @var string
     */
    private $outbound = '';

    /**
     * @var string
     */
    private $inbound = '';

    /**
     * @var array of FlightStops
     */
    private $flightStops;

    /**
     * @var FlightItineraryPricingInfo
     */
    private $flightItineraryPricingInfo;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->flightSegment = new FlightSegment();
        $this->currency = new Currency();
        $this->flightStops = [];
        $this->flightItineraryPricingInfo = new FlightItineraryPricingInfo();
    }

    /**
     * Get Flight Segment object
     * @return FlightSegment object
     */
    function getFlightSegment()
    {
        return $this->flightSegment;
    }

    /**
     * Get Currency object
     * @return Currency object
     */
    function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get flightType
     * @return string
     */
    function getFlightType()
    {
        return $this->flightType;
    }

    /**
     * Get totalSegment
     * @return integer
     */
    function getTotalSegment()
    {
        return $this->totalSegment;
    }

    /**
     * Get totalDuration
     * @return integer
     */
    function getTotalDuration()
    {
        return $this->totalDuration;
    }

    /**
     * Get outbound
     * @return string
     */
    function getOutbound()
    {
        return $this->outbound;
    }

    /**
     * Get inbound
     * @return string
     */
    function getInbound()
    {
        return $this->inbound;
    }

    /**
     * Get array of FlightStops object
     * @return array of FlightStops object
     */
    function getFlightStops()
    {
        return $this->flightStops;
    }

    /**
     * Get FlightItineraryPricingInfo
     * @return FlightItineraryPricingInfo object
     */
    function getFlightItineraryPricingInfo()
    {
        return $this->flightItineraryPricingInfo;
    }

    /**
     * Set flightSegment
     * @param FlightSegment $flightSegment
     */
    function setFlightSegment($flightSegment)
    {
        $this->flightSegment = $flightSegment;
    }

    /**
     * Set currency
     * @param Currency $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Set flightType
     * @param string $flightType
     */
    function setFlightType($flightType)
    {
        $this->flightType = $flightType;
    }

    /**
     * Set totalSegment
     * @param integer $totalSegment
     */
    function setTotalSegment($totalSegment)
    {
        $this->totalSegment = $totalSegment;
    }

    /**
     * Set totalDuration
     * @param integer $totalDuration
     */
    function setTotalDuration($totalDuration)
    {
        $this->totalDuration = $totalDuration;
    }

    /**
     * Set outbound
     * @param string $outbound
     */
    function setOutbound($outbound)
    {
        $this->outbound = $outbound;
    }

    /**
     * Set inbound
     * @param string $inbound
     */
    function setInbound($inbound)
    {
        $this->inbound = $inbound;
    }

    /**
     * Set flightStops
     * @param $flightStops
     */
    function setFlightStops($flightStops)
    {
        $this->flightStops = $flightStops;
    }

    /**
     * Set FlightItineraryPricingInfo $flightItineraryPricingInfo
     */
    function setFlightItineraryPricingInfo(FlightItineraryPricingInfo $flightItineraryPricingInfo)
    {
        $this->flightItineraryPricingInfo = $flightItineraryPricingInfo;
    }
}
