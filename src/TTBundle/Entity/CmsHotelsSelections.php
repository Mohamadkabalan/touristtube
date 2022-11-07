<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsHotelsSelections
 *
 * @ORM\Table(name="cms_hotels_selections")
 * @ORM\Entity
 */
class CmsHotelsSelections
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
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private $count;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     */
    private $cityId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="selectionType", type="integer", nullable=false)
     */
    private $selectionType = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="small_img", type="string", length=255, nullable=false)
     */
    private $img;

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
     * Set count
     *
     * @param string $count
     *
     * @return CmsHotelsSelections
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return string
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return CmsHotelsSelections
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get cityId
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsHotelsSelections
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set SelectionType
     *
     * @param boolean $SelectionType
     *
     * @return CmsHotelsSelections
     */
    public function setSelectionType($SelectionType)
    {
        $this->selectionType = $SelectionType;

        return $this;
    }

    /**
     * Get selectionType
     *
     * @return boolean
     */
    public function getSelectionType()
    {
        return $this->selectionType;
    }

    /**
     * Set img
     *
     * @param string img
     *
     * @return CmsHotelsSelections
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }
}