<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoiGps
 *
 * @ORM\Table(name="poi_gps")
 * @ORM\Entity
 */
class PoiGps
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="poi_name", type="string", length=200, nullable=false)
     */
    private $poiName;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=150, nullable=false)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=5, nullable=true)
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country_name", type="string", length=200, nullable=true)
     */
    private $countryName;

    /**
     * @var float
     *
     * @ORM\Column(name="poi_latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $poiLatitude;

    /**
     * @var float
     *
     * @ORM\Column(name="poi_longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $poiLongitude;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status = '0';



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
     * Set poiName
     *
     * @param string $poiName
     *
     * @return PoiGps
     */
    public function setPoiName($poiName)
    {
        $this->poiName = $poiName;

        return $this;
    }

    /**
     * Get poiName
     *
     * @return string
     */
    public function getPoiName()
    {
        return $this->poiName;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return PoiGps
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return PoiGps
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set countryName
     *
     * @param string $countryName
     *
     * @return PoiGps
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * Get countryName
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Set poiLatitude
     *
     * @param float $poiLatitude
     *
     * @return PoiGps
     */
    public function setPoiLatitude($poiLatitude)
    {
        $this->poiLatitude = $poiLatitude;

        return $this;
    }

    /**
     * Get poiLatitude
     *
     * @return float
     */
    public function getPoiLatitude()
    {
        return $this->poiLatitude;
    }

    /**
     * Set poiLongitude
     *
     * @param float $poiLongitude
     *
     * @return PoiGps
     */
    public function setPoiLongitude($poiLongitude)
    {
        $this->poiLongitude = $poiLongitude;

        return $this;
    }

    /**
     * Get poiLongitude
     *
     * @return float
     */
    public function getPoiLongitude()
    {
        return $this->poiLongitude;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return PoiGps
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
}
