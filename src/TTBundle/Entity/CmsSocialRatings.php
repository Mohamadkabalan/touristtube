<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialRatings
 *
 * @ORM\Table(name="cms_social_ratings", indexes={@ORM\Index(name="channel_id", columns={"channel_id"}), @ORM\Index(name="single_rate", columns={"user_id"})})
 * @ORM\Entity
 */
class CmsSocialRatings
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
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=false)
     */
    private $entityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="rate_type", type="integer", nullable=false)
     */
    private $rateType;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_type", type="integer", nullable=false)
     */
    private $entityType;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating_value", type="smallint", nullable=false)
     */
    private $ratingValue = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rating_ts", type="datetime", nullable=false)
     */
    private $ratingTs = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="rating_status", type="smallint", nullable=false)
     */
    private $ratingStatus = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="channel_id", type="integer", nullable=true)
     */
    private $channelId;



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
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsSocialRatings
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set entityId
     *
     * @param integer $entityId
     *
     * @return CmsSocialRatings
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return integer
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set rateType
     *
     * @param integer $rateType
     *
     * @return CmsSocialRatings
     */
    public function setRateType($rateType)
    {
        $this->rateType = $rateType;

        return $this;
    }

    /**
     * Get rateType
     *
     * @return boolean
     */
    public function getRateType()
    {
        return $this->rateType;
    }

    /**
     * Set entityType
     *
     * @param integer $entityType
     *
     * @return CmsSocialRatings
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * Get entityType
     *
     * @return boolean
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Set ratingValue
     *
     * @param integer $ratingValue
     *
     * @return CmsSocialRatings
     */
    public function setRatingValue($ratingValue)
    {
        $this->ratingValue = $ratingValue;

        return $this;
    }

    /**
     * Get ratingValue
     *
     * @return integer
     */
    public function getRatingValue()
    {
        return $this->ratingValue;
    }

    /**
     * Set ratingTs
     *
     * @param \DateTime $ratingTs
     *
     * @return CmsSocialRatings
     */
    public function setRatingTs($ratingTs)
    {
        $this->ratingTs = $ratingTs;

        return $this;
    }

    /**
     * Get ratingTs
     *
     * @return \DateTime
     */
    public function getRatingTs()
    {
        return $this->ratingTs;
    }

    /**
     * Set ratingStatus
     *
     * @param integer $ratingStatus
     *
     * @return CmsSocialRatings
     */
    public function setRatingStatus($ratingStatus)
    {
        $this->ratingStatus = $ratingStatus;

        return $this;
    }

    /**
     * Get ratingStatus
     *
     * @return integer
     */
    public function getRatingStatus()
    {
        return $this->ratingStatus;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsSocialRatings
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set channelId
     *
     * @param integer $channelId
     *
     * @return CmsSocialRatings
     */
    public function setChannelId($channelId)
    {
        $this->channelId = $channelId;

        return $this;
    }

    /**
     * Get channelId
     *
     * @return integer
     */
    public function getChannelId()
    {
        return $this->channelId;
    }
}
