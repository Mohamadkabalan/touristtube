<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThingstodoDivision
 *
 * @ORM\Table(name="thingstodo_division")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\DiscoverQueryRQRepository")
 */
class ThingstodoDivision
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="division_category_id", type="integer", nullable=false)
     */
    private $divisionCategoryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="ttd_id", type="integer", nullable=true)
     */
    private $ttdId;

    /**
     * @var string
     */
    private $mediaSettings;

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
     * @return ThingstodoDivision
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set divisionCategoryId
     *
     * @param integer $divisionCategoryId
     *
     * @return ThingstodoDivision
     */
    public function setDivisionCategoryId($divisionCategoryId)
    {
        $this->divisionCategoryId = $divisionCategoryId;

        return $this;
    }

    /**
     * Get divisionCategoryId
     *
     * @return integer
     */
    public function getDivisionCategoryId()
    {
        return $this->divisionCategoryId;
    }

    /**
     * Set ParentId
     *
     * @param integer $parentId
     *
     * @return ThingstodoDivision
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
     * Set ttdId
     *
     * @param integer $ttdId
     *
     * @return ThingstodoDivision
     */
    public function setTtdId($ttdId)
    {
        $this->ttdId = $ttdId;

        return $this;
    }

    /**
     * Get ttdId
     *
     * @return integer
     */
    public function getTtdId()
    {
        return $this->ttdId;
    }

    /**
     * Set mediaSettings
     *
     * @param string $mediaSettings
     *
     * @return ThingstodoDivision
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
     * Set SortOrder
     *
     * @param integer $sortOrder
     *
     * @return ThingstodoDivision
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
