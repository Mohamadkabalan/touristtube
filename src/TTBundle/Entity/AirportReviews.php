<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AirportReviews
 *
 * @ORM\Table(name="airport_reviews")
 * @ORM\Entity
 */
class AirportReviews
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
     * @ORM\Column(name="airport_id", type="integer", nullable=false)
     */
    private $airportId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="hide_user", type="integer", nullable=false)
     */
    private $hideUser = '0';

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
     * Set airportId
     *
     * @param integer $airportId
     *
     * @return AirportReviews
     */
    public function setAirportId($airportId)
    {
        $this->airportId = $airportId;

        return $this;
    }

    /**
     * Get airportId
     *
     * @return integer
     */
    public function getAirportId()
    {
        return $this->airportId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return AirportReviews
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
     * Set title
     *
     * @param string $title
     *
     * @return AirportReviews
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return AirportReviews
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createTs
     *
     * @param \DateTime $createTs
     *
     * @return AirportReviews
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
     * Set hideUser
     *
     * @param boolean $hideUser
     *
     * @return AirportReviews
     */
    public function setHideUser($hideUser)
    {
        $this->hideUser = $hideUser;

        return $this;
    }

    /**
     * Get hideUser
     *
     * @return boolean
     */
    public function getHideUser()
    {
        return $this->hideUser;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return AirportReviews
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
