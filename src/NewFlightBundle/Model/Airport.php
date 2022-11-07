<?php

namespace NewFlightBundle\Model;

class Airport extends flightVO
{
    /**
     * 
     */
    private $id = '';

    /**
     * 
     */
    private $name = '';

    /**
     * 
     */
    private $code = '';

    /**
     * 
     */
    private $city;

    /**
     * 
     */
    private $terminalId = '';

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->city = new City();
    }

    /**
     * Get City object
     * @return City object
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get code
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get terminalId
     * @return string
     */
    public function getTerminalId()
    {
        return $this->terminalId;
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
     * Set name
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set code
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Set terminalId
     * @param string $terminalId
     */
    public function setTerminalId($terminalId)
    {
        $this->terminalId = $terminalId;
    }

    /**
     * Set city
     * @param City $city
     */
    public function setCity(City $city)
    {
        $this->city = $city;
    }
}
