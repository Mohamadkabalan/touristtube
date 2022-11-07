<?php

namespace NewFlightBundle\Model;

class CreateSessionRQ extends flightVO
{
    /**
     * 
     */
    private $apiCredentailsModel;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->apiCredentailsModel = new APICredentails();
    }

    /**
     * Get APICredentails Model object
     * @return object
     */
    public function getApiCredentailsModel()
    {
        return $this->apiCredentailsModel;
    }
}