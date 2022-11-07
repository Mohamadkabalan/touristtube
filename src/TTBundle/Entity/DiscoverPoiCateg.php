<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverPoiCateg
 *
 * @ORM\Table(name="discover_poi_categ")
 * @ORM\Entity
 */
class DiscoverPoiCateg
{
    /**
     * @var integer
     *
     * @ORM\Column(name="categ_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $categId;

    /**
     * @var integer
     *
     * @ORM\Column(name="poi_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $poiId;



    /**
     * Set categId
     *
     * @param integer $categId
     *
     * @return DiscoverPoiCateg
     */
    public function setCategId($categId)
    {
        $this->categId = $categId;

        return $this;
    }

    /**
     * Get categId
     *
     * @return integer
     */
    public function getCategId()
    {
        return $this->categId;
    }

    /**
     * Set poiId
     *
     * @param integer $poiId
     *
     * @return DiscoverPoiCateg
     */
    public function setPoiId($poiId)
    {
        $this->poiId = $poiId;

        return $this;
    }

    /**
     * Get poiId
     *
     * @return integer
     */
    public function getPoiId()
    {
        return $this->poiId;
    }
}
