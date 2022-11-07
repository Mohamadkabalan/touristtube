<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HotelDivisions
 *
 * @ORM\Table(name="hotel_divisions")
 * @ORM\Entity
 */
class HotelDivisions
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
     * @ORM\Column(name="hotel_division_category_id", type="integer", nullable=false)
     */
    private $hotelDivisionCategoryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="ota_id", type="integer", nullable=true)
     */
    private $otaId;

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
     * @return HotelDivisions
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set HotelDivisionCategoryId
     *
     * @param integer $hotelDivisionCategoryId
     *
     * @return HotelDivisions
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

    /**
     * Set ParentId
     *
     * @param integer $parentId
     *
     * @return HotelDivisions
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get ParentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set OtaId
     *
     * @param integer $otaId
     *
     * @return HotelDivisions
     */
    public function setOtaId($otaId)
    {
        $this->otaId = $otaId;

        return $this;
    }

    /**
     * Get OtaId
     *
     * @return integer
     */
    public function getOtaId()
    {
        return $this->otaId;
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
