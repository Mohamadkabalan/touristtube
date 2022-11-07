<?php

namespace NewFlightBundle\Model;

class Destinations extends flightVO
{
	/**
     * @var $departureAirport
     */
    private $departureAirport;

    /**
     * @var $arrivalAirport
     */
    private $arrivalAirport;

    /**
     * @var $fromDate
     */
    private $fromDate;

    /**
     * @var $toDate
     */
    private $toDate;

    /**
     * The __construct
     */
    public function __construct()
    {

    }

    /**
     * Set Airport
     * @param Airport
     */
    public function setDepartureAirport(Airport $departureAirport)
    {
        $this->departureAirport = $departureAirport;
    }

    /**
     * Get Airport Model object
     * @return object
     */
    public function getDepartureAirport()
    {
        return $this->departureAirport;
    }

    /**
     * Set Airport
     * @param Airport
     */
    public function setArrivalAirport(Airport $arrivalAirport)
    {
        $this->arrivalAirport = $arrivalAirport;
    }

    /**
     * Get Airport
     * @return Airport
     */
    public function getArrivalAirport()
    {
        return $this->arrivalAirport;
    }

    /**
     * Get fromDate
     * @return fromDate
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set fromDate
     * @param Datetime $fromDate
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;
    }

    /**
     * Get toDate
     * @return toDate
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Set toDate
     * @param Datetime $toDate
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;
    }
}
