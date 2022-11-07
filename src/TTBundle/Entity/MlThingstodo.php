<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * MlThingstodo
 *
 * @ORM\Table(name="ml_thingstodo", indexes={@ORM\Index(name="id", columns={"id"}), @ORM\Index(name="id_2", columns={"id"})})
 * @ORM\Entity
 */
class MlThingstodo
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=2, nullable=false)
     */
    private $language = 'fr';

    /**
     * @var string
     *
     * @ORM\Column(name="h3", type="string", length=255, nullable=false)
     */
    private $h3;
    
    /**
     * @var string
     *
     * @ORM\Column(name="p3", type="text", length=65535, nullable=false)
     */
    private $p3;

    /**
     * @var string
     *
     * @ORM\Column(name="h4", type="string", length=255, nullable=false)
     */
    private $h4;
    
    /**
     * @var string
     *
     * @ORM\Column(name="p4", type="text", length=65535, nullable=false)
     */
    private $p4;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=false)
     */
    private $parentId;

    /**
     * @var string
     *
     * @ORM\Column(name="desc_thingstodo", type="text", length=65535, nullable=false)
     */
    private $descThingstodo;

    /**
     * @var string
     *
     * @ORM\Column(name="desc_discover", type="text", length=65535, nullable=false)
     */
    private $descDiscover;

    /**
     * @var string
     *
     * @ORM\Column(name="desc_hotelsin", type="text", length=65535, nullable=false)
     */
    private $descHotelsin;
    
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
     * @return MlThingstodo
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
     * Set description
     *
     * @param string $description
     *
     * @return MlThingstodo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return MlThingstodo
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
    
    /**
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return MlThingstodo
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set h3
     *
     * @param string $h3
     *
     * @return MlThingstodo
     */
    public function setH3($h3)
    {
        $this->h3 = $h3;

        return $this;
    }

    /**
     * Get h3
     *
     * @return string
     */
    public function getH3()
    {
        return $this->h3;
    }

    /**
     * Set p3
     *
     * @param string $p3
     *
     * @return MlThingstodo
     */
    public function setP3($p3)
    {
        $this->p3 = $p3;

        return $this;
    }

    /**
     * Get p3
     *
     * @return string
     */
    public function getP3()
    {
        return $this->p3;
    }

    /**
     * Set h4
     *
     * @param string $h4
     *
     * @return MlThingstodo
     */
    public function setH4($h4)
    {
        $this->h4 = $h4;

        return $this;
    }

    /**
     * Get h4
     *
     * @return string
     */
    public function getH4()
    {
        return $this->h4;
    }

    /**
     * Set p4
     *
     * @param string $p4
     *
     * @return MlThingstodo
     */
    public function setP4($p4)
    {
        $this->p4 = $p4;

        return $this;
    }

    /**
     * Get p4
     *
     * @return string
     */
    public function getP4()
    {
        return $this->p4;
    }

    /**
     * Set descThingstodo
     *
     * @param string $descThingstodo
     *
     * @return CmsThingstodo
     */
    public function setDescThingstodo($descThingstodo)
    {
        $this->descThingstodo = $descThingstodo;

        return $this;
    }

    /**
     * Get descThingstodo
     *
     * @return string
     */
    public function getDescThingstodo()
    {
        return $this->descThingstodo;
    }

    /**
     * Set descDiscover
     *
     * @param string $descDiscover
     *
     * @return CmsThingstodo
     */
    public function setDescDiscover($descDiscover)
    {
        $this->descDiscover = $descDiscover;

        return $this;
    }

    /**
     * Get descDiscover
     *
     * @return string
     */
    public function getDescDiscover()
    {
        return $this->descDiscover;
    }

    /**
     * Set descHotelsin
     *
     * @param string $descHotelsin
     *
     * @return CmsThingstodo
     */
    public function setDescHotelsin($descHotelsin)
    {
        $this->descHotelsin = $descHotelsin;

        return $this;
    }

    /**
     * Get descHotelsin
     *
     * @return string
     */
    public function getDescHotelsin()
    {
        return $this->descHotelsin;
    }
}
