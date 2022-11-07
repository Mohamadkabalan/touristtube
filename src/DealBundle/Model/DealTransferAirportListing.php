<?php

namespace DealBundle\Model;

use TTBundle\Model\Country;
use TTBundle\Model\City;

/**
 * DealTransferAirportListingResponse is the class for different airports in transports.
 * These airports are based from the parameters Country and City.
 * We have an attribute for this in DealResponse called $transferAirports.
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealTransferAirportListing
{
    /**
     * @var integer
     */
    private $id = '';

    /**
     * @var string
     */
    private $code = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $type = '';

    /**
     * 
     */
    private $country;

    /**
     * 
     */
    private $city;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->country = new Country();
        $this->city    = new City();
    }

    /**
     * Get id
     * @return String
     */
    function getId()
    {
        return $this->id;
    }

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
     * Get type
     * @return String
     */
    function getType()
    {
        return $this->type;
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
     * Set id
     * @param String $id
     */
    function setId($id)
    {
        $this->id = $id;
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
     * Set type
     * @param String $type
     */
    function setType($type)
    {
        $this->type = $type;
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