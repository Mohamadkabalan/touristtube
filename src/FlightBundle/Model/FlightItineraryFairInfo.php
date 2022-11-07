<?php

namespace FlightBundle\Model;


/**
 * Flight Itinerary Fair Info
 *
 * This class serves as getter and setter for Flight Itinerary Fair Info
 */
class FlightItineraryFairInfo {

    /**
     * @var seatsRemaining
     */
    protected $seatsRemaining;

    /**
     * @var cabin
     */
    protected $cabin;

    /**
     * Get seatsRemaining
     *
     * @return string
     */
    public function getSeatsRemaining() {
        return $this->seatsRemaining;
    }

    /**
     * Set seatsRemaining
     *
     * @param string $seatsRemaining
     */
    public function setSeatsRemaining($seatsRemaining) {
        $this->seatsRemaining = $seatsRemaining;
    }

    /**
     * Set cabin
     *
     * @param string $cabin
     */
    public function setCabin($cabin) {
        $this->cabin = $cabin;
    }

    /**
     * Get cabin
     *
     * @return string cabin
     */
    public function getCabin() {
        return $this->cabin;
    }

    
}
