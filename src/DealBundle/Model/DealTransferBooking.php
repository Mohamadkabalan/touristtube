<?php

namespace DealBundle\Model;

/**
 * DealTransferBooking contains data of a booked transport.
 * I separated this in another class cause this has more attributes than DealBooking. In order for it not to be confusing.
 * We have an attribute for this in DealResponse called $transferBooking.
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealTransferBooking
{
    /**
     * 
     */
    private $bookingResponse;

    /**
     *
     */
    private $arrivalDeparture;

    /**
     * @var string
     */
    private $serviceType = '';

    /**
     * @var string
     */
    private $serviceCode = '';

    /**
     * @var array
     */
    private $dealArrivalDeparture = array();

    /**
     * @var string
     */
    private $creditCardTransactionType = '';

    /**
     * @var string
     */
    private $creditCardTransactionID = '';

    /**
     * @var string
     */
    private $goingTo = '';

    /**
     * @var string
     */
    private $ccBillingAddress = '';

    /**
     * @var integer
     */
    private $numOfpassengers = '';

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->bookingResponse  = new DealBookingResponse();
        $this->arrivalDeparture = new DealArrivalDeparture();
    }

    /**
     * Get bookingResponse
     * @return DealBookingResponse Object
     */
    function getBookingResponse()
    {
        return $this->bookingResponse;
    }

    /**
     * Get arrivalDeparture
     * @return DealArrivalDeparture Object
     */
    function getArrivalDeparture()
    {
        return $this->arrivalDeparture;
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
     * Get serviceCode
     * @return String
     */
    function getServiceCode()
    {
        return $this->serviceCode;
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
     * Get creditCardTransactionType
     * @return String
     */
    function getCreditCardTransactionType()
    {
        return $this->creditCardTransactionType;
    }

    /**
     * Get creditCardTransactionID
     * @return String
     */
    function getCreditCardTransactionID()
    {
        return $this->creditCardTransactionID;
    }

    /**
     * Get goingTo
     * @return string
     */
    function getGoingTo()
    {
        return $this->goingTo;
    }

    /**
     * Get ccBillingAddress
     * @return string
     */
    function getCcBillingAddress()
    {
        return $this->ccBillingAddress;
    }

    /**
     * Get numOfpassengers
     * @return integer
     */
    function getNumOfpassengers()
    {
        return $this->numOfpassengers;
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
     * Set serviceCode
     * @param String $serviceCode
     */
    function setServiceCode($serviceCode)
    {
        $this->serviceCode = $serviceCode;
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
     * Set creditCardTransactionType
     * @param String $creditCardTransactionType
     */
    function setCreditCardTransactionType($creditCardTransactionType)
    {
        $this->creditCardTransactionType = $creditCardTransactionType;
    }

    /**
     * Set creditCardTransactionID
     * @param String $creditCardTransactionID
     */
    function setCreditCardTransactionID($creditCardTransactionID)
    {
        $this->creditCardTransactionID = $creditCardTransactionID;
    }

    /**
     * Set goingTo
     * @param string $goingTo
     */
    function setGoingTo($goingTo)
    {
        $this->goingTo = $goingTo;
    }

    /**
     * Set ccBillingAddress
     * @param string $ccBillingAddress
     */
    function setCcBillingAddress($ccBillingAddress)
    {
        $this->ccBillingAddress = $ccBillingAddress;
    }

    /**
     * Set numOfpassengers
     * @param integer $numOfpassengers
     */
    function setNumOfpassengers($numOfpassengers)
    {
        $this->numOfpassengers = $numOfpassengers;
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