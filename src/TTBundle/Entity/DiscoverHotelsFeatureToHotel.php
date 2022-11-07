<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverHotelsFeatureToHotel
 *
 * @ORM\Table(name="discover_hotels_feature_to_hotel", uniqueConstraints={@ORM\UniqueConstraint(name="hotel_id", columns={"hotel_id", "hotel_feature_id"})}, indexes={@ORM\Index(name="hotel_feature_id", columns={"hotel_feature_id"})})
 * @ORM\Entity
 */
class DiscoverHotelsFeatureToHotel
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
     * @ORM\Column(name="hotel_id", type="integer", nullable=false)
     */
    private $hotelId;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotel_feature_id", type="integer", nullable=false)
     */
    private $hotelFeatureId;



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
     *
     * @return DiscoverHotelsFeatureToHotel
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
     * Set hotelFeatureId
     *
     * @param integer $hotelFeatureId
     *
     * @return DiscoverHotelsFeatureToHotel
     */
    public function setHotelFeatureId($hotelFeatureId)
    {
        $this->hotelFeatureId = $hotelFeatureId;

        return $this;
    }

    /**
     * Get hotelFeatureId
     *
     * @return integer
     */
    public function getHotelFeatureId()
    {
        return $this->hotelFeatureId;
    }
}
