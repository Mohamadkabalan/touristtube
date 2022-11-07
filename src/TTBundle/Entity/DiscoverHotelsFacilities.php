<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverHotelsFacilities
 *
 * @ORM\Table(name="discover_hotels_facilities")
 * @ORM\Entity
 */
class DiscoverHotelsFacilities
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
     * @ORM\Column(name="facility_id", type="integer", nullable=false)
     */
    private $facilityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotel_id", type="integer", nullable=false)
     */
    private $hotelId;



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
     * Set facilityId
     *
     * @param integer $facilityId
     *
     * @return DiscoverHotelsFacilities
     */
    public function setFacilityId($facilityId)
    {
        $this->facilityId = $facilityId;

        return $this;
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

    /**
     * Set hotelId
     *
     * @param integer $hotelId
     *
     * @return DiscoverHotelsFacilities
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
}
