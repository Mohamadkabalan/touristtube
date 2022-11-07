<?php

namespace HotelBundle\Model;

/**
 * The RspResponse class.
 *
 */
class RspResponse
{
    private $status;

    /**
     * The __construct when we make a new instance of RspResponse class.
     */
    public function __construct()
    {
        $this->status = new RspStatus();
    }

    /**
     * Set status
     * @param \HotelBundle\Model\RspStatus $status
     * @return $this
     */
    public function setStatus(RspStatus $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get cancellation status
     * @return HotelResponseStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get boolean true if has error; otherwise false
     * @return type
     */
    public function hasError()
    {
        return (!$this->status->getStatus());
    }

    /**
     * Converts an object to an array.
     * @return Array
     */
    public function toArray()
    {
        return array_merge(get_object_vars($this), $this->status->toArray());
    }
}
