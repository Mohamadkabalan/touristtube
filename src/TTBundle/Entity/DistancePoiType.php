<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DistancePoiType
 *
 * @ORM\Table(name="distance_poi_type")
 * @ORM\Entity
 */
class DistancePoiType
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $distancePoiTypeGroupId;

    /**
     * @var integer
     */
    private $vendorId;

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
     * @return DistancePoiType
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
     * Set distancePoiTypeGroupId
     *
     * @param integer $distancePoiTypeGroupId
     *
     * @return DistancePoiType
     */
    public function setDistancePoiTypeGroupId($distancePoiTypeGroupId)
    {
        $this->distancePoiTypeGroupId = $distancePoiTypeGroupId;

        return $this;
    }

    /**
     * Get distancePoiTypeGroupId
     *
     * @return integer
     */
    public function getDistancePoiTypeGroupId()
    {
        return $this->distancePoiTypeGroupId;
    }

    /**
     * Set vendorId
     *
     * @param integer $vendorId
     *
     * @return DistancePoiType
     */
    public function setVendorId($vendorId)
    {
        $this->vendorId = $vendorId;

        return $this;
    }

    /**
     * Get vendorId
     *
     * @return integer
     */
    public function getVendorId()
    {
        return $this->vendorId;
    }
}
