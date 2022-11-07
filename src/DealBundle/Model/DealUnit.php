<?php

namespace DealBundle\Model;

/**
 * DealPriceOptions contains the different units inside a price option
 * This is used in Price & Options section of tourDetails.
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealUnit
{
    private $unitId             = '';
    private $label              = '';
    private $minimum            = '';
    private $maximum            = '';
    private $capacityCount      = '';
    private $requiredOtherUnits = '';
    private $chargePrice        = '';
    private $netPrice           = '';
    private $currency           = '';
    private $quantity           = 0;

    function getUnitId()
    {
        return $this->unitId;
    }

    function getLabel()
    {
        return $this->label;
    }

    function getMinimum()
    {
        return $this->minimum;
    }

    function getMaximum()
    {
        return $this->maximum;
    }

    function getCapacityCount()
    {
        return $this->capacityCount;
    }

    function getRequiredOtherUnits()
    {
        return $this->requiredOtherUnits;
    }

    function getChargePrice()
    {
        return $this->chargePrice;
    }

    function getNetPrice()
    {
        return $this->netPrice;
    }

    function getCurrency()
    {
        return $this->currency;
    }

    function getQuantity()
    {
        return $this->quantity;
    }

    function setUnitId($unitId)
    {
        $this->unitId = $unitId;
    }

    function setLabel($label)
    {
        $this->label = $label;
    }

    function setMinimum($minimum)
    {
        $this->minimum = $minimum;
    }

    function setMaximum($maximum)
    {
        $this->maximum = $maximum;
    }

    function setCapacityCount($capacityCount)
    {
        $this->capacityCount = $capacityCount;
    }

    function setRequiredOtherUnits($requiredOtherUnits)
    {
        $this->requiredOtherUnits = $requiredOtherUnits;
    }

    function setChargePrice($chargePrice)
    {
        $this->chargePrice = $chargePrice;
    }

    function setNetPrice($netPrice)
    {
        $this->netPrice = $netPrice;
    }

    function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
}