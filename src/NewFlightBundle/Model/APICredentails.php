<?php

namespace NewFlightBundle\Model;

class APICredentails extends flightVO
{
    /**
     * @var string
     */
    private $providerName;

    /**
     * @var boolean
     */
    private $testMode;

    /**
     * @var string
     */
    private $URL;

    /**
     * @var string
     */
    private $projectUserName;

    /**
     * @var string
     */
    private $companyName;

    /**
     * @var string
     */
    private $projectIPCC;

    /**
     * @var string
     */
    private $projectPCC;

    /**
     * @var string
     */
    private $projectPassword;

    /**
     * @var string
     */
    private $projectDomain;

    /**
     * @var string
     */
    private $conversationId;

    /**
     * @var string
     */
    private $messageId;

    /**
     * @var string
     */
    private $partyIdFrom;

    /**
     * @var string
     */
    private $partyIdTo;

    /**
     * @var datetime
     */
    private $timestamp;

    /**
     * @var datetime
     */
    private $timeToLive;

    /**
     * @var string
     */
    private $salt;

    /**
     * Get providerName
     * @return String
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * Get testMode
     * @return boolean
     */
    public function isTestMode()
    {
        return $this->testMode;
    }

    /**
     * Get URL
     * @return String
     */
    public function getURL()
    {
        return $this->URL;
    }

    /**
     * Get projectUserName
     * @return String
     */
    public function getProjectUserName()
    {
        return $this->projectUserName;
    }

    /**
     * Get companyName
     * @return String
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Get projectIPCC
     * @return String
     */
    public function getProjectIPCC()
    {
        return $this->projectIPCC;
    }

    /**
     * Get projectPCC
     * @return String
     */
    public function getProjectPCC()
    {
        return $this->projectPCC;
    }

    /**
     * Get projectPassword
     * @return String
     */
    public function getProjectPassword()
    {
        return $this->projectPassword;
    }

    /**
     * Get projectDomain
     * @return String
     */
    public function getProjectDomain()
    {
        return $this->projectDomain;
    }

    /**
     * Get conversationId
     * @return String
     */
    public function getConversationId()
    {
        return $this->conversationId;
    }

    /**
     * Get messageId
     * @return String
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * Get partyIdFrom
     * @return String
     */
    public function getPartyIdFrom()
    {
        return $this->partyIdFrom;
    }

    /**
     * Get timestamp
     * @return datetime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Get timeToLive
     * @return datetime
     */
    public function getTimeToLive()
    {
        return $this->timeToLive;
    }

    /**
     * Get salt
     * @return String
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set providerName
     * @param String $providerName
     */
    public function setProviderName($providerName)
    {
        $this->providerName = $providerName;
    }

    /**
     * Set testMode
     * @param boolean (true or false)
     */
    public function setIsTestMode($testMode)
    {
        $this->testMode = $testMode;
    }

    /**
     * Set URL
     * @param String $URL
     */
    public function setURL($URL)
    {
        $this->URL = $URL;
    }

    /**
     * Set projectUserName
     * @param String $projectUserName
     */
    public function setProjectUserName($projectUserName)
    {
        $this->projectUserName = $projectUserName;
    }

    /**
     * Set companyName
     * @param String $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * Set projectIPCC
     * @param String $projectIPCC
     */
    public function setProjectIPCC($projectIPCC)
    {
        $this->projectIPCC = $projectIPCC;
    }

    /**
     * Set projectPCC
     * @param String $projectPCC
     */
    public function setProjectPCC($projectPCC)
    {
        $this->projectPCC = $projectPCC;
    }

    /**
     * Set projectPassword
     * @param String $projectPassword
     */
    public function setProjectPassword($projectPassword)
    {
        $this->projectPassword = $projectPassword;
    }

    /**
     * Set projectDomain
     * @param String $projectDomain
     */
    public function setProjectDomain($projectDomain)
    {
        $this->projectDomain = $projectDomain;
    }

    /**
     * Set conversationId
     * @param String $conversationId
     */
    public function setConversationId($conversationId)
    {
        $this->conversationId = $conversationId;
    }

    /**
     * Set messageId
     * @param String $messageId
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * Set partyIdFrom
     * @param String $partyIdFrom
     */
    public function setPartyIdFrom($partyIdFrom)
    {
        $this->partyIdFrom = $partyIdFrom;
    }

    /**
     * Set timestamp
     * @param datetime
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Set timeToLive
     * @param datetime
     */
    public function setTimeToLive($timeToLive)
    {
        $this->timeToLive = $timeToLive;
    }

    /**
     * Set salt
     * @param String $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
}