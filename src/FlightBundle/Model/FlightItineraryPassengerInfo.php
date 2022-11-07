<?php

namespace FlightBundle\Model;


/**
 * Flight Itinerary Passenger Info
 *
 * This class serves as getter and setter for Flight Itinerary Passenger Info
 */
class FlightItineraryPassengerInfo {

    /**
     * @var code
     */
    protected $code;

    /**
     * @var quantity
     */
    protected $quantity;

    /**
     * @var fareCalculationLine
     */
    public $fareCalculationLine;

    /**
     * @var baggage
     */
    protected $baggage;

    function __construct(){
        $this->baggage = array();
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code) {
        $this->code = $code;
    }

    /**
     * Set baggage
     *
     * @param object baggage
     */
    public function setBaggage(FlightItineraryPassengerInfoBaggage $baggage) {
        $this->baggage[] = $baggage;
    }

    /**
     * Get baggage
     *
     * @return object baggage
     */
    public function getBaggage() {
        return $this->baggage;
    }

    /**
     * Set quantity
     *
     * @param string $quantity
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    /**
     * Get quantity
     *
     * @return string quantity
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * Set fareCalculationLine
     *
     * @param mixed $fareCalculationLine 
     */
    public function setFareCalculationLine($fareCalculationLine) {
        $this->fareCalculationLine = $fareCalculationLine;
    }

    /**
     * Get fareCalculationLine
     *
     * @return mixed $fareCalculationLine 
     */
    public function getFareCalculationLine() {
        return $this->fareCalculationLine;
    }
    
}
