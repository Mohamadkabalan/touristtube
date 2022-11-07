<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsAllcategories
 *
 * @ORM\Table(name="cms_allcategories")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\PhotosVideosRepository")
 */
class CmsAllcategories
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="item_order", type="integer", nullable=false)
     */
    private $itemOrder;

    /**
     * @var integer
     *
     * @ORM\Column(name="show_home", type="integer", nullable=false)
     */
    private $showHome;



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
     * Set title
     *
     * @param string $title
     *
     * @return CmsAllcategories
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsAllcategories
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
     * Set itemOrder
     *
     * @param integer $itemOrder
     *
     * @return CmsAllcategories
     */
    public function setItemOrder($itemOrder)
    {
        $this->itemOrder = $itemOrder;

        return $this;
    }

    /**
     * Get itemOrder
     *
     * @return integer
     */
    public function getItemOrder()
    {
        return $this->itemOrder;
    }
    
    /**
     * Set showHome
     *
     * @param integer $showHome
     *
     * @return CmsAllcategories
     */
    public function setShowHome($showHome)
    {
        $this->showHome = $showHome;

        return $this;
    }

    /**
     * Get showHome
     *
     * @return integer
     */
    public function getShowHome()
    {
        return $this->showHome;
    }
}
