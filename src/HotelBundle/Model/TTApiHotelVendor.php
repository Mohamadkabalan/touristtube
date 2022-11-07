<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HotelBundle\Model;

/**
 * Description of TTApiHotelVendor
 *
 * @author Home
 */
class TTApiHotelVendor
{
    private $id   = 0;
    private $name = '';
    private $providers;

    public function __construct()
    {
        $this->providers = array();
    }

    /**
     * Get id.
     * @return Integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name.
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get providers.
     * @return Array
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * Set id.
     * @param Integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set name.
     * @param String $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Add provider.
     * @param TTApiVendorSource $provider
     * @return $this
     */
    public function addProvider(TTApiVendorSource $provider)
    {
        $this->providers[] = $provider;
        return $this;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toreturn = get_object_vars($this);

        $providers = array();
        foreach ($this->getProviders() AS $provider) {
            $providers[] = $provider->toArray();
        }

        $toreturn['providers'] = $providers;

        return $toreturn;
    }
}