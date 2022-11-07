<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FlightBundle\Model;

/**
 * Description of FlightArrival
 *
 * @author para-soft7
 */
class FlightArrival
{
    /**
     * @var arrivalDateTime
     */
    protected $arrivalDateTime;

    /**
     * @var arrivalAirportLocationCode
     */
    protected $arrivalAirportLocationCode;

    /**
     * @var arrivalAirportTerminalId
     */
    protected $arrivalAirportTerminalId;

    /**
     * @var arrivalTimeZoneGmtOffset
     */
    protected $arrivalTimeZoneGmtOffset;



    /**
     * Set Arrival DateTime
     *
     * @param string arrivalDateTime
     */
    public function setArrivalDateTime($arrivalDateTime) {
        $this->arrivalDateTime = $arrivalDateTime;
    }

    /**
     * Get Arrival DateTime
     *
     * @return string
     */
    public function getArrivalDateTime() {
        return $this->arrivalDateTime;
    }

    /**
     * Set Arrival Airport Location Code
     *
     * @param string arrivalAirportLocationCode
     */
    public function setArrivalAirportLocationCode($arrivalAirportLocationCode) {
        $this->arrivalAirportLocationCode = $arrivalAirportLocationCode;
    }

    /**
     * Get Arrival Airport Location Code
     *
     * @return string
     */
    public function getArrivalAirportLocationCode() {
        return $this->arrivalAirportLocationCode;
    }

    /**
     * Set Arrival Airport TerminalId
     *
     * @param string arrivalAirportTerminalId
     */
    public function setArrivalAirportTerminalId($arrivalAirportTerminalId) {
        $this->arrivalAirportTerminalId = $arrivalAirportTerminalId;
    }

    /**
     * Get Arrival Airport TerminalId
     *
     * @return string
     */
    public function getArrivalAirportTerminalId() {
        return $this->arrivalAirportTerminalId;
    }

    /**
     * Set Arrival TimeZone Gmt Offset
     *
     * @param string arrivalTimeZoneGmtOffset
     */
    public function setArrivalTimeZoneGmtOffset($arrivalTimeZoneGmtOffset) {
        $this->arrivalTimeZoneGmtOffset = $arrivalTimeZoneGmtOffset;
    }

    /**
     * Get Arrival TimeZone Gmt Offset
     *
     * @return string
     */
    public function getArrivalTimeZoneGmtOffset() {
        return $this->arrivalTimeZoneGmtOffset;
    }
}