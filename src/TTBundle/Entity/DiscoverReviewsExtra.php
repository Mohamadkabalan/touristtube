<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverReviewsExtra
 *
 * @ORM\Table(name="discover_reviews_extra")
 * @ORM\Entity
 */
class DiscoverReviewsExtra
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
     * @ORM\Column(name="entity_type", type="integer", nullable=false)
     */
    private $entityType;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="text", length=65535, nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="near", type="text", length=65535, nullable=false)
     */
    private $near;

    /**
     * @var string
     *
     * @ORM\Column(name="theme", type="text", length=65535, nullable=false)
     */
    private $theme;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_ts", type="datetime", nullable=false)
     */
    private $createTs = 'CURRENT_TIMESTAMP';



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
     * @return DiscoverReviewsExtra
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
     * @return DiscoverReviewsExtra
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
     * @return DiscoverReviewsExtra
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
     * Set date
     *
     * @param string $date
     *
     * @return DiscoverReviewsExtra
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set near
     *
     * @param string $near
     *
     * @return DiscoverReviewsExtra
     */
    public function setNear($near)
    {
        $this->near = $near;

        return $this;
    }

    /**
     * Get near
     *
     * @return string
     */
    public function getNear()
    {
        return $this->near;
    }

    /**
     * Set theme
     *
     * @param string $theme
     *
     * @return DiscoverReviewsExtra
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set createTs
     *
     * @param \DateTime $createTs
     *
     * @return DiscoverReviewsExtra
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
}
