<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialSharesUsers
 *
 * @ORM\Table(name="cms_social_shares_users", indexes={@ORM\Index(name="share_id", columns={"share_id", "user_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class CmsSocialSharesUsers
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
     * @ORM\Column(name="share_id", type="integer", nullable=false)
     */
    private $shareId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';



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
     * Set shareId
     *
     * @param integer $shareId
     *
     * @return CmsSocialSharesUsers
     */
    public function setShareId($shareId)
    {
        $this->shareId = $shareId;

        return $this;
    }

    /**
     * Get shareId
     *
     * @return integer
     */
    public function getShareId()
    {
        return $this->shareId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsSocialSharesUsers
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
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsSocialSharesUsers
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
