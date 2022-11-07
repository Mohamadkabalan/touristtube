<?php

namespace HotelBundle\Model;

/**
 * Description of RspApiResponse
 *
 *
 */
class RspApiResponse extends RspResponse
{
    private $session;
    private $request;
    private $response;

    /**
     * The __construct when we make a new instance of RspApiResponse class.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get session.
     * @return Array
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Get request.
     * @return String
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get response.
     * @return String
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set session
     * @param Array $session
     * @return $this
     */
    public function setSession(array $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * Set request.
     * @param String $request
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Set response.
     * @param String $response
     * @return $this
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Get array format response of this instance
     * @return type
     */
    public function toArray()
    {
        return array_merge(get_object_vars($this), parent::toArray());
    }
}
