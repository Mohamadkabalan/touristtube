<?php

namespace FlightBundle\Model;


/**
 * Flight Itinerary Passenger Info Baggage
 *
 * This class serves as getter and setter for Flight Itinerary Passenger Info Baggage
 */
class FlightItineraryPassengerInfoBaggage {

    /**
     * @var weight
     */
    protected $weight;

    /**
     * @var unit
     */
    protected $unit;

    /**
     * @var pieces
     */
    protected $pieces;

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight() {
        return $this->weight;
    }

    /**
     * Set weight
     *
     * @param string $weight
     */
    public function setWeight($weight) {
        $this->weight = $weight;
    }

    /**
     * Set unit
     *
     * @param string $unit
     */
    public function setUnit($unit) {
        $this->unit = $unit;
    }

    /**
     * Get unit
     *
     * @return string unit
     */
    public function getUnit() {
        return $this->unit;
    }

    /**
     * Get pieces
     *
     * @return string
     */
    public function getPieces() {
        return $this->pieces;
    }

    /**
     * Set pieces
     *
     * @param string $weight
     */
    public function setPieces($pieces) {
        $this->pieces = $pieces;
    }

    
}
