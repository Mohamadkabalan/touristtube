<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HotelToHotelDivisions
 *
 * @ORM\Table(name="hotel_to_hotel_divisions")
 * @ORM\Entity
 */
class HotelToHotelDivisions
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotel_id", type="integer", nullable=false)
     */
    private $hotelId;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotel_division_id", type="integer", nullable=false)
     */
    private $hotelDivisionId;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort_order", type="integer", nullable=false)
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return HotelToHotelDivisions
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set HotelId
     *
     * @param integer $hotelId
     *
     * @return HotelToHotelDivisions
     */
    public function setHotelId($hotelId)
    {
        $this->hotelId = $hotelId;

        return $this;
    }

    /**
     * Get HotelId
     *
     * @return integer
     */
    public function getHotelId()
    {
        return $this->hotelId;
    }

    /**
     * Set HotelDivisionId
     *
     * @param integer $hotelDivisionId
     *
     * @return HotelToHotelDivisions
     */
    public function setHotelDivisionId($hotelDivisionId)
    {
        $this->hotelDivisionId = $hotelDivisionId;

        return $this;
    }

    /**
     * Get HotelDivisionId
     *
     * @return integer
     */
    public function getHotelDivisionId()
    {
        return $this->hotelDivisionId;
    }

    /**
     * Set SortOrder
     *
     * @param integer $sortOrder
     *
     * @return HotelDivisions
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get SortOrder
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }
}
