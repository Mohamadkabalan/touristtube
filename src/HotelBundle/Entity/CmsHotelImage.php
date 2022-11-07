<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsHotelImage
 *
 * @ORM\Table(name="cms_hotel_image", indexes={@ORM\Index(name="hotel_id", columns={"hotel_id"})})
 * @ORM\Entity(repositoryClass="HotelBundle\Repository\HRSRepository")
 */
class CmsHotelImage
{
    /**
     * @var integer
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
     */
    private $filename = '';

    /**
     * @var integer
     */
    private $hotelId = '0';

    /**
     * @var integer
     */
    private $hotelDivisionId;

    /**
     * @var integer
     */
    private $mediaTypeId;

    /**
     * @var string
     */
    private $mediaSettings;

    /**
     * @var string
     */
    private $location;

    /**
     * @var integer
     */
    private $defaultPic = '0';

    /**
     * @var integer
     */
    private $isFeatured;
    
    /**
     * @var integer
     */
    private $sortOrder;

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
     * @return CmsHotelImage
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
     * @return CmsHotelImage
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
     * @return CmsHotelImage
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
     * Set hotelDivisionId
     *
     * @param integer $hotelDivisionId
     *
     * @return CmsHotelImage
     */
    public function setHotelDivisionId($hotelDivisionId)
    {
        $this->hotelDivisionId = $hotelDivisionId;

        return $this;
    }

    /**
     * Get hotelDivisionId
     *
     * @return integer
     */
    public function getHotelDivisionId()
    {
        return $this->hotelDivisionId;
    }

    /**
     * Set mediaTypeId
     *
     * @param integer $mediaTypeId
     *
     * @return AmadeusHotelImage
     */
    public function setMediaTypeId($mediaTypeId)
    {
        $this->mediaTypeId = $mediaTypeId;

        return $this;
    }

    /**
     * Get mediaTypeId
     *
     * @return integer
     */
    public function getMediaTypeId()
    {
        return $this->mediaTypeId;
    }

    /**
     * Set mediaSettings
     *
     * @param string $mediaSettings
     *
     * @return AmadeusHotelImage
     */
    public function setMediaSettings($mediaSettings)
    {
        $this->mediaSettings = $mediaSettings;

        return $this;
    }

    /**
     * Get mediaSettings
     *
     * @return string
     */
    public function getMediaSettings()
    {
        return $this->mediaSettings;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return CmsHotelImage
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set defaultPic
     *
     * @param integer $defaultPic
     *
     * @return CmsHotelImage
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

    /**
     * Check if hotel image is featured
     * @return Boolean
     */
    public function isFeatured()
    {
        return $this->isFeatured;
    }

    /**
     * Set isFeatured
     * @param Boolean $isFeatured
     */
    public function setFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;
    }
    
    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     *
     * @return CmsHotelImage
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }
}
