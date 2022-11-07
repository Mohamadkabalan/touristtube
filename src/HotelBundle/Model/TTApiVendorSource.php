<?php

namespace HotelBundle\Model;

/**
 * Description of TTApiVendorSource
 *
 * 
 */
class TTApiVendorSource
{
    private $code = '';

    /**
     * Get code.
     * @return String
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set code.
     * @param String $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}