<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialNewsfeedHide
 *
 * @ORM\Table(name="cms_social_newsfeed_hide", indexes={@ORM\Index(name="feed_id", columns={"feed_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class CmsSocialNewsfeedHide
{
    /**
     * @var integer
     *
     * @ORM\Column(name="feed_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $feedId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $userId;



    /**
     * Set feedId
     *
     * @param integer $feedId
     *
     * @return CmsSocialNewsfeedHide
     */
    public function setFeedId($feedId)
    {
        $this->feedId = $feedId;

        return $this;
    }

    /**
     * Get feedId
     *
     * @return integer
     */
    public function getFeedId()
    {
        return $this->feedId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsSocialNewsfeedHide
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
}
