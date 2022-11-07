<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealTemp
 *
 * @ORM\Table(name="deal_texts_temp")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealTextsTemp
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
     * @ORM\Column(name="activity_highlights", type="string", nullable=true)
     */
    private $activityHighlights;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_description", type="string", nullable=true)
     */
    private $activityDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_city", type="string", nullable=true)
     */
    private $activityCity;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_type", type="string", nullable=true)
     */
    private $activityType;

    function getId()
    {
        return $this->id;
    }

    function getActivityHighlights()
    {
        return $this->activityHighlights;
    }

    function setActivityHighlights($activityHighlights)
    {
        $this->activityHighlights = $activityHighlights;
    }

    function getActivityId()
    {
        return $this->activityId;
    }

    function getActivityName()
    {
        return $this->activityName;
    }

    function getActivityCity()
    {
        return $this->activityCity;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setActivityId($activityId)
    {
        $this->activityId = $activityId;
    }

    function setActivityName($activityName)
    {
        $this->activityName = $activityName;
    }

    function setActivityCity($activityCity)
    {
        $this->activityCity = $activityCity;
    }

    function getActivityDescription()
    {
        return $this->activityDescription;
    }

    function setActivityDescription($activityDescription)
    {
        $this->activityDescription = $activityDescription;
    }

    function getActivityType()
    {
        return $this->activityType;
    }

    function setActivityType($activityType)
    {
        $this->activityType = $activityType;
    }
}