<?php

namespace NewFlightBundle\Model;

use JMS\Serializer\Annotation\Type;

class FlightCriteria extends flightVO
{
    /**
     * @var Airport $airport
     */
    private $airport;

    /**
     * @var $dateTime
     */
    private $dateTime;

    /**
     * @var $timezoneGmtOffset
     */
    private $timezoneGmtOffset;


    /**
     * The __construct
     */
    public function __construct()
    {
        $this->airport = new Airport();
    }

    /**
     * Get Airport object
     * @return Airport
     */
    public function getAirport()
    {
        return $this->airport;
    }

    /**
     * Get datetime
     * @return date
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Get timezoneGmtOffset
     * @return string
     */
    public function getTimezoneGmtOffset()
    {
        return $this->timezoneGmtOffset;
    }

    /**
     * Set Airport
     * @param Airport $airport
     */
    public function setAirport(Airport $airport)
    {
        $this->airport = $airport;
    }

    /**
     * Set dateTime
     * @param date $dateTime
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * Set timezoneGmtOffset
     * @param string $timezoneGmtOffset
     */
    public function setTimezoneGmtOffset($timezoneGmtOffset)
    {
        $this->timezoneGmtOffset = $timezoneGmtOffset;
    }
}
