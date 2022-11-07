<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HotelToHotelDivisionsCategories
 *
 * @ORM\Table(name="hotel_to_hotel_divisions_categories")
 * @ORM\Entity
 */
class HotelToHotelDivisionsCategories
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
     * @ORM\Column(name="hotel_division_category_id", type="integer", nullable=false)
     */
    private $hotelDivisionCategoryId;

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
     * @return HotelDivisions
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
     * @return HotelToHotelDivisionsCategories
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
     * Set HotelDivisionCategoryId
     *
     * @param integer $hotelDivisionCategoryId
     *
     * @return HotelToHotelDivisionsCategories
     */
    public function setHotelDivisionCategoryId($hotelDivisionCategoryId)
    {
        $this->hotelDivisionCategoryId = $hotelDivisionCategoryId;

        return $this;
    }

    /**
     * Get HotelDivisionCategoryId
     *
     * @return integer
     */
    public function getHotelDivisionCategoryId()
    {
        return $this->hotelDivisionCategoryId;
    }
}
