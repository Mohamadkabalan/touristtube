<?php

namespace HotelBundle\Model;

/**
 * Description of TTApiVendor
 *
 * 
 */
class TTApiVendor
{
    private $id   = 0;
    private $name = '';
    private $source;

    public function __construct()
    {
        $this->source = new TTApiVendorSource();
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
     * Get name.
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get source.
     * @return TTApiVendorSource
     */
    public function getSource()
    {
        return $this->source;
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
     * Set name.
     * @param String $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set source.
     * @param \HotelBundle\Model\TTApiVendorSource $source
     * @return $this
     */
    public function setSource(TTApiVendorSource $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toreturn           = get_object_vars($this);
        $toreturn['source'] = $this->getSource()->toArray();

        return $toreturn;
    }
}