<?php

namespace DealBundle\Model;

/**
 * DealAirport is used for the airport object call including its code and name
 * where we are calling it in different classes
 * This class is currently used as object for the XmlReponse in transfers inside CityDiscoveryHandler.php
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealAirport
{
    private $id   = '';
    private $name = '';
    private $code = '';

    /**
     * Get id
     * @return String
     */
    function getId()
    {
        return $this->id;
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
     * Get code
     * @return String
     */
    function getCode()
    {
        return $this->code;
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
     * Set name
     * @param String $name
     */
    function setName($name)
    {
        $this->name = $name;
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