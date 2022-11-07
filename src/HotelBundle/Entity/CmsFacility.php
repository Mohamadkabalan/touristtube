<?php

namespace HotelBundle\Entity;

/**
 * CmsFacility
 */
class CmsFacility
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
     * @var integer
     */
    private $typeId = '0';

    /**
     * @var integer
     */
    private $amenityLevel = '0';

    /**
     * @var integer
     */
    private $published = '0';

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
     * @return CmsFacility
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
     * Set typeId
     *
     * @param integer $typeId
     *
     * @return CmsFacility
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get typeId
     *
     * @return integer
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set amenityLevel
     *
     * @param integer $amenityLevel
     *
     * @return CmsFacility
     */
    public function setAmenityLevel($amenityLevel)
    {
        $this->amenityLevel = $amenityLevel;

        return $this;
    }

    /**
     * Get amenityLevel
     *
     * @return integer
     */
    public function getAmenityLevel()
    {
        return $this->amenityLevel;
    }

    /**
     * Set published
     *
     * @param integer $published
     *
     * @return CmsFacility
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return integer
     */
    public function getPublished()
    {
        return $this->published;
    }
}