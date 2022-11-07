<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialNewsfeedGroup
 *
 * @ORM\Table(name="cms_social_newsfeed_group")
 * @ORM\Entity
 */
class CmsSocialNewsfeedGroup
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
     * @ORM\Column(name="owner_id", type="integer", nullable=false)
     */
    private $ownerId;

    /**
     * @var integer
     *
     * @ORM\Column(name="action_id", type="integer", nullable=false)
     */
    private $actionId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="action_type", type="boolean", nullable=false)
     */
    private $actionType;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=false)
     */
    private $entityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_type", type="integer", nullable=false)
     */
    private $entityType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="feed_ts", type="datetime", nullable=false)
     */
    private $feedTs = '0000-00-00 00:00:00';

    /**
     * @var integer
     *
     * @ORM\Column(name="feed_privacy", type="integer", nullable=false)
     */
    private $feedPrivacy = '2';

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
     * @var boolean
     *
     * @ORM\Column(name="is_visible", type="boolean", nullable=false)
     */
    private $isVisible = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="entity_group", type="string", length=255, nullable=false)
     */
    private $entityGroup;



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
     * @return CmsSocialNewsfeedGroup
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
     * Set ownerId
     *
     * @param integer $ownerId
     *
     * @return CmsSocialNewsfeedGroup
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    /**
     * Get ownerId
     *
     * @return integer
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * Set actionId
     *
     * @param integer $actionId
     *
     * @return CmsSocialNewsfeedGroup
     */
    public function setActionId($actionId)
    {
        $this->actionId = $actionId;

        return $this;
    }

    /**
     * Get actionId
     *
     * @return integer
     */
    public function getActionId()
    {
        return $this->actionId;
    }

    /**
     * Set actionType
     *
     * @param boolean $actionType
     *
     * @return CmsSocialNewsfeedGroup
     */
    public function setActionType($actionType)
    {
        $this->actionType = $actionType;

        return $this;
    }

    /**
     * Get actionType
     *
     * @return boolean
     */
    public function getActionType()
    {
        return $this->actionType;
    }

    /**
     * Set entityId
     *
     * @param integer $entityId
     *
     * @return CmsSocialNewsfeedGroup
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
     * Set entityType
     *
     * @param integer $entityType
     *
     * @return CmsSocialNewsfeedGroup
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
     * Set feedTs
     *
     * @param \DateTime $feedTs
     *
     * @return CmsSocialNewsfeedGroup
     */
    public function setFeedTs($feedTs)
    {
        $this->feedTs = $feedTs;

        return $this;
    }

    /**
     * Get feedTs
     *
     * @return \DateTime
     */
    public function getFeedTs()
    {
        return $this->feedTs;
    }

    /**
     * Set feedPrivacy
     *
     * @param integer $feedPrivacy
     *
     * @return CmsSocialNewsfeedGroup
     */
    public function setFeedPrivacy($feedPrivacy)
    {
        $this->feedPrivacy = $feedPrivacy;

        return $this;
    }

    /**
     * Get feedPrivacy
     *
     * @return integer
     */
    public function getFeedPrivacy()
    {
        return $this->feedPrivacy;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsSocialNewsfeedGroup
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
     * @return CmsSocialNewsfeedGroup
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

    /**
     * Set isVisible
     *
     * @param boolean $isVisible
     *
     * @return CmsSocialNewsfeedGroup
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * Get isVisible
     *
     * @return boolean
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }

    /**
     * Set entityGroup
     *
     * @param string $entityGroup
     *
     * @return CmsSocialNewsfeedGroup
     */
    public function setEntityGroup($entityGroup)
    {
        $this->entityGroup = $entityGroup;

        return $this;
    }

    /**
     * Get entityGroup
     *
     * @return string
     */
    public function getEntityGroup()
    {
        return $this->entityGroup;
    }
}
