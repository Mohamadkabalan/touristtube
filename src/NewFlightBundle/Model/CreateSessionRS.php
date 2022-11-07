<?php

namespace NewFlightBundle\Model;

class CreateSessionRS extends flightVO
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $returnedConversationId;

    /**
     * Get accessToken
     * @return String
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Get returnedConversationId
     * @return String
     */
    public function getReturnedConversationId()
    {
        return $this->returnedConversationId;
    }

    /**
     * Set accessToken
     * @param String $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Set returnedConversationId
     * @param String $returnedConversationId
     */
    public function setReturnedConversationId($returnedConversationId)
    {
        $this->returnedConversationId = $returnedConversationId;
    }
}