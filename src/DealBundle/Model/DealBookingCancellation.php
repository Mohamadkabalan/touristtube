<?php

namespace DealBundle\Model;

/**
 *  DealBookingCancellation is the class that will hold the cancellation object to be used in various classes
 *
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealBookingCancellation
{
    private $cancelBookingReference = '';
    private $cancelBookingStatus    = '';
    private $cancelBookingPrice     = '';
    private $cancelBookingDay       = '';
    private $cancelBookingDiscount  = '';
    private $amountFBC              = 0;
    private $amountSBC              = 0;
    private $amountACCurrency       = '';

    /**
     * Get cancelBookingReference
     * @return String
     */
    function getCancelBookingReference()
    {
        return $this->cancelBookingReference;
    }

    /**
     * Get cancelBookingStatus
     * @return String
     */
    function getCancelBookingStatus()
    {
        return $this->cancelBookingStatus;
    }

    /**
     * Get cancelBookingPrice
     * @return String
     */
    function getCancelBookingPrice()
    {
        return $this->cancelBookingPrice;
    }

    /**
     * Get cancelBookingDay
     * @return String
     */
    function getCancelBookingDay()
    {
        return $this->cancelBookingDay;
    }

    /**
     * Get cancelBookingDiscount
     * @return String
     */
    function getCancelBookingDiscount()
    {
        return $this->cancelBookingDiscount;
    }

    /**
     * Get amountFBC
     * @return Integer
     */
    function getAmountFBC()
    {
        return $this->amountFBC;
    }

    /**
     * Get amountSBC
     * @return Integer
     */
    function getAmountSBC()
    {
        return $this->amountSBC;
    }

    /**
     * Get amountACCurrency
     * @return String
     */
    function getAmountACCurrency()
    {
        return $this->amountACCurrency;
    }

    /**
     * Set cancelBookingReference
     * @param String $cancelBookingReference
     */
    function setCancelBookingReference($cancelBookingReference)
    {
        $this->cancelBookingReference = $cancelBookingReference;
    }

    /**
     * Set cancelBookingStatus
     * @param String $cancelBookingStatus
     */
    function setCancelBookingStatus($cancelBookingStatus)
    {
        $this->cancelBookingStatus = $cancelBookingStatus;
    }

    /**
     * Set cancelBookingPrice
     * @param String $cancelBookingPrice
     */
    function setCancelBookingPrice($cancelBookingPrice)
    {
        $this->cancelBookingPrice = $cancelBookingPrice;
    }

    /**
     * Set cancelBookingDay
     * @param String $cancelBookingDay
     */
    function setCancelBookingDay($cancelBookingDay)
    {
        $this->cancelBookingDay = $cancelBookingDay;
    }

    /**
     * Set cancelBookingDiscount
     * @param String $cancelBookingDiscount
     */
    function setCancelBookingDiscount($cancelBookingDiscount)
    {
        $this->cancelBookingDiscount = $cancelBookingDiscount;
    }

    /**
     * Set amountFBC
     * @param Integer $amountFBC
     */
    function setAmountFBC($amountFBC)
    {
        $this->amountFBC = $amountFBC;
    }

    /**
     * Set amountSBC
     * @param Integer $amountSBC
     */
    function setAmountSBC($amountSBC)
    {
        $this->amountSBC = $amountSBC;
    }

    /**
     * Set amountACCurrency
     * @param String $amountACCurrency
     */
    function setAmountACCurrency($amountACCurrency)
    {
        $this->amountACCurrency = $amountACCurrency;
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