<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialShares
 *
 * @ORM\Table(name="cms_social_shares", indexes={@ORM\Index(name="from_user", columns={"from_user"}), @ORM\Index(name="share_fk", columns={"entity_id"}), @ORM\Index(name="channel_id", columns={"channel_id"})})
 * @ORM\Entity
 */
class CmsSocialShares
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
     * @ORM\Column(name="from_user", type="integer", nullable=false)
     */
    private $fromUser;

    /**
     * @var string
     *
     * @ORM\Column(name="all_users", type="text", length=65535, nullable=false)
     */
    private $allUsers;

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
     * @ORM\Column(name="share_ts", type="datetime", nullable=false)
     */
    private $shareTs = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="msg", type="string", length=512, nullable=false)
     */
    private $msg;

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
     * @ORM\Column(name="share_type", type="boolean", nullable=false)
     */
    private $shareType = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_visible", type="boolean", nullable=false)
     */
    private $isVisible = '1';



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
     * Set fromUser
     *
     * @param integer $fromUser
     *
     * @return CmsSocialShares
     */
    public function setFromUser($fromUser)
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    /**
     * Get fromUser
     *
     * @return integer
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Set allUsers
     *
     * @param string $allUsers
     *
     * @return CmsSocialShares
     */
    public function setAllUsers($allUsers)
    {
        $this->allUsers = $allUsers;

        return $this;
    }

    /**
     * Get allUsers
     *
     * @return string
     */
    public function getAllUsers()
    {
        return $this->allUsers;
    }

    /**
     * Set entityId
     *
     * @param integer $entityId
     *
     * @return CmsSocialShares
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
     * @return CmsSocialShares
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
     * Set shareTs
     *
     * @param \DateTime $shareTs
     *
     * @return CmsSocialShares
     */
    public function setShareTs($shareTs)
    {
        $this->shareTs = $shareTs;

        return $this;
    }

    /**
     * Get shareTs
     *
     * @return \DateTime
     */
    public function getShareTs()
    {
        return $this->shareTs;
    }

    /**
     * Set msg
     *
     * @param string $msg
     *
     * @return CmsSocialShares
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;

        return $this;
    }

    /**
     * Get msg
     *
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsSocialShares
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
     * @return CmsSocialShares
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
     * Set shareType
     *
     * @param boolean $shareType
     *
     * @return CmsSocialShares
     */
    public function setShareType($shareType)
    {
        $this->shareType = $shareType;

        return $this;
    }

    /**
     * Get shareType
     *
     * @return boolean
     */
    public function getShareType()
    {
        return $this->shareType;
    }

    /**
     * Set isVisible
     *
     * @param boolean $isVisible
     *
     * @return CmsSocialShares
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
}
