<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealBookingQuote
 *
 * @ORM\Table(name="deal_booking_quote")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 * @ORM\Entity
 */
class DealBookingQuote
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="package_id", type="integer", nullable=false)
     */
    private $packageId;

    /**
     * @var string
     *
     * @ORM\Column(name="tour_code", type="string", length=45, nullable=false)
     */
    private $tourCode;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_price_id", type="string", length=45, nullable=false)
     */
    private $activityPriceId;

    /**
     * @var string
     *
     * @ORM\Column(name="price_id", type="string", length=45, nullable=false)
     */
    private $priceId;

    /**
     * @var string
     *
     * @ORM\Column(name="quote_key", type="string", length=150, nullable=false)
     */
    private $quoteKey;

    /**
     * @var decimal
     *
     * @ORM\Column(name="total", type="decimal", nullable=false)
     */
    private $total;

    /**
     * @var string
     *
     * @ORM\Column(name="time_id", type="string", length=45, nullable=true)
     */
    private $timeId;

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="string", length=20, nullable=true)
     */
    private $time;

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
     * @var string
     *
     * @ORM\Column(name="dynamic_fields", type="text", nullable=true)
     */
    private $dynamicFields;

    /**
     * @var string
     *
     * @ORM\Column(name="dynamic_fields_values", type="text", nullable=true)
     */
    private $dynamicFieldsValues;

    function getId()
    {
        return $this->id;
    }

    function getPackageId()
    {
        return $this->packageId;
    }

    function getTourCode()
    {
        return $this->tourCode;
    }

    function getActivityPriceId()
    {
        return $this->activityPriceId;
    }

    function getPriceId()
    {
        return $this->priceId;
    }

    function getQuoteKey()
    {
        return $this->quoteKey;
    }

    function getTotal()
    {
        return $this->total;
    }

    function getTimeId()
    {
        return $this->timeId;
    }

    function getTime()
    {
        return $this->time;
    }

    function getCreatedAt()
    {
        return $this->createdAt;
    }

    function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    function getDynamicFields()
    {
        return $this->dynamicFields;
    }

    function getDynamicFieldsValues()
    {
        return $this->dynamicFieldsValues;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setPackageId($packageId)
    {
        $this->packageId = $packageId;
    }

    function setTourCode($tourCode)
    {
        $this->tourCode = $tourCode;
    }

    function setActivityPriceId($activityPriceId)
    {
        $this->activityPriceId = $activityPriceId;
    }

    function setPriceId($priceId)
    {
        $this->priceId = $priceId;
    }

    function setQuoteKey($quoteKey)
    {
        $this->quoteKey = $quoteKey;
    }

    function setTotal($total)
    {
        $this->total = $total;
    }

    function setTimeId($timeId)
    {
        $this->timeId = $timeId;
    }

    function setTime($time)
    {
        $this->time = $time;
    }

    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    function setDynamicFields($dynamicFields)
    {
        $this->dynamicFields = $dynamicFields;
    }

    function setDynamicFieldsValues($dynamicFieldsValues)
    {
        $this->dynamicFieldsValues = $dynamicFieldsValues;
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