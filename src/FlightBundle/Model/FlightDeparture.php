<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FlightBundle\Model;

/**
 * Description of FlightDeparture
 *
 * @author para-soft7
 */
class FlightDeparture
{

    /**
     * @var departureDateTime
     */
    protected $departureDateTime;

    /**
     * @var departureAirportLocationCode
     */
    protected $departureAirportLocationCode;

    /**
     * @var departureAirportTerminalId
     */
    protected $departureAirportTerminalId;

    /**
     * @var departureTimeZoneGmtOffset
     */
    protected $departureTimeZoneGmtOffset;

    /**
     * Set Departure DateTime
     *
     * @param string departureDateTime
     */
    public function setDepartureDateTime($departureDateTime) {
        $this->departureDateTime = $departureDateTime;
    }

    /**
     * Get  Departure DateTime
     *
     * @return string
     */
    public function getDepartureDateTime() {
        return $this->departureDateTime;
    }

    /**
     * Set Departure Airport Location Code
     *
     * @param string departureAirportLocationCode
     */
    public function setDepartureAirportLocationCode($departureAirportLocationCode) {
        $this->departureAirportLocationCode = $departureAirportLocationCode;
    }

    /**
     * Get  Departure Airport Location Code
     *
     * @return string
     */
    public function getDepartureAirportLocationCode() {
        return $this->departureAirportLocationCode;
    }

    /**
     * Set Departure AirportTerminal Id
     *
     * @param string departureAirportTerminalId
     */
    public function setDepartureAirportTerminalId($departureAirportTerminalId) {
        $this->departureAirportTerminalId = $departureAirportTerminalId;
    }

    /**
     * Get  Departure AirportTerminal Id
     *
     * @return string
     */
    public function getDepartureAirportTerminalId() {
        return $this->departureAirportTerminalId;
    }

    /**
     * Set Departure TimeZone Gmt Offset
     *
     * @param string departureTimeZoneGmtOffset
     */
    public function setDepartureTimeZoneGmtOffset($departureTimeZoneGmtOffset) {
        $this->departureTimeZoneGmtOffset = $departureTimeZoneGmtOffset;
    }

    /**
     * Get  Departure TimeZone Gmt Offset
     *
     * @return string
     */
    public function getDepartureTimeZoneGmtOffset() {
        return $this->departureTimeZoneGmtOffset;
    }
}