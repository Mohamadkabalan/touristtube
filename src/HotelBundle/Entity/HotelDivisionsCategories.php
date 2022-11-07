<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HotelDivisionsCategories
 *
 * @ORM\Table(name="hotel_divisions_categories")
 * @ORM\Entity
 */
class HotelDivisionsCategories
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
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotel_division_category_group_id", type="integer", nullable=false)
     */
    private $hotelDivisionCategoryGroupId;

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
     * @return HotelDivisionsCategories
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get HotelDivisionCategoryGroupId
     *
     * @return integer
     */
    public function getHotelDivisionCategoryGroupId()
    {
        return $this->hotelDivisionCategoryGroupId;
    }

    /**
     * Set HotelDivisionCategoryGroupId
     *
     * @param integer $hotelDivisionCategoryGroupId
     *
     * @return HotelDivisionsCategories
     */
    public function setHotelDivisionCategoryGroupId($hotelDivisionCategoryGroupId)
    {
        $this->hotelDivisionCategoryGroupId = $hotelDivisionCategoryGroupId;

        return $this;
    }
}
