<?php

namespace HotelBundle\Entity;

/**
 * CmsAmenity
 */
class CmsAmenity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var boolean
     */
    private $hasCount = 'b\'0\'';

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return CmsAmenity
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set hasCount
     *
     * @param boolean $hasCount
     *
     * @return CmsAmenity
     */
    public function setHasCount($hasCount)
    {
        $this->hasCount = $hasCount;

        return $this;
    }

    /**
     * Get hasCount
     *
     * @return boolean
     */
    public function getHasCount()
    {
        return $this->hasCount;
    }
}