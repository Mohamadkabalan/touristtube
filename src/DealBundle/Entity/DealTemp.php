<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealTemp
 *
 * @ORM\Table(name="deal_temp")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealTemp
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
     * @ORM\Column(name="activity_starting_place", type="string", nullable=true)
     */
    private $activityStartingPlace;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_highlights", type="string", nullable=true)
     */
    private $activityHighlights;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_id", type="string", nullable=true)
     */
    private $activityId;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_name", type="string", nullable=true)
     */
    private $activityName;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_img_main", type="string", nullable=true)
     */
    private $activityImgMain;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_price_adult", type="string", nullable=true)
     */
    private $activityPriceAdult;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_price_currency", type="string", nullable=true)
     */
    private $activityPriceCurrency;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_duration", type="string", nullable=false)
     */
    private $activityDuration;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_duration_text", type="string", nullable=false)
     */
    private $activityDurationText;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_availability_type	", type="string", nullable=true)
     */
    private $activityAvailabilityType;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_country", type="string", nullable=true)
     */
    private $activityCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_city", type="string", nullable=true)
     */
    private $activityCity;

    /**
     * @var string
     *
     * @ORM\Column(name="cancel_count", type="string", nullable=true)
     */
    private $cancelCount;

    /**
     * @var string
     *
     * @ORM\Column(name="percentage", type="string", nullable=false)
     */
    private $percentage;

    function getId()
    {
        return $this->id;
    }

    function getActivityStartingPlace()
    {
        return $this->activityStartingPlace;
    }

    function getActivityHighlights()
    {
        return $this->activityHighlights;
    }

    function getActivityId()
    {
        return $this->activityId;
    }

    function getActivityName()
    {
        return $this->activityName;
    }

    function getActivityImgMain()
    {
        return $this->activityImgMain;
    }

    function getActivityPriceAdult()
    {
        return $this->activityPriceAdult;
    }

    function getActivityPriceCurrency()
    {
        return $this->activityPriceCurrency;
    }

    function getActivityDuration()
    {
        return $this->activityDuration;
    }

    function getActivityDurationText()
    {
        return $this->activityDurationText;
    }

    function getActivityAvailabilityType()
    {
        return $this->activityAvailabilityType;
    }

    function getActivityCountry()
    {
        return $this->activityCountry;
    }

    function getActivityCity()
    {
        return $this->activityCity;
    }

    function getCancelCount()
    {
        return $this->cancelCount;
    }

    function getPercentage()
    {
        return $this->percentage;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setActivityStartingPlace($activityStartingPlace)
    {
        $this->activityStartingPlace = $activityStartingPlace;
    }

    function setActivityHighlights($activityHighlights)
    {
        $this->activityHighlights = $activityHighlights;
    }

    function setActivityId($activityId)
    {
        $this->activityId = $activityId;
    }

    function setActivityName($activityName)
    {
        $this->activityName = $activityName;
    }

    function setActivityImgMain($activityImgMain)
    {
        $this->activityImgMain = $activityImgMain;
    }

    function setActivityPriceAdult($activityPriceAdult)
    {
        $this->activityPriceAdult = $activityPriceAdult;
    }

    function setActivityPriceCurrency($activityPriceCurrency)
    {
        $this->activityPriceCurrency = $activityPriceCurrency;
    }

    function setActivityDuration($activityDuration)
    {
        $this->activityDuration = $activityDuration;
    }

    function setActivityDurationText($activityDurationText)
    {
        $this->activityDurationText = $activityDurationText;
    }

    function setActivityAvailabilityType($activityAvailabilityType)
    {
        $this->activityAvailabilityType = $activityAvailabilityType;
    }

    function setActivityCountry($activityCountry)
    {
        $this->activityCountry = $activityCountry;
    }

    function setActivityCity($activityCity)
    {
        $this->activityCity = $activityCity;
    }

    function setCancelCount($cancelCount)
    {
        $this->cancelCount = $cancelCount;
    }

    function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }
}