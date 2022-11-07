<?php

namespace DealBundle\Model;

use TTBundle\Model\Country;
use TTBundle\Model\City;

/**
 * DealTransferVehiclesListing contains the list of vehicles available for transport.
 * This is based on the Country, City, Date, ServiceType parameters.
 * We have an attribute for this in DealResponse called $transferVehicles.
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealTransferVehiclesListing
{
    /**
     * @var string
     */
    private $serviceType = '';

    /**
     * @var integer
     */
    private $numOfPersons = '';

    /**
     * @var array
     */
    private $airport = array();

    /**
     * @var array
     */
    private $dealArrivalDeparture = array();

    /**
     * @var string
     */
    private $transferVehicle = '';

    /**
     * @var string
     */
    private $transferMinimumHourBooking = '';

    /**
     * @var string
     */
    private $transferPickupHour = '';

    /**
     * @var array
     */
    private $dealTransferAirportPrice = array();

    /**
     * @var string
     */
    private $checkAvailability = '';

    /**
     * @var string
     */
    private $selectedCurrency = '';

    /**
     * 
     */
    private $dealAirport;

    /**
     * 
     */
    private $typeOfTransfer;

    /**
     * 
     */
    private $arrivalDeparture;

    /**
     * 
     */
    private $country;

    /**
     * 
     */
    private $city;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->country          = new Country();
        $this->city             = new City();
        $this->typeOfTransfer   = new DealTransferType();
        $this->dealAirport      = new DealAirport();
        $this->arrivalDeparture = new DealArrivalDeparture();
    }

    /**
     * Get DealArrivalDeparture
     * @return DealArrivalDeparture object
     */
    function getArrivalDeparture()
    {
        return $this->arrivalDeparture;
    }

    /**
     * Get Country
     * @return Country object
     */
    function getCountry()
    {
        return $this->country;
    }

    /**
     * Get serviceType
     * @return String
     */
    function getServiceType()
    {
        return $this->serviceType;
    }

    /**
     * Get numOfPersons
     * @return String
     */
    function getNumOfPersons()
    {
        return $this->numOfPersons;
    }

    /**
     * Get airport
     * @return Array
     */
    function getAirport()
    {
        return $this->airport;
    }

    /**
     * Get dealArrivalDeparture
     * @return Array
     */
    function getDealArrivalDeparture()
    {
        return $this->dealArrivalDeparture;
    }

    /**
     * Get transferVehicle
     * @return String
     */
    function getTransferVehicle()
    {
        return $this->transferVehicle;
    }

    /**
     * Get transferMinimumHourBooking
     * @return String
     */
    function getTransferMinimumHourBooking()
    {
        return $this->transferMinimumHourBooking;
    }

    /**
     * Get transferPickupHour
     * @return String
     */
    function getTransferPickupHour()
    {
        return $this->transferPickupHour;
    }

    /**
     * Get dealTransferAirportPrice
     * @return Array
     */
    function getDealTransferAirportPrice()
    {
        return $this->dealTransferAirportPrice;
    }

    /**
     * Get City
     * @return City object
     */
    function getCity()
    {
        return $this->city;
    }

    /**
     * Get airportCode
     * @return Airport object
     */
    function getDealAirport()
    {
        return $this->dealAirport;
    }

    /**
     * Get typeOfTransfer
     * @return String
     */
    function getTypeOfTransfer()
    {
        return $this->typeOfTransfer;
    }

    /**
     * Get checkAvailability
     * @return String
     */
    function getCheckAvailability()
    {
        return $this->checkAvailability;
    }

    /**
     * Get selectedCurrency
     * @return String
     */
    function getSelectedCurrency()
    {
        return $this->selectedCurrency;
    }

    /**
     * Set serviceType
     * @param String $serviceType
     */
    function setServiceType($serviceType)
    {
        $this->serviceType = $serviceType;
    }

    /**
     * Set numOfPersons
     * @param String $numOfPersons
     */
    function setNumOfPersons($numOfPersons)
    {
        $this->numOfPersons = $numOfPersons;
    }

    /**
     * Set airport
     * @param Array $airport
     */
    function setAirport(array $airport)
    {
        $this->airport = $airport;
    }

    /**
     * Set dealArrivalDeparture
     * @param Array $dealArrivalDeparture
     */
    function setDealArrivalDeparture(array $dealArrivalDeparture)
    {
        $this->dealArrivalDeparture = $dealArrivalDeparture;
    }

    /**
     * Set transferVehicle
     * @param String $transferVehicle
     */
    function setTransferVehicle($transferVehicle)
    {
        $this->transferVehicle = $transferVehicle;
    }

    /**
     * Set transferMinimumHourBooking
     * @param String $transferMinimumHourBooking
     */
    function setTransferMinimumHourBooking($transferMinimumHourBooking)
    {
        $this->transferMinimumHourBooking = $transferMinimumHourBooking;
    }

    /**
     * Set transferPickupHour
     * @param String $transferPickupHour
     */
    function setTransferPickupHour($transferPickupHour)
    {
        $this->transferPickupHour = $transferPickupHour;
    }

    /**
     * Set dealTransferAirportPrice
     * @param Array $dealTransferAirportPrice
     */
    function setDealTransferAirportPrice(array $dealTransferAirportPrice)
    {
        $this->dealTransferAirportPrice = $dealTransferAirportPrice;
    }

    /**
     * Set checkAvailability
     * @param string $checkAvailability
     */
    function setCheckAvailability($checkAvailability)
    {
        $this->checkAvailability = $checkAvailability;
    }

    /**
     * Set selectedCurrency
     * @param string $selectedCurrency
     */
    function setSelectedCurrency($selectedCurrency)
    {
        $this->selectedCurrency = $selectedCurrency;
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