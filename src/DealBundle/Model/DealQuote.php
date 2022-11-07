<?php

namespace DealBundle\Model;

/**
 * DealQuote contains the attributes for quote.
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealQuote
{
    /**
     * @var string
     */
    private $quoteKey = '';

    /**
     * @var string
     */
    private $total = 00.00;

    /**
     * @var integer
     */
    private $timeId = 0;

    /**
     * @var string
     */
    private $time = '';

    /**
     * @var array
     */
    private $units = array();

    /**
     * @var integer
     */
    private $activityPriceId = 0;

    /**
     * @var integer
     */
    private $priceId = 0;

    /**
     * @var string
     */
    private $quote = '';

    /**
     * @var string
     */
    private $mandatoryFields = '';

    /**
     * 
     */
    private $commonSC;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->commonSC = new DealsCommonSC();
    }

    /**
     * Get Common search criteria object
     * @return DealsCommonSC object
     */
    function getCommonSC()
    {
        return $this->commonSC;
    }

    /**
     * Get quoteKey
     * @return String
     */
    function getQuoteKey()
    {
        return $this->quoteKey;
    }

    /**
     * Get total
     * @return String
     */
    function getTotal()
    {
        return $this->total;
    }

    /**
     * Get timeId
     * @return integer
     */
    function getTimeId()
    {
        return $this->timeId;
    }

    /**
     * Get time
     * @return String
     */
    function getTime()
    {
        return $this->time;
    }

    /**
     * Get units
     * @return Array
     */
    function getUnits()
    {
        return $this->units;
    }

    /**
     * Get activityPriceId
     * @return integer
     */
    function getActivityPriceId()
    {
        return $this->activityPriceId;
    }

    /**
     * Get priceId
     * @return integer
     */
    function getPriceId()
    {
        return $this->priceId;
    }

    /**
     * Get quote
     * @return string
     */
    function getQuote()
    {
        return $this->quote;
    }

    /**
     * Get mandatoryFields
     * @return string
     */
    function getMandatoryFields()
    {
        return $this->mandatoryFields;
    }

    /**
     * Set quoteKey
     * @param String $quoteKey
     */
    function setQuoteKey($quoteKey)
    {
        $this->quoteKey = $quoteKey;
    }

    /**
     * Set total
     * @param String $total
     */
    function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * Set timeId
     * @param integer $timeId
     */
    function setTimeId($timeId)
    {
        $this->timeId = $timeId;
    }

    /**
     * Set time
     * @param String $time
     */
    function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Set units
     * @param Array $units
     */
    function setUnits($units)
    {
        $this->units = $units;
    }

    /**
     * Set activityPriceId
     * @param integer $activityPriceId
     */
    function setActivityPriceId($activityPriceId)
    {
        $this->activityPriceId = $activityPriceId;
    }

    /**
     * Set priceId
     * @param integer $priceId
     */
    function setPriceId($priceId)
    {
        $this->priceId = $priceId;
    }

    /**
     * Set quote
     * @param String $quote
     */
    function setQuote($quote)
    {
        $this->quote = $quote;
    }

    /**
     * Set mandatoryFields
     * @param String $mandatoryFields
     */
    function setMandatoryFields($mandatoryFields)
    {
        $this->mandatoryFields = $mandatoryFields;
    }
}