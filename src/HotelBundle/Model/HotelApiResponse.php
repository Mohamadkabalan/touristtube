<?php

namespace HotelBundle\Model;

/**
 * Description of HotelApiResponse
 */
class HotelApiResponse
{
    private $error       = array();
    private $session     = null;
    private $success     = false;
    private $xmlResponse = '';

    /**
     * The parsed data from $xmlResponse; Or other information.
     * @var array
     */
    private $data = array();

    /**
     * Get error.
     * @return Mixed    Array of errors/error message
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set error.
     * @param Mixed $error  Array of errors/error message
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * Set session.
     * @return array
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set session.
     * @param type $session
     */
    public function setSession(array $session)
    {
        $this->session = $session;
    }

    /**
     * Get success.
     * @return Boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * Set success.
     * @param Boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * Get xmlResponse.
     * @return String
     */
    public function getXmlResponse()
    {
        return $this->xmlResponse;
    }

    /**
     * Set setXmlResponse.
     * @param String $xmlResponse
     */
    public function setXmlResponse($xmlResponse)
    {
        $this->xmlResponse = $xmlResponse;
    }

    /**
     * Get the parsed data from $xmlResponse.
     * @return Mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the data.
     * @param Mixed $data    The parsed data from $xmlResponse.
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
