<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealDetailsItinerary
 *
 * @ORM\Table(name="deal_details_itinerary")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealDetailsItinerary
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
     * @ORM\Column(name="deal_details_id", type="int", length=11, nullable=false)
     */
    private $dealDetailsId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="breakfast_include", type="int", length=4, nullable=false)
     */
    private $breakfastInclude;

    /**
     * @var integer
     *
     * @ORM\Column(name="lunch_include", type="int", length=4, nullable=false)
     */
    private $lunchInclude;

    /**
     * @var integer
     *
     * @ORM\Column(name="dinner_include", type="int", length=4, nullable=false)
     */
    private $dinnerInclude;

    /**
     * @var string
     *
     * @ORM\Column(name="hotel_code", type="string", length=100, nullable=true)
     */
    private $hotelCode;

    /**
     * @var string
     *
     * @ORM\Column(name="hotel_name", type="string", length=100, nullable=true)
     */
    private $hotelName;

    /**
     * @var string
     *
     * @ORM\Column(name="item_code", type="string", length=100, nullable=true)
     */
    private $itemCode;

    /**
     * @var string
     *
     * @ORM\Column(name="item_type", type="string", length=100, nullable=true)
     */
    private $itemType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime", nullable=true)
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime", nullable=true)
     */
    private $end;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=100, nullable=true)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100, nullable=true)
     */
    private $status;

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

    function getId()
    {
        return $this->id;
    }

    function getDealDetailsId()
    {
        return $this->dealDetailsId;
    }

    function getTitle()
    {
        return $this->title;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getBreakfastInclude()
    {
        return $this->breakfastInclude;
    }

    function getLunchInclude()
    {
        return $this->lunchInclude;
    }

    function getDinnerInclude()
    {
        return $this->dinnerInclude;
    }

    function getHotelCode()
    {
        return $this->hotelCode;
    }

    function getHotelName()
    {
        return $this->hotelName;
    }

    function getItemCode()
    {
        return $this->itemCode;
    }

    function getItemType()
    {
        return $this->itemType;
    }

    function getStart()
    {
        return $this->start;
    }

    function getEnd()
    {
        return $this->end;
    }

    function getDuration()
    {
        return $this->duration;
    }

    function getStatus()
    {
        return $this->status;
    }

    function getCreatedAt()
    {
        return $this->createdAt;
    }

    function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setDealDetailsId($dealDetailsId)
    {
        $this->dealDetailsId = $dealDetailsId;
    }

    function setTitle($title)
    {
        $this->title = $title;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    function setBreakfastInclude($breakfastInclude)
    {
        $this->breakfastInclude = $breakfastInclude;
    }

    function setLunchInclude($lunchInclude)
    {
        $this->lunchInclude = $lunchInclude;
    }

    function setDinnerInclude($dinnerInclude)
    {
        $this->dinnerInclude = $dinnerInclude;
    }

    function setHotelCode($hotelCode)
    {
        $this->hotelCode = $hotelCode;
    }

    function setHotelName($hotelName)
    {
        $this->hotelName = $hotelName;
    }

    function setItemCode($itemCode)
    {
        $this->itemCode = $itemCode;
    }

    function setItemType($itemType)
    {
        $this->itemType = $itemType;
    }

    function setStart($start)
    {
        $this->start = $start;
    }

    function setEnd($end)
    {
        $this->end = $end;
    }

    function setDuration($duration)
    {
        $this->duration = $duration;
    }

    function setStatus($status)
    {
        $this->status = $status;
    }

    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}