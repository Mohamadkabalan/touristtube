<?php

namespace DealBundle\Model;

use TTBundle\Model\Country;
use TTBundle\Model\City;

/**
 *  Common deals properties which are used in most of times
 *
 * @author Anna Lou Parejo <anna.parejo@touristtube.com>
 */
class DealsCommonSC
{
    /**
     * 
     */
    private $country;

    /**
     * 
     */
    private $city;

    /**
     * 
     */
    private $package;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->country = new Country();
        $this->city    = new City();
        $this->package = new DealPackage();
    }

    /**
     * Get Package
     * @return Package object
     */
    function getPackage()
    {
        return $this->package;
    }

    /**
     * Get Country
     * @return Country object
     */
    function getCountry()
    {
        return $this->country;
    }

    /**
     * Get City
     * @return City object
     */
    function getCity()
    {
        return $this->city;
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