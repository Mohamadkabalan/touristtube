<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FlightBundle\Model;

/**
 * Description of Airline
 *
 * @author para-soft7
 */

class AirlineInfo
{

    /**
     * @var operatingAirlineCode
     */
    protected $operatingAirlineCode;

    /**
     * @var operatingAirlineFlightNumber
     */
    protected $operatingAirlineFlightNumber;
     /**
     * @var marketingAirlineCode
     */
    protected $marketingAirlineCode;

        /**
     * Set Operating Airline Code
     *
     * @param string operatingAirlineCode
     */
    public function setOperatingAirlineCode($operatingAirlineCode) {
        $this->operatingAirlineCode = $operatingAirlineCode;
    }

    /**
     * Get Operating Airline Code
     *
     * @return string
     */
    public function getOperatingAirlineCode() {
        return $this->operatingAirlineCode;
    }

    /**
     * Set Operating Airline FlightNumber
     *
     * @param string operatingAirlineFlightNumber
     */
    public function setOperatingAirlineFlightNumber($operatingAirlineFlightNumber) {
        $this->operatingAirlineFlightNumber = $operatingAirlineFlightNumber;
    }

    /**
     * Get Operating Airline FlightNumber
     *
     * @return string
     */
    public function getOperatingAirlineFlightNumber() {
        return $this->operatingAirlineFlightNumber;
    }
        /**
     * Set Marketing Airline Code
     *
     * @param string marketingAirlineCode
     */
    public function setMarketingAirlineCode($marketingAirlineCode) {
        $this->marketingAirlineCode = $marketingAirlineCode;
    }

    /**
     * Get Marketing Airline Code
     *
     * @return string marketingAirlineCode
     */
    public function getMarketingAirlineCode() {
        return $this->marketingAirlineCode;
    }
}