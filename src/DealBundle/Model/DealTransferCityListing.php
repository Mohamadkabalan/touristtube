<?php

namespace DealBundle\Model;

/**
 * DealTransferCityListing contains the Cities for a specific Country.
 * This is currently used in transport.
 * We have an attribute for this in DealResponse called $transferCities.
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealTransferCityListing
{
    private $name = '';

    /**
     * Get name
     * @return String
     */
    function getName()
    {
        return $this->name;
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