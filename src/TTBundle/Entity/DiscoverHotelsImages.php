<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverHotelsImages
 *
 * @ORM\Table(name="discover_hotels_images", indexes={@ORM\Index(name="hotel_id", columns={"hotel_id"})})
 * @ORM\Entity
 */
class DiscoverHotelsImages
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
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotel_id", type="integer", nullable=false)
     */
    private $hotelId;

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
     * @return DiscoverHotelsImages
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
     * @return DiscoverHotelsImages
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
     * Set hotelId
     *
     * @param integer $hotelId
     *
     * @return DiscoverHotelsImages
     */
    public function setHotelId($hotelId)
    {
        $this->hotelId = $hotelId;

        return $this;
    }

    /**
     * Get hotelId
     *
     * @return integer
     */
    public function getHotelId()
    {
        return $this->hotelId;
    }

    /**
     * Set defaultPic
     *
     * @param integer $defaultPic
     *
     * @return DiscoverHotelsImages
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
