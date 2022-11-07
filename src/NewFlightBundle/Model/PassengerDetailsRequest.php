<?php

namespace NewFlightBundle\Model;

class PassengerDetailsRequest extends flightVO
{
	/**
     * 
     */
    private $passportCheck;

    /**
     * 
     */
    private $marketingAirlineModel;

    /**
     * 
     */
    private $passengerNameRecordModel;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->passengerNameRecordModel = new PassengerNameRecord();
        $this->marketingAirlineModel = new Airline();
    }

    /**
     * Get PassengerNameRecord Model object
     * @return object
     */
    public function getPassengerNameRecordModel()
    {
        return $this->passengerNameRecordModel;
    }

    /**
     * Get Airline Model object
     * @return object
     */
    public function getMarketingAirlineModel()
    {
        return $this->marketingAirlineModel;
    }

    /**
     * Get passportCheck
     * @return string
     */
    public function getPassportCheck()
    {
        return $this->passportCheck;
    }

    /**
     * Set PassengerNameRecord Model object
     * @param object
     */
    public function setPassengerNameRecordModel($passengerNameRecordModel)
    {
        $this->passengerNameRecordModel = $passengerNameRecordModel;
    }

    /**
     * Set PassportCheck
     * @param string
     */
    public function setPassportCheck($passportCheck)
    {
        $this->passportCheck = $passportCheck;
    }


}