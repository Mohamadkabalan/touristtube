<?php

namespace NewFlightBundle\Model;

class FlightIteneraryGrouped extends flightVO
{
    /**
     * 
     */
    private $flightItenerary = array();

    /**
     * @var string
     */
    private $flightType;

    /**
     * @var integer
     */
    private $totalSegment;

    /**
     * @var decimal
     */
    private $totalDuration;

    /**
     * @var decimal
     */
    private $price;

    /**
     * @var decimal
     */
    private $discountedPrice;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var decimal
     */
    private $displayedPrice;

    /**
     * @var string
     */
    private $displayedCurrency;

    /**
     * @var string
     */
    private $projectDomain;

    /**
     * @var string
     */
    private $subtopic1;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->flightItenerary = new FlightItinerary();
    }

    /**
     * Get FlightItenerary Model object
     * @return object
     */
    public function getFlightItenerary()
    {
        return $this->flightItenerary;
    }

    /**
     * Get flightType
     * @return String
     */
    public function getFlightType()
    {
        return $this->flightType;
    }

    /**
     * Get totalSegment
     * @return integer
     */
    public function getTotalSegment()
    {
        return $this->totalSegment;
    }

    /**
     * Get totalDuration
     * @return decimal
     */
    public function getTotalDuration()
    {
        return $this->totalDuration;
    }

    /**
     * Get totalPrice
     * @return decimal
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get discountedPrice
     * @return decimal
     */
    public function getDiscountedPrice()
    {
        return $this->discountedPrice;
    }

    /**
     * Get currency
     * @return String
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get displayedPrice
     * @return decimal
     */
    public function getDisplayedPrice()
    {
        return $this->displayedPrice;
    }

    /**
     * Get displayedCurrency
     * @return String
     */
    public function getDisplayedCurrency()
    {
        return $this->displayedCurrency;
    }

    /**
     * Get projectDomain
     * @return String
     */
    public function getProjectDomain()
    {
        return $this->projectDomain;
    }

    /**
     * Get subtopic1
     * @return String
     */
    public function getSubtopic1()
    {
        return $this->subtopic1;
    }

    /**
     * Set flightType
     * @param String $flightType
     */
    public function setFlightType($flightType)
    {
        $this->flightType = $flightType;
    }

    /**
     * Set totalSegment
     * @param integer $totalSegment
     */
    public function setTotalSegment($totalSegment)
    {
        $this->totalSegment = $totalSegment;
    }

    /**
     * Set totalDuration
     * @param decimal $totalDuration
     */
    public function setTotalDuration($totalDuration)
    {
        $this->totalDuration = $totalDuration;
    }

    /**
     * Set totalPrice
     * @param decimal $totalPrice
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Set discountedPrice
     * @param decimal $discountedPrice
     */
    public function setDiscountedPrice($discountedPrice)
    {
        $this->discountedPrice = $discountedPrice;
    }

    /**
     * Set currency
     * @param String $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Set displayedPrice
     * @param decimal $displayedPrice
     */
    public function setDisplayedPrice($displayedPrice)
    {
        $this->displayedPrice = $displayedPrice;
    }

    /**
     * Set displayedCurrency
     * @param String $displayedCurrency
     */
    public function setDisplayedCurrency($displayedCurrency)
    {
        $this->displayedCurrency = $displayedCurrency;
    }

    /**
     * Set projectDomain
     * @param String $projectDomain
     */
    public function setProjectDomain($projectDomain)
    {
        $this->projectDomain = $projectDomain;
    }

    /**
     * Set subtopic1
     * @param String $subtopic1
     */
    public function setSubtopic1($subtopic1)
    {
        $this->subtopic1 = $subtopic1;
    }

    /**
     * Set flightItenerary
     * @param object $flightItenerary
     */
    public function setFlightItenerary($flightItenerary)
    {
        $this->flightItenerary = $flightItenerary;
    }
}
