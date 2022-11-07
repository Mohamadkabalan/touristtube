<?php

namespace NewFlightBundle\Model;

class Airline extends flightVO
{
    /**
     * 
     */
    private $id = '';

    /**
     * 
     */
    private $airlineName = '';

    /**
     * 
     */
    private $airlineCode = '';

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get airlineName
     * @return string
     */
    public function getAirlineName()
    {
        return $this->airlineName;
    }

    /**
     * Get airlineCode
     * @return string
     */
    public function getAirlineCode()
    {
        return $this->airlineCode;
    }

    /**
     * Set id
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set airlineName
     * @param string $airlineName
     */
    public function setAirlineName($airlineName)
    {
        $this->airlineName = $airlineName;
    }

    /**
     * Set airlineCode
     * @param string $airlineCode
     */
    public function setAirlineCode($airlineCode)
    {
        $this->airlineCode = $airlineCode;
    }
}