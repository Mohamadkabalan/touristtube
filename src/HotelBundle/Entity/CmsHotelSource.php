<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsHotelSource
 *
 * @ORM\Table(name="cms_hotel_source", indexes={@ORM\Index(name="source_hotel", columns={"source","hotel_id"})})
 * @ORM\Entity
 */
class CmsHotelSource
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $hotelId = '0';

    /**
     * @var string
     */
    private $source = '';

    /**
     * @var integer
     */
    private $sourceId = '0';

    /**
     * @var integer
     */
    private $locationId = '0';

    /**
     * @var string
     */
    private $trustyouId = '';

    /**
     * @var integer
     */
    private $isActive = '1';

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
     * Set hotelId
     *
     * @param integer $hotelId
     *
     * @return CmsHotelSource
     */
    public function setHotelId($hotelId)
    {
        $this->hotelId = $hotelId;

        return $this;
    }

    /**
     * Get hotelId
     *
     * @return integer
     */
    public function getHotelId()
    {
        return $this->hotelId;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return CmsHotelSource
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set sourceId
     *
     * @param integer $sourceId
     *
     * @return CmsHotelSource
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    /**
     * Get sourceId
     *
     * @return integer
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * Set locationId
     *
     * @param integer $locationId
     *
     * @return CmsHotelSource
     */
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * Get locationId
     *
     * @return integer
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * Set trustyouId
     *
     * @param string $trustyouId
     *
     * @return CmsHotelSource
     */
    public function setTrustyouId($trustyouId)
    {
        $this->trustyouId = $trustyouId;

        return $this;
    }

    /**
     * Get trustyouId
     *
     * @return string
     */
    public function getTrustyouId()
    {
        return $this->trustyouId;
    }

    /**
     * Set isActive
     *
     * @param integer $isActive
     *
     * @return CmsHotelSource
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return integer
     */
    public function isActive()
    {
        return $this->isActive;
    }
}
