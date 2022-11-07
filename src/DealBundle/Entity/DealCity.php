<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealCity
 *
 * @ORM\Table(name="deal_city", indexes={@ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealCity
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
     * @ORM\Column(name="country_code", type="string", length=10, nullable=true)
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city_code", type="string", length=100, nullable=true)
     */
    private $cityCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city_name", type="string", length=100, nullable=true)
     */
    private $cityName;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=100, nullable=true)
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=true)
     */
    private $cityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="deal_api_supplier_id", type="integer", nullable=true)
     */
    private $dealApiSupplierId;

    /**
     * @var string
     *
     * @ORM\Column(name="parent_city_code", type="string", length=100, nullable=true)
     */
    private $parentCityCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", nullable=false)
     */
    private $priority;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="display_order", type="integer", nullable=true)
     */
    private $displayOrder;

    function getId()
    {
        return $this->id;
    }

    function getCountryCode()
    {
        return $this->countryCode;
    }

    function getCityCode()
    {
        return $this->cityCode;
    }

    function getCityName()
    {
        return $this->cityName;
    }

    function getState()
    {
        return $this->state;
    }

    function getCreatedAt()
    {
        return $this->createdAt;
    }

    function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    function getCityId()
    {
        return $this->cityId;
    }

    function getDealApiSupplierId()
    {
        return $this->dealApiSupplierId;
    }

    function getParentCityCode()
    {
        return $this->parentCityCode;
    }

    function getPriority()
    {
        return $this->priority;
    }

    function getImage()
    {
        return $this->image;
    }

    function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    function setCityCode($cityCode)
    {
        $this->cityCode = $cityCode;
    }

    function setCityName($cityName)
    {
        $this->cityName = $cityName;
    }

    function setState($state)
    {
        $this->state = $state;
    }

    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    function setCityId($cityId)
    {
        $this->cityId = $cityId;
    }

    function setDealApiSupplierId($dealApiSupplierId)
    {
        $this->dealApiSupplierId = $dealApiSupplierId;
    }

    function setParentCityCode($parentCityCode)
    {
        $this->parentCityCode = $parentCityCode;
    }

    function setPriority($priority)
    {
        $this->priority = $priority;
    }

    function setImage($image)
    {
        $this->image = $image;
    }

    function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;
    }

    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $value) {
            $toreturn[$key] = $value;
        }
        return $toreturn;
    }
}