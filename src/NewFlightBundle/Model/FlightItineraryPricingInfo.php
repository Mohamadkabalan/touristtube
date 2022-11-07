<?php

namespace NewFlightBundle\Model;

class FlightItineraryPricingInfo extends flightVO
{
    /**
     * @var float
     */
    private $baseFare;

    /**
     * @var float
     */
    private $fareConstruction;

    /**
     * @var float
     */
    private $equivFare;

    /**
     * @var float
     */
    private $baseTaxes;

    /**
     * @var float
     */
    private $taxes;

    /**
     * @var float
     */
    private $totalFare;

    /**
     * @var float
     */
    private $discount;

    /**
     * @var float
     */
    private $price;

    /**
     * Set baseFare
     * @param decimal $baseFare
     */
    function setBaseFare($baseFare)
    {
        $this->baseFare = $baseFare;
    }

    /**
     * Get baseFare
     * @return float BaseFare
     */
    function getBaseFare()
    {
        return $this->baseFare;
    }

    /**
     * Set fareConstruction
     * @param decimal $fareConstruction
     */
    function setFareConstruction($fareConstruction)
    {
        $this->fareConstruction = $fareConstruction;
    }

    /**
     * Get fareConstruction
     * @return float fareConstruction
     */
    function getFareConstruction()
    {
        return $this->fareConstruction;
    }

    /**
     * Set equivFare
     * @param decimal $equivFare
     */
    function setEquivFare($equivFare)
    {
        $this->equivFare = $equivFare;
    }

    /**
     * Get equivFare
     * @return float equivFare
     */
    function getEquivFare()
    {
        return $this->equivFare;
    }

    /**
     * Set taxes
     * @param decimal $taxes
     */
    function setTaxes($taxes)
    {
        $this->taxes = $taxes;
    }

    /**
     * Get taxes
     * @return float taxes
     */
    function getTaxes()
    {
        return $this->taxes;
    }

    /**
     * Set discount
     * @param decimal $discount
     */
    function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * Get discount
     * @return float discount
     */
    function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set price
     * @param decimal $price
     */
    function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     * @return float price
     */
    function getPrice()
    {
        return $this->price;
    }

    /**
     * Set totalFare
     * @param decimal $totalFare
     */
    function setTotalFare($totalFare)
    {
        $this->totalFare = $totalFare;
    }

    /**
     * Get totalFare
     * @return float totalFare
     */
    function getTotalFare()
    {
        return $this->totalFare;
    }

    /**
     * Set baseTaxes
     * @param decimal $baseTaxes
     */
    function setBaseTaxes($baseTaxes)
    {
        $this->baseTaxes = $baseTaxes;
    }

    /**
     * Get baseTaxes
     * @return float baseTaxes
     */
    function getBaseTaxes()
    {
        return $this->baseTaxes;
    }
}
