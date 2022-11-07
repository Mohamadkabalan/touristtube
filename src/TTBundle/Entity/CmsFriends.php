<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsFriends
 *
 * @ORM\Table(name="cms_friends", uniqueConstraints={@ORM\UniqueConstraint(name="unq_frnd_rel_key", columns={"requester_id", "receipient_id"})}, indexes={@ORM\Index(name="requester_id", columns={"requester_id"}), @ORM\Index(name="receipient_id", columns={"receipient_id"})})
 * @ORM\Entity
 */
class CmsFriends
{
    /**
     * @var integer
     *
     * @ORM\Column(name="requester_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $requesterId;

    /**
     * @var integer
     *
     * @ORM\Column(name="receipient_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $receipientId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="request_ts", type="datetime", nullable=false)
     */
    private $requestTs = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="notify", type="smallint", nullable=false)
     */
    private $notify = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="blocked", type="boolean", nullable=false)
     */
    private $blocked;

    /**
     * @var boolean
     *
     * @ORM\Column(name="profile_blocked", type="boolean", nullable=false)
     */
    private $profileBlocked = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="request_msg", type="string", length=255, nullable=false)
     */
    private $requestMsg;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_visible", type="boolean", nullable=false)
     */
    private $isVisible = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=false)
     */
    private $lastModified = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';



    /**
     * Set requesterId
     *
     * @param integer $requesterId
     *
     * @return CmsFriends
     */
    public function setRequesterId($requesterId)
    {
        $this->requesterId = $requesterId;

        return $this;
    }

    /**
     * Get requesterId
     *
     * @return integer
     */
    public function getRequesterId()
    {
        return $this->requesterId;
    }

    /**
     * Set receipientId
     *
     * @param integer $receipientId
     *
     * @return CmsFriends
     */
    public function setReceipientId($receipientId)
    {
        $this->receipientId = $receipientId;

        return $this;
    }

    /**
     * Get receipientId
     *
     * @return integer
     */
    public function getReceipientId()
    {
        return $this->receipientId;
    }

    /**
     * Set requestTs
     *
     * @param \DateTime $requestTs
     *
     * @return CmsFriends
     */
    public function setRequestTs($requestTs)
    {
        $this->requestTs = $requestTs;

        return $this;
    }

    /**
     * Get requestTs
     *
     * @return \DateTime
     */
    public function getRequestTs()
    {
        return $this->requestTs;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return CmsFriends
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set notify
     *
     * @param integer $notify
     *
     * @return CmsFriends
     */
    public function setNotify($notify)
    {
        $this->notify = $notify;

        return $this;
    }

    /**
     * Get notify
     *
     * @return integer
     */
    public function getNotify()
    {
        return $this->notify;
    }

    /**
     * Set blocked
     *
     * @param boolean $blocked
     *
     * @return CmsFriends
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;

        return $this;
    }

    /**
     * Get blocked
     *
     * @return boolean
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * Set profileBlocked
     *
     * @param boolean $profileBlocked
     *
     * @return CmsFriends
     */
    public function setProfileBlocked($profileBlocked)
    {
        $this->profileBlocked = $profileBlocked;

        return $this;
    }

    /**
     * Get profileBlocked
     *
     * @return boolean
     */
    public function getProfileBlocked()
    {
        return $this->profileBlocked;
    }

    /**
     * Set requestMsg
     *
     * @param string $requestMsg
     *
     * @return CmsFriends
     */
    public function setRequestMsg($requestMsg)
    {
        $this->requestMsg = $requestMsg;

        return $this;
    }

    /**
     * Get requestMsg
     *
     * @return string
     */
    public function getRequestMsg()
    {
        return $this->requestMsg;
    }

    /**
     * Set isVisible
     *
     * @param boolean $isVisible
     *
     * @return CmsFriends
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
     * Set lastModified
     *
     * @param \DateTime $lastModified
     *
     * @return CmsFriends
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    /**
     * Get lastModified
     *
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsFriends
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
}
