<?php

namespace DealBundle\Model;

/**
 * DealTransferCountryListing contains the countries used for transport.
 * We have an attribute for this in DealResponse called $transferCountries.
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealTransferCountryListing
{
    private $code      = '';
    private $name      = '';
    private $continent = '';

    /**
     * Get code
     * @return String
     */
    function getCode()
    {
        return $this->code;
    }

    /**
     * Get name
     * @return String
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * Get continent
     * @return String
     */
    function getContinent()
    {
        return $this->continent;
    }

    /**
     * Set code
     * @param String $code
     */
    function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Set name
     * @param String $name
     */
    function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set continent
     * @param String $continent
     */
    function setContinent($continent)
    {
        $this->continent = $continent;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $value) {
            $toreturn[$key] = $value;
        }
        return $toreturn;
    }
}