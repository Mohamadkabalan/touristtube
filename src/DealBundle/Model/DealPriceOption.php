<?php

namespace DealBundle\Model;


/**
 * DealPriceOptions contains the different price options for a specific tour.
 * This is used in Price & Options section of tourDetails.
 * We have an attribute $priceOptions in main class DealReponse for this.
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealPriceOption
{
    private $activityPriceId  = '';
    private $priceId          = '';
    private $priceDays        = array();
    private $optionLabel      = '';
    private $optionDateBegins = '';
    private $optionDateEnd    = '';
    private $units            = array();
    private $schedules        = array();
    private $priceOptions     = '';
    private $inclusions       = '';

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

    function getActivityPriceId()
    {
        return $this->activityPriceId;
    }

    function getPriceId()
    {
        return $this->priceId;
    }

    function getPriceDays()
    {
        return $this->priceDays;
    }

    function getOptionLabel()
    {
        return $this->optionLabel;
    }

    function getOptionDateBegins()
    {
        return $this->optionDateBegins;
    }

    function getOptionDateEnd()
    {
        return $this->optionDateEnd;
    }

    function getUnits()
    {
        return $this->units;
    }

    function getSchedules()
    {
        return $this->schedules;
    }

    function getPriceOptions()
    {
        return $this->priceOptions;
    }

    function getInclusions()
    {
        return $this->inclusions;
    }

    function setActivityPriceId($activityPriceId)
    {
        $this->activityPriceId = $activityPriceId;
    }

    function setPriceId($priceId)
    {
        $this->priceId = $priceId;
    }

    function setPriceDays($priceDays)
    {
        $this->priceDays = $priceDays;
    }

    function setOptionLabel($optionLabel)
    {
        $this->optionLabel = $optionLabel;
    }

    function setOptionDateBegins($optionDateBegins)
    {
        $this->optionDateBegins = $optionDateBegins;
    }

    function setOptionDateEnd($optionDateEnd)
    {
        $this->optionDateEnd = $optionDateEnd;
    }

    function setUnits($units)
    {
        $this->units = $units;
    }

    function setSchedules($schedules)
    {
        $this->schedules = $schedules;
    }

    function setPriceOptions($priceOptions)
    {
        $this->priceOptions = $priceOptions;
    }

    function setInclusions($inclusions)
    {
        $this->inclusions = $inclusions;
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