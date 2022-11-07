<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsHotelAmenity
 *
 * @ORM\Table(name="cms_hotel_amenity")
 * @ORM\Entity(repositoryClass="HotelBundle\Repository\HotelRepository")
 */
class CmsHotelAmenity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $hotelId = '0';

    /**
     * @var integer
     */
    private $amenityId = '0';

    /**
     * @var integer
     */
    private $countValue = '0';

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
     * Set hotelId
     *
     * @param integer $hotelId
     */
    public function setHotelId($hotelId)
    {
        $this->hotelId = $hotelId;
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
     * Set amenityId
     *
     * @param integer $amenityId
     */
    public function setAmenityId($amenityId)
    {
        $this->amenityId = $amenityId;
    }

    /**
     * Get amenityId
     *
     * @return integer
     */
    public function getAmenityId()
    {
        return $this->amenityId;
    }

    /**
     * Set countValue
     *
     * @param integer $countValue
     */
    public function setCountValue($countValue)
    {
        $this->countValue = $countValue;
    }

    /**
     * Get countValue
     *
     * @return integer
     */
    public function getCountValue()
    {
        return $this->countValue;
    }
}