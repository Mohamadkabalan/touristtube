<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverRestaurantsImages
 *
 * @ORM\Table(name="discover_restaurants_images", indexes={@ORM\Index(name="hotel_id", columns={"old_id"})})
 * @ORM\Entity
 */
class DiscoverRestaurantsImages
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
    private $userId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @var integer
     *
     * @ORM\Column(name="restaurant_id", type="integer", nullable=false)
     */
    private $restaurantId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="old_id", type="integer", nullable=false)
     */
    private $oldId;

    /**
     * @var integer
     *
     * @ORM\Column(name="default_pic", type="integer", nullable=false)
     */
    private $defaultPic = '0';



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
     * @return DiscoverRestaurantsImages
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
     * Set filename
     *
     * @param string $filename
     *
     * @return DiscoverRestaurantsImages
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set restaurantId
     *
     * @param integer $restaurantId
     *
     * @return DiscoverRestaurantsImages
     */
    public function setRestaurantId($restaurantId)
    {
        $this->restaurantId = $restaurantId;

        return $this;
    }

    /**
     * Get restaurantId
     *
     * @return integer
     */
    public function getRestaurantId()
    {
        return $this->restaurantId;
    }

    /**
     * Set oldId
     *
     * @param integer $oldId
     *
     * @return DiscoverRestaurantsImages
     */
    public function setOldId($oldId)
    {
        $this->oldId = $oldId;

        return $this;
    }

    /**
     * Get oldId
     *
     * @return integer
     */
    public function getOldId()
    {
        return $this->oldId;
    }

    /**
     * Set defaultPic
     *
     * @param integer $defaultPic
     *
     * @return DiscoverRestaurantsImages
     */
    public function setDefaultPic($defaultPic)
    {
        $this->defaultPic = $defaultPic;

        return $this;
    }

    /**
     * Get defaultPic
     *
     * @return integer
     */
    public function getDefaultPic()
    {
        return $this->defaultPic;
    }
}
