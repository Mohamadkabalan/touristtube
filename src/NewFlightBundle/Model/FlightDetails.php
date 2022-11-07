<?php

namespace NewFlightBundle\Model;

class FlightDetails extends flightVO
{
    /**
     * 
     */
    private $passengerNameRecord;

    /**
     * 
     */
    private $flightIteneraryGrouped;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->passengerNameRecord    = new PassengerNameRecord();
        $this->flightIteneraryGrouped = new FlightIteneraryGrouped();
    }

    /**
     * Get PassengerNameRecord Model object
     * @return object
     */
    public function getPassengerNameRecord()
    {
        return $this->passengerNameRecord;
    }

    /**
     * Get FlightIteneraryGrouped Model object
     * @return object
     */
    public function getFlightIteneraryGrouped()
    {
        return $this->flightIteneraryGrouped;
    }

    /**
     * Set PassengerNameRecord Model object
     * @param object
     */
    public function setPassengerNameRecord($passengerNameRecord)
    {
        $this->passengerNameRecord = $passengerNameRecord;
    }

    /**
     * Set flightIteneraryGrouped Model object
     * @param object
     */
    public function setFlightIteneraryGrouped($flightIteneraryGrouped)
    {
        $this->flightIteneraryGrouped = $flightIteneraryGrouped;
    }
}
