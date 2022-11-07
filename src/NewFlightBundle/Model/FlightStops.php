<?php

namespace NewFlightBundle\Model;

/**
 * Class FlightStops
 *
 * This class serves as setter and getter class for flight Stops (i.e: stops duration, city, etc...)
 *
 * @package NewFlightBundle\Model
 */

class FlightStops extends flightVO
{
    /**
     * @var stops
     */
    private $stops;

    /**
     * @var duration
     */
    private $duration;

    /**
     * @var city Object
     */
    private $city;

    /**
     * @var indicator
     */
    private $indicator;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->city  = new City();
    }

    /**
     * Set stops
     * @param integer $stops
     */
    public function setStops($stops)
    {
        $this->stops = $stops;
    }

    /**
     * Get stops
     * @return integer
     */
    public function getStops()
    {
        return $this->stops;
    }

    /**
     * Set city
     *
     * @param City $city
     */
    public function setCity(City $city)
    {
        $this->city = $city;
    }

    /**
     * Set duration
     * @param integer $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * Get duration
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Get city
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set indicator
     * @param integer $indicator
     */
    public function setIndicator($indicator)
    {
        $this->indicator = $indicator;
    }

    /**
     * Get indicator
     * @return integer
     */
    public function getIndicator()
    {
        return $this->indicator;
    }

}
