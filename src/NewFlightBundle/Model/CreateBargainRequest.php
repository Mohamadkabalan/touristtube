<?php

namespace NewFlightBundle\Model;

class CreateBargainRequest extends flightVO
{
    /**
     * 
     */
    private $apiCredentailsModel;

    /**
     * 
     */
    private $originLocation;

    /**
     * 
     */
    private $destinationLocation;

    /**
     * @var array
     */
    private $destinations;

    /**
     * @var datetime
     */
    private $fromDate;

    /**
     * @var datetime
     */
    private $toDate;

    /**
     * @var string
     */
    private $cabinPref;

    /**
     * @var string
     */
    private $tripType;

    /**
     * @var string
     */
    private $flexibleDate;

    /**
     * @var integer
     */
    private $ADTCount;

    /**
     * @var integer
     */
    private $CNNCount;

    /**
     * @var integer
     */
    private $INFCount;

    /**
     * @var integer
     */
    private $chosenAirline;

    /**
     * @var integer
     */
    private $priority;

    /**
     * @var boolean
     */
    private $isMultiDestination;

    /**
     * @var array
     */
    private $flightDetailsSelectedSearchResult;

    /**
     * @var string
     */
    private $currencyCode;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->apiCredentailsModel = new APICredentails();
        $this->originLocation    = new Airport();
        $this->destinationLocation = new Airport();
        $this->destinations = [];
    }

    /**
     * Get APICredentails Model object
     * @return object
     */
    public function getApiCredentailsModel()
    {
        return $this->apiCredentailsModel;
    }

    /**
     * Get Airport Model object
     * @return object
     */
    public function getOriginLocation()
    {
        return $this->originLocation;
    }

    /**
     * Get Airport Model object
     * @return object
     */
    public function getDestinationLocation()
    {
        return $this->destinationLocation;
    }

    /**
     * Get Destinations Model object
     * @return object
     */
    public function getDestinations()
    {
        return $this->destinations;
    }

    /**
     * Get fromDate
     * @return datetime
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Get datetime
     * @return toDate
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Get cabinPref
     * @return String
     */
    public function getCabinPref()
    {
        return $this->cabinPref;
    }

    /**
     * Get tripType
     * @return String
     */
    public function getTripType()
    {
        return $this->tripType;
    }

    /**
     * Get flexibleDate
     * @return String
     */
    public function getFlexibleDate()
    {
        return $this->flexibleDate;
    }

    /**
     * Get ADTCount
     * @return integer
     */
    public function getADTCount()
    {
        return $this->ADTCount;
    }

    /**
     * Get CNNCount
     * @return integer
     */
    public function getCNNCount()
    {
        return $this->CNNCount;
    }

    /**
     * Get INFCount
     * @return integer
     */
    public function getINFCount()
    {
        return $this->INFCount;
    }

    /**
     * Get chosenAirline
     * @return String
     */
    public function getChosenAirline()
    {
        return $this->chosenAirline;
    }

    /**
     * Get priority
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Get currencyCode
     * @return String
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Get isMultidestination
     * @return boolean
     */
    public function isMultiDestination()
    {
        return $this->isMultiDestination;
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
     * Set toDate
     * @param Datetime $toDate
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;
    }

    /**
     * Set cabinPref
     * @param String $cabinPref
     */
    public function setCabinPref($cabinPref)
    {
        $this->cabinPref = $cabinPref;
    }

    /**
     * Set tripType
     * @param String $tripType
     */
    public function setTripType($tripType)
    {
        $this->tripType = $tripType;
    }

    /**
     * Set flexibleDate
     * @param String $flexibleDate
     */
    public function setFlexibleDate($flexibleDate)
    {
        $this->flexibleDate = $flexibleDate;
    }

    /**
     * Set ADTCount
     * @param Integer $ADTCount
     */
    public function setADTCount($ADTCount)
    {
        $this->ADTCount = $ADTCount;
    }

    /**
     * Set CNNCount
     * @param Integer $CNNCount
     */
    public function setCNNCount($CNNCount)
    {
        $this->CNNCount = $CNNCount;
    }

    /**
     * Set INFCount
     * @param Integer $INFCount
     */
    public function setINFCount($INFCount)
    {
        $this->INFCount = $INFCount;
    }

    /**
     * Set chosenAirline
     * @param String $chosenAirline
     */
    public function setChosenAirline($chosenAirline)
    {
        $this->chosenAirline = $chosenAirline;
    }

    /**
     * Set priority
     * @param integer $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * Set Destinations
     * @param array Destinations
     */
    public function setDestinations(Destinations $destinations)
    {
        $this->destinations[] = $destinations;
    }

    /**
     * Set isMultidestination
     * @param boolean $isMultiDestination
     */
    public function setIsMultiDestination($isMultiDestination)
    {
        $this->isMultiDestination = $isMultiDestination;
    }

    /**
     * Set flightDetailsSelectedSearchResult
     * @param array $flightDetailsSelectedSearchResult
     */
    public function setFlightDetailsSelectedSearchResult(FlightDetails $flightDetailsSelectedSearchResult)
    {
        $this->flightDetailsSelectedSearchResult = $flightDetailsSelectedSearchResult;
    }

    /**
     * Set currencyCode
     * @param String $currencyCode
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * Set originLocation
     * @param String $originLocation
     */
    public function setOriginLocation(Airport $originLocation)
    {
        $this->originLocation = $originLocation;
    }

    /**
     * Set destinationLocation
     * @param String $destinationLocation
     */
    public function setDestinationLocation(Airport $destinationLocation)
    {
        $this->destinationLocation = $destinationLocation;
    }

    /**
     * This method handles request from the form POST request,
     * As we don't want to change the structure of the old form, as it may requires very thorough changes
     * from the frontend (template, js) and so on.
     * This will normalize the POST request so that this Class could handle and match the data required
     *
     * This Support chaining effect
     * @param $params
     * @return CreateBargainRequest
     */
    function normalizePostRequest($params){

        $this->getOriginLocation()->setCode($params['departureairportC'][0]);
        $this->getDestinationLocation()->setCode($params['arrivalairportC'][0]);
        $this->setFromDate($params['fromDate'][0] . "T00:00:00");
        $this->setToDate($params['toDate'][0] . "T23:59:59");

        $this->setCabinPref($params['cabinselect']);
        $this->setFlexibleDate($params['flexibledate']);

        $this->setADTCount($params['adultsselect']);
        $this->setCNNCount($params['childsselect']);
        $this->setINFCount($params['infantsselect']);

        if ($params['oneway'] == 1) {
            $this->setTripType('OneWay');
        }
        else {
            $this->setTripType('Return');
        }

        return $this;
    }
}
