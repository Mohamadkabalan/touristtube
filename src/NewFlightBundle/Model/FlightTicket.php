<?php

namespace NewFlightBundle\Model;

class FlightTicket extends flightVO
{
    /**
     * 
     */
    private $passengerDetailsModel;

    /**
     * 
     */
    private $flightSegmentModel;

    /**
     * @var string
     */
    private $ticketNumber;

    /**
     * @var string
     */
    private $rph;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->passengerDetailsModel = new PassengerDetails();
        $this->flightSegmentModel    = new FlightSegment();
    }

    /**
     * Get PassengerDetails Model object
     * @return object
     */
    public function getPassengerDetailsModel()
    {
        return $this->passengerDetailsModel;
    }

    /**
     * Get FlightSegement Model object
     * @return object
     */
    public function getFlightSegmentModel()
    {
        return $this->flightSegmentModel;
    }

    /**
     * Set FlightSegment
     * @param String $flightSegement
     */
    public function setFlightSegmentModel($flightSegement)
    {
        $this->flightSegmentModel = $flightSegement;
    }

    /**
     * Get ticketNumber
     * @return String
     */
    public function getTicketNumber()
    {
        return $this->ticketNumber;
    }

    /**
     * Set ticketNumber
     * @param String $ticketNumber
     */
    public function setTicketNumber($ticketNumber)
    {
        $this->ticketNumber = $ticketNumber;
    }

    /**
     * Get rph
     * @return String
     */
    public function getRph()
    {
        return $this->rph;
    }

    /**
     * Set rph
     * @param String $rph
     */
    public function setRph($rph)
    {
        $this->rph = $rph;

    }

    /**
     * Set PassengerDetailsModel Model object
     * @return object
     */
    function setPassengerDetailsModel($passengerDetailsModel)
    {
        $this->passengerDetailsModel = $passengerDetailsModel;
    }

}
