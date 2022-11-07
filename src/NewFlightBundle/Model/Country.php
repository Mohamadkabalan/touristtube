<?php

namespace NewFlightBundle\Model;

class Country extends flightVO
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
    private $iso3 = '';

    /**
     * 
     */
    private $iso2 = '';

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
     * Get iso3
     * @return string
     */
    public function getIso3()
    {
        return $this->iso3;
    }

    /**
     * Get iso2
     * @return string
     */
    public function getIso2()
    {
        return $this->iso2;
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
     * Set iso3
     * @param string $iso3
     */
    public function setIso3($iso3)
    {
        $this->iso3 = $iso3;
    }

    /**
     * Set iso2
     * @param string $iso2
     */
    public function setIso2($iso2)
    {
        $this->iso2 = $iso2;
    }
}