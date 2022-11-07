<?php

namespace DealBundle\Model;

/**
 * DealStartingPlace contains the starting place data from activitiyDetails call
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealStartingPlace
{
    private $address = '';
    private $lat     = '';
    private $long    = '';

    /**
     * Get address
     * @return String
     */
    function getAddress()
    {
        return $this->address;
    }

    /**
     * Get lat
     * @return String
     */
    function getLat()
    {
        return $this->lat;
    }

    /**
     * Get long
     * @return String
     */
    function getLong()
    {
        return $this->long;
    }

    /**
     * Set address
     * @param String $address
     */
    function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Set lat
     * @param String $lat
     */
    function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * Set long
     * @param String $long
     */
    function setLong($long)
    {
        $this->long = $long;
    }
}