<?php

namespace DealBundle\Model;

/**
 *  DealArrivalDeparture is a centralized object class for several attributes related to arrival and departure
 * these attributes were used in several classes and then moved to here to be localized in one object to be called from
 * those classes
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealArrivalDeparture
{
    /**
     * @var date
     */
    private $arrivalPickupDate = '';

    /**
     * @var string
     */
    private $arrivalPickupTime = '';

    /**
     * @var date
     */
    private $arrivalFlightDate = '';

    /**
     * @var string
     */
    private $arrivalFlightTime = '';

    /**
     * @var string
     */
    private $arrivalAirportPortTrain = '';

    /**
     * @var string
     */
    private $arrivalPassenger = '';

    /**
     * @var date
     */
    private $arrivalDate = '';

    /**
     * @var string
     */
    private $arrivalTime = '';

    /**
     * @var string
     */
    private $arrivalMinute = '';

    /**
     * @var string
     */
    private $arrivalHour = '';

    /**
     * @var string
     */
    private $arrivalFlightDetails = '';

    /**
     * @var string
     */
    private $arrivalFrom = '';

    /**
     * @var integer
     */
    private $arrivalPriceId = '';

    /**
     * @var string
     */
    private $arrivalDestinationAddress = '';

    /**
     * @var string
     */
    private $arrivalCompany = '';

    /**
     * @var date
     */
    private $departurePickupDate = '';

    /**
     * @var string
     */
    private $departurePickupTime = '';

    /**
     * @var date
     */
    private $departureFlightDate = '';

    /**
     * @var string
     */
    private $departureFlightTime = '';

    /**
     * @var string
     */
    private $departAirportPortTrain = '';

    /**
     * @var string
     */
    private $departPassenger = '';

    /**
     * @var date
     */
    private $departureDate = '';

    /**
     * @var string
     */
    private $departureTime = '';

    /**
     * @var string
     */
    private $departureMinute = '';

    /**
     * @var string
     */
    private $departureHour = '';

    /**
     * @var string
     */
    private $departureFlightDetails = '';

    /**
     * @var string
     */
    private $departTo = '';

    /**
     * @var string
     */
    private $departurePickupAddress = '';

    /**
     * @var string
     */
    private $departureDestinationAddress = '';

    /**
     * @var integer
     */
    private $departurePriceId = '';

    /**
     * @var string
     */
    private $departureCompany = '';

    /**
     * Get arrivalPickupDate
     * @return String
     */
    function getArrivalPickupDate()
    {
        return $this->arrivalPickupDate;
    }

    /**
     * Get arrivalPickupTime
     * @return String
     */
    function getArrivalPickupTime()
    {
        return $this->arrivalPickupTime;
    }

    /**
     * Get arrivalFlightDate
     * @return String
     */
    function getArrivalFlightDate()
    {
        return $this->arrivalFlightDate;
    }

    /**
     * Get arrivalFlightTime
     * @return String
     */
    function getArrivalFlightTime()
    {
        return $this->arrivalFlightTime;
    }

    /**
     * Get departurePickupDate
     * @return String
     */
    function getDeparturePickupDate()
    {
        return $this->departurePickupDate;
    }

    /**
     * Get departurePickupTime
     * @return String
     */
    function getDeparturePickupTime()
    {
        return $this->departurePickupTime;
    }

    /**
     * Get departureFlightDate
     * @return String
     */
    function getDepartureFlightDate()
    {
        return $this->departureFlightDate;
    }

    /**
     * Get departureFlightTime
     * @return String
     */
    function getDepartureFlightTime()
    {
        return $this->departureFlightTime;
    }

    /**
     * Get arrivalAirportPortTrain
     * @return String
     */
    function getArrivalAirportPortTrain()
    {
        return $this->arrivalAirportPortTrain;
    }

    /**
     * Get arrivalPassenger
     * @return String
     */
    function getArrivalPassenger()
    {
        return $this->arrivalPassenger;
    }

    /**
     * Get arrivalDate
     * @return String
     */
    function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    /**
     * Get arrivalTime
     * @return String
     */
    function getArrivalTime()
    {
        return $this->arrivalTime;
    }

    /**
     * Get arrivalMinute
     * @return String
     */
    function getArrivalMinute()
    {
        return $this->arrivalMinute;
    }

    /**
     * Get arrivalHour
     * @return String
     */
    function getArrivalHour()
    {
        return $this->arrivalHour;
    }

    /**
     * Get arrivalFlightDetails
     * @return String
     */
    function getArrivalFlightDetails()
    {
        return $this->arrivalFlightDetails;
    }

    /**
     * Get arrivalFrom
     * @return String
     */
    function getArrivalFrom()
    {
        return $this->arrivalFrom;
    }

    /**
     * Get arrivalDestinationAddress
     * @return String
     */
    function getArrivalDestinationAddress()
    {
        return $this->arrivalDestinationAddress;
    }

    /**
     * Get departAirportPortTrain
     * @return String
     */
    function getDepartAirportPortTrain()
    {
        return $this->departAirportPortTrain;
    }

    /**
     * Get departPassenger
     * @return String
     */
    function getDepartPassenger()
    {
        return $this->departPassenger;
    }

    /**
     * Get departureDate
     * @return String
     */
    function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * Get departureTime
     * @return String
     */
    function getDepartureTime()
    {
        return $this->departureTime;
    }

    /**
     * Get departureMinute
     * @return String
     */
    function getDepartureMinute()
    {
        return $this->departureMinute;
    }

    /**
     * Get departureHour
     * @return String
     */
    function getDepartureHour()
    {
        return $this->departureHour;
    }

    /**
     * Get departureFlightDetails
     * @return String
     */
    function getDepartureFlightDetails()
    {
        return $this->departureFlightDetails;
    }

    /**
     * Get departTo
     * @return String
     */
    function getDepartTo()
    {
        return $this->departTo;
    }

    /**
     * Get departurePickupAddress
     * @return String
     */
    function getDeparturePickupAddress()
    {
        return $this->departurePickupAddress;
    }

    /**
     * Get arrivalPriceId
     * @return integer
     */
    function getArrivalPriceId()
    {
        return $this->arrivalPriceId;
    }

    /**
     * Get departurePriceId
     * @return integer
     */
    function getDeparturePriceId()
    {
        return $this->departurePriceId;
    }

    /**
     * Get departureDestinationAddress
     * @return String
     */
    function getDepartureDestinationAddress()
    {
        return $this->departureDestinationAddress;
    }

    /**
     * Set arrivalCompany
     * @param String $arrivalCompany
     */
    function getArrivalCompany()
    {
        return $this->arrivalCompany;
    }

    /**
     * Set departureCompany
     * @param String $departureCompany
     */
    function getDepartureCompany()
    {
        return $this->departureCompany;
    }

    /**
     * Set arrivalPickupDate
     * @param String $arrivalPickupDate
     */
    function setArrivalPickupDate($arrivalPickupDate)
    {
        $this->arrivalPickupDate = $arrivalPickupDate;
    }

    /**
     * Set arrivalPickupTime
     * @param String $arrivalPickupTime
     */
    function setArrivalPickupTime($arrivalPickupTime)
    {
        $this->arrivalPickupTime = $arrivalPickupTime;
    }

    /**
     * Set arrivalFlightDate
     * @param String $arrivalFlightDate
     */
    function setArrivalFlightDate($arrivalFlightDate)
    {
        $this->arrivalFlightDate = $arrivalFlightDate;
    }

    /**
     * Set arrivalFlightTime
     * @param String $arrivalFlightTime
     */
    function setArrivalFlightTime($arrivalFlightTime)
    {
        $this->arrivalFlightTime = $arrivalFlightTime;
    }

    /**
     * Set departurePickupDate
     * @param String $departurePickupDate
     */
    function setDeparturePickupDate($departurePickupDate)
    {
        $this->departurePickupDate = $departurePickupDate;
    }

    /**
     * Set departurePickupTime
     * @param String $departurePickupTime
     */
    function setDeparturePickupTime($departurePickupTime)
    {
        $this->departurePickupTime = $departurePickupTime;
    }

    /**
     * Set departureFlightDate
     * @param String $departureFlightDate
     */
    function setDepartureFlightDate($departureFlightDate)
    {
        $this->departureFlightDate = $departureFlightDate;
    }

    /**
     * Set departureFlightTime
     * @param String $departureFlightTime
     */
    function setDepartureFlightTime($departureFlightTime)
    {
        $this->departureFlightTime = $departureFlightTime;
    }

    /**
     * Set arrivalAirportPortTrain
     * @param String $arrivalAirportPortTrain
     */
    function setArrivalAirportPortTrain($arrivalAirportPortTrain)
    {
        $this->arrivalAirportPortTrain = $arrivalAirportPortTrain;
    }

    /**
     * Set arrivalPassenger
     * @param String $arrivalPassenger
     */
    function setArrivalPassenger($arrivalPassenger)
    {
        $this->arrivalPassenger = $arrivalPassenger;
    }

    /**
     * Set arrivalDate
     * @param String $arrivalDate
     */
    function setArrivalDate($arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;
    }

    /**
     * Set arrivalTime
     * @param String $arrivalTime
     */
    function setArrivalTime($arrivalTime)
    {
        $this->arrivalTime = $arrivalTime;
    }

    /**
     * Set arrivalFlightDetails
     * @param String $arrivalFlightDetails
     */
    function setArrivalFlightDetails($arrivalFlightDetails)
    {
        $this->arrivalFlightDetails = $arrivalFlightDetails;
    }

    /**
     * Set arrivalFrom
     * @param String $arrivalFrom
     */
    function setArrivalFrom($arrivalFrom)
    {
        $this->arrivalFrom = $arrivalFrom;
    }

    /**
     * Set arrivalDestinationAddress
     * @param String $arrivalDestinationAddress
     */
    function setArrivalDestinationAddress($arrivalDestinationAddress)
    {
        $this->arrivalDestinationAddress = $arrivalDestinationAddress;
    }

    /**
     * Set departAirportPortTrain
     * @param String $departAirportPortTrain
     */
    function setDepartAirportPortTrain($departAirportPortTrain)
    {
        $this->departAirportPortTrain = $departAirportPortTrain;
    }

    /**
     * Set departPassenger
     * @param String $departPassenger
     */
    function setDepartPassenger($departPassenger)
    {
        $this->departPassenger = $departPassenger;
    }

    /**
     * Set departureDate
     * @param String $departureDate
     */
    function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;
    }

    /**
     * Set departureTime
     * @param String $departureTime
     */
    function setDepartureTime($departureTime)
    {
        $this->departureTime = $departureTime;
    }

    /**
     * Set departureFlightDetails
     * @param String $departureFlightDetails
     */
    function setDepartureFlightDetails($departureFlightDetails)
    {
        $this->departureFlightDetails = $departureFlightDetails;
    }

    /**
     * Set departTo
     * @param String $departTo
     */
    function setDepartTo($departTo)
    {
        $this->departTo = $departTo;
    }

    /**
     * Set departurePickupAddress
     * @param String $departurePickupAddress
     */
    function setDeparturePickupAddress($departurePickupAddress)
    {
        $this->departurePickupAddress = $departurePickupAddress;
    }

    /**
     * Set arrivalMinute
     * @param String $arrivalMinute
     */
    function setArrivalMinute($arrivalMinute)
    {
        $this->arrivalMinute = $arrivalMinute;
    }

    /**
     * Set arrivalHour
     * @param String $arrivalHour
     */
    function setArrivalHour($arrivalHour)
    {
        $this->arrivalHour = $arrivalHour;
    }

    /**
     * Set departureMinute
     * @param String $departureMinute
     */
    function setDepartureMinute($departureMinute)
    {
        $this->departureMinute = $departureMinute;
    }

    /**
     * Set departureHour
     * @param String $departureHour
     */
    function setDepartureHour($departureHour)
    {
        $this->departureHour = $departureHour;
    }

    /**
     * Set arrivalPriceId
     * @param Integer $arrivalPriceId
     */
    function setArrivalPriceId($arrivalPriceId)
    {
        $this->arrivalPriceId = $arrivalPriceId;
    }

    /**
     * Set departurePriceId
     * @param Integer $departurePriceId
     */
    function setDeparturePriceId($departurePriceId)
    {
        $this->departurePriceId = $departurePriceId;
    }

    /**
     * Set departureDestinationAddress
     * @param String $departureDestinationAddress
     */
    function setDepartureDestinationAddress($departureDestinationAddress)
    {
        $this->departureDestinationAddress = $departureDestinationAddress;
    }

    /**
     * Set arrivalCompany
     * @param String $arrivalCompany
     */
    function setArrivalCompany($arrivalCompany)
    {
        $this->arrivalCompany = $arrivalCompany;
    }

    /**
     * Set departureCompany
     * @param String $departureCompany
     */
    function setDepartureCompany($departureCompany)
    {
        $this->departureCompany = $departureCompany;
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