<?php

namespace HotelBundle\Model;

/**
 * Description of TTApiHotelSource
 *
 * 
 */
class TTApiHotelSource
{
    private $code = "";
    private $id   = 0;
    private $vendor;

    public function __construct()
    {
        $this->vendor = new TTApiVendor();
    }

    /**
     * Get code.
     * @return String
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get id.
     * @return Integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get vendor.
     * @return TTApiVendor
     */
    public function getVendor()
    {
        return $this->vendor;
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
     * Set id.
     * @param Integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set vendor.
     * @param \HotelBundle\Model\TTApiVendor $vendor
     * @return $this
     */
    public function setVendor(TTApiVendor $vendor)
    {
        $this->vendor = $vendor;
        return $this;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toreturn           = get_object_vars($this);
        $toreturn['vendor'] = $this->getVendor()->toArray();

        return $toreturn;
    }
}