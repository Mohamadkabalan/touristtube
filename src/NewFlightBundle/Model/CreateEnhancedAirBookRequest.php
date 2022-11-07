<?php

namespace NewFlightBundle\Model;

class CreateEnhancedAirBookRequest extends flightVO
{
    /**
     * 
     */
    private $flightItineraryModel;

    /**
     * 
     */
    private $numberInParty;

    /**
     * 
     */
    private $adultsQuantity;

    /**
     * 
     */
    private $infantsQuantity;

    /**
     * 
     */
    private $childrenQuantity;

    /**
     * 
     */
    private $accessToken;

    /**
     * 
     */
    private $returnedConversationId;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->flightItineraryModel = new FlightItinerary();
    }

    /**
     * Get flightItinerary Model
     * @return flightItinerary
     */
    public function getFlightItineraryModel()
    {
        return $this->flightItineraryModel;
    }

    /**
     * Get numberInParty
     * @return numberInParty
     */
    public function getNumberInParty()
    {
        return $this->numberInParty;
    }

    /**
     * Get adultsQuantity
     * @return adultsQuantity
     */
    public function getAdultsQuantity()
    {
        return $this->adultsQuantity;
    }

    /**
     * Get childrenQuantity
     * @return childrenQuantity
     */
    public function getChildrenQuantity()
    {
        return $this->childrenQuantity;
    }

    /**
     * Get infantsQuantity
     * @return infantsQuantity
     */
    public function getInfantsQuantity()
    {
        return $this->infantsQuantity;
    }

    /**
     * Get accessToken
     * @return accessToken
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Get returnedConverstationId
     * @return returnedConverstationId
     */
    public function getReturnedConverstationId()
    {
        return $this->returnedConverstationId;
    }

    /**
     * Set flightItinerary Model
     * @param $flightItineraryModel
     */
    public function setFightItineraryModel($flightItineraryModel)
    {
        $this->flightItineraryModel = $flightItineraryModel;
    }

    /**
     * Set numberInParty
     * @param $numberInParty
     */
    public function setNumberInParty($numberInParty)
    {
        $this->numberInParty = $numberInParty;
    }

    /**
     * Set adultsQuantity
     * @param $adultsQuantity
     */
    public function setAdultsQuantity($adultsQuantity)
    {
        $this->adultsQuantity = $adultsQuantity;
    }

    /**
     * Set childrenQuantity
     * @param $childrenQuantity
     */
    public function setChildrenQuantity($childrenQuantity)
    {
        $this->childrenQuantity = $childrenQuantity;
    }

    /**
     * Set infantsQuantity
     * @param $infantsQuantity
     */
    public function setInfantsQuantity($infantsQuantity)
    {
        $this->infantsQuantity = $infantsQuantity;
    }

    /**
     * Set accessToken
     * @param $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Set returnedConversationId
     * @param $returnedConversationId
     */
    public function setReturnedConversationId($returnedConversationId)
    {
        $this->returnedConversationId = $returnedConversationId;
    }

}
    