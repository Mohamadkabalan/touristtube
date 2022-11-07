<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsHotelFacility
 *
 * @ORM\Table(name="cms_hotel_facility")
 * @ORM\Entity(repositoryClass="HotelBundle\Repository\HRSRepository")
 */
class CmsHotelFacility
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
    private $facilityId = '0';

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
     * Set facilityId
     *
     * @param integer $facilityId
     */
    public function setFacilityId($facilityId)
    {
        $this->facilityId = $facilityId;
    }

    /**
     * Get facilityId
     *
     * @return integer
     */
    public function getFacilityId()
    {
        return $this->facilityId;
    }
}
