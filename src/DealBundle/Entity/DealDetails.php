<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealDetails
 *
 * @ORM\Table(name="deal_details")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealDetails
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
     * @ORM\Column(name="deal_code", type="string", length=45, nullable=true)
     */
    private $dealCode;

    /**
     * @var string
     *
     * @ORM\Column(name="deal_name", type="string", length=100, nullable=true)
     */
    private $dealName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="datetime", nullable=true)
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="datetime", nullable=true)
     */
    private $endTime;

    /**
     * @var bigint
     *
     * @ORM\Column(name="country_id", type="bigint", length=20, nullable=true)
     */
    private $countryId;

    /**
     * @var bigint
     *
     * @ORM\Column(name="deal_city_id", type="bigint", length=20, nullable=true)
     */
    private $dealCityId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var bigint
     *
     * @ORM\Column(name="deal_type_id", type="bigint", length=20, nullable=true)
     */
    private $dealTypeId;

    /**
     * @var bigint
     *
     * @ORM\Column(name="deal_api_id", type="bigint", length=20, nullable=true)
     */
    private $dealApiId;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="int", length=11, nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=100, nullable=true)
     */
    private $currency;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", nullable=false)
     */
    private $priority;

    /**
     * @var int
     *
     * @ORM\Column(name="price_before_promo", type="int", length=11, nullable=true)
     */
    private $priceBeforePromo;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=100, nullable=true)
     */
    private $duration;

    /*
     * @var string
     *
     * @ORM\Column(name="supplier", type="string", length=255, nullable=true)

      private $supplier;

      function getSupplier()
      {
      return $this->supplier;
      }

      function setSupplier($supplier)
      {
      $this->supplier = $supplier;
      } */

    function getId()
    {
        return $this->id;
    }

    function getDealCode()
    {
        return $this->dealCode;
    }

    function getDealName()
    {
        return $this->dealName;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getStartTime()
    {
        return $this->startTime;
    }

    function getEndTime()
    {
        return $this->endTime;
    }

    function getCountryId()
    {
        return $this->countryId;
    }

    function getDealCityId()
    {
        return $this->dealCityId;
    }

    function getCreatedAt()
    {
        return $this->createdAt;
    }

    function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    function getDealTypeId()
    {
        return $this->dealTypeId;
    }

    function getDealApiId()
    {
        return $this->dealApiId;
    }

    function getPrice()
    {
        return $this->price;
    }

    function getCurrency()
    {
        return $this->currency;
    }

    function getPublished()
    {
        return $this->published;
    }

    function getPriority()
    {
        return $this->priority;
    }

    function getPriceBeforePromo()
    {
        return $this->priceBeforePromo;
    }

    function getLatitude()
    {
        return $this->latitude;
    }

    function getLongitude()
    {
        return $this->longitude;
    }

    function getDuration()
    {
        return $this->duration;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setDealCode($dealCode)
    {
        $this->dealCode = $dealCode;
    }

    function setDealName($dealName)
    {
        $this->dealName = $dealName;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    function setCountryId($countryId)
    {
        $this->countryId = $countryId;
    }

    function setDealCityId($dealCityId)
    {
        $this->dealCityId = $dealCityId;
    }

    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    function setDealTypeId($dealTypeId)
    {
        $this->dealTypeId = $dealTypeId;
    }

    function setDealApiId($dealApiId)
    {
        $this->dealApiId = $dealApiId;
    }

    function setPrice($price)
    {
        $this->price = $price;
    }

    function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    function setPublished($published)
    {
        $this->published = $published;
    }

    function setPriority($priority)
    {
        $this->priority = $priority;
    }

    function setPriceBeforePromo($priceBeforePromo)
    {
        $this->priceBeforePromo = $priceBeforePromo;
    }

    function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    function setDuration($duration)
    {
        $this->duration = $duration;
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
