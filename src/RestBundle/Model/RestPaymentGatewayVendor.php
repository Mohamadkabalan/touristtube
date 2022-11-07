<?php

namespace RestBundle\Model;

class RestPaymentGatewayVendor
{
    private $id;
    private $responseCode;
    private $message;

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get ResponseCode
     * @return string
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * Set ResponseCode
     * @param string $responseCode
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    /**
     * Get Message
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set Message
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
