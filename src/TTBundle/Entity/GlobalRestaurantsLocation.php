<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GlobalRestaurantsLocation
 *
 * @ORM\Table(name="global_restaurants_location", indexes={@ORM\Index(name="city_id", columns={"city_id"}), @ORM\Index(name="locality", columns={"locality"}), @ORM\Index(name="region", columns={"region"}), @ORM\Index(name="country", columns={"country"}), @ORM\Index(name="admin_region", columns={"admin_region"})})
 * @ORM\Entity
 */
class GlobalRestaurantsLocation
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
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     */
    private $cityId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="locality", type="string", length=255, nullable=false)
     */
    private $locality;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255, nullable=false)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="admin_region", type="string", length=255, nullable=false)
     */
    private $adminRegion;

    /**
     * @var string
     *
     * @ORM\Column(name="state_code", type="string", length=20, nullable=false)
     */
    private $stateCode;



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
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return GlobalRestaurantsLocation
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get cityId
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set locality
     *
     * @param string $locality
     *
     * @return GlobalRestaurantsLocation
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * Get locality
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return GlobalRestaurantsLocation
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return GlobalRestaurantsLocation
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set adminRegion
     *
     * @param string $adminRegion
     *
     * @return GlobalRestaurantsLocation
     */
    public function setAdminRegion($adminRegion)
    {
        $this->adminRegion = $adminRegion;

        return $this;
    }

    /**
     * Get adminRegion
     *
     * @return string
     */
    public function getAdminRegion()
    {
        return $this->adminRegion;
    }

    /**
     * Set stateCode
     *
     * @param string $stateCode
     *
     * @return GlobalRestaurantsLocation
     */
    public function setStateCode($stateCode)
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    /**
     * Get stateCode
     *
     * @return string
     */
    public function getStateCode()
    {
        return $this->stateCode;
    }
}
