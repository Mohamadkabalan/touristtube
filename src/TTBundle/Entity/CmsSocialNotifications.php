<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialNotifications
 *
 * @ORM\Table(name="cms_social_notifications", indexes={@ORM\Index(name="poster_id", columns={"poster_id", "receiver_id"})})
 * @ORM\Entity
 */
class CmsSocialNotifications
{
    /**
     * @var integer
     *
     * @ORM\Column(name="poster_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $posterId;

    /**
     * @var integer
     *
     * @ORM\Column(name="receiver_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $receiverId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_channel", type="boolean")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $isChannel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="poster_is_channel", type="boolean")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $posterIsChannel = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_ts", type="datetime", nullable=false)
     */
    private $createTs = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notify", type="boolean", nullable=false)
     */
    private $notify = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="show_from_tuber", type="integer", nullable=false)
     */
    private $showFromTuber = '1';



    /**
     * Set posterId
     *
     * @param integer $posterId
     *
     * @return CmsSocialNotifications
     */
    public function setPosterId($posterId)
    {
        $this->posterId = $posterId;

        return $this;
    }

    /**
     * Get posterId
     *
     * @return integer
     */
    public function getPosterId()
    {
        return $this->posterId;
    }

    /**
     * Set receiverId
     *
     * @param integer $receiverId
     *
     * @return CmsSocialNotifications
     */
    public function setReceiverId($receiverId)
    {
        $this->receiverId = $receiverId;

        return $this;
    }

    /**
     * Get receiverId
     *
     * @return integer
     */
    public function getReceiverId()
    {
        return $this->receiverId;
    }

    /**
     * Set isChannel
     *
     * @param boolean $isChannel
     *
     * @return CmsSocialNotifications
     */
    public function setIsChannel($isChannel)
    {
        $this->isChannel = $isChannel;

        return $this;
    }

    /**
     * Get isChannel
     *
     * @return boolean
     */
    public function getIsChannel()
    {
        return $this->isChannel;
    }

    /**
     * Set posterIsChannel
     *
     * @param boolean $posterIsChannel
     *
     * @return CmsSocialNotifications
     */
    public function setPosterIsChannel($posterIsChannel)
    {
        $this->posterIsChannel = $posterIsChannel;

        return $this;
    }

    /**
     * Get posterIsChannel
     *
     * @return boolean
     */
    public function getPosterIsChannel()
    {
        return $this->posterIsChannel;
    }

    /**
     * Set createTs
     *
     * @param \DateTime $createTs
     *
     * @return CmsSocialNotifications
     */
    public function setCreateTs($createTs)
    {
        $this->createTs = $createTs;

        return $this;
    }

    /**
     * Get createTs
     *
     * @return \DateTime
     */
    public function getCreateTs()
    {
        return $this->createTs;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsSocialNotifications
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
     * Set notify
     *
     * @param boolean $notify
     *
     * @return CmsSocialNotifications
     */
    public function setNotify($notify)
    {
        $this->notify = $notify;

        return $this;
    }

    /**
     * Get notify
     *
     * @return boolean
     */
    public function getNotify()
    {
        return $this->notify;
    }

    /**
     * Set showFromTuber
     *
     * @param integer $showFromTuber
     *
     * @return CmsSocialNotifications
     */
    public function setShowFromTuber($showFromTuber)
    {
        $this->showFromTuber = $showFromTuber;

        return $this;
    }

    /**
     * Get showFromTuber
     *
     * @return integer
     */
    public function getShowFromTuber()
    {
        return $this->showFromTuber;
    }
}
