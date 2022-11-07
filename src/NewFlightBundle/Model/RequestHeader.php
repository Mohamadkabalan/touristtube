<?php

namespace NewFlightBundle\Model;

class RequestHeader extends flightVO
{
    /**
     * 
     */
    private $createSessionRSModel;

    /**
     * 
     */
    private $apiCredentialsModel;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->apiCredentialsModel  = new APICredentials();
        $this->createSessionRSModel = new CreateSessionRS();
    }

    /**
     * Get APICredentails Model object
     * @return object
     */
    public function getApiCredentialsModel()
    {
        return $this->apiCredentialsModel;
    }

    /**
     * Get CreateSessionRS Model object
     * @return object
     */
    public function getCreateSessionRSModel()
    {
        return $this->createSessionRSModel;
    }
}