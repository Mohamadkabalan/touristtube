<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HotelBundle\Model;

/**
 * Description of City
 *
 * @author Home
 */
class City
{
    private $id   = 0;
    private $code = '';
    private $name = '';

    /**
     * Get city id.
     * @return Integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get city code.
     * @return Integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get city name.
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set city id.
     * @param Integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = intval($id);
        return $this;
    }

    /**
     * Set city code.
     * @param Integer $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Set city name.
     * @param String $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
