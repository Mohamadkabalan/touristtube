<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * CmsThingstodoRegion
 *
 * @ORM\Table(name="cms_thingstodo_region", indexes={@ORM\Index(name="id", columns={"id"}), @ORM\Index(name="id_2", columns={"id"})})
 * @ORM\Entity
 */
class CmsThingstodoRegion
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
     * @ORM\Column(name="image", type="string", length=200, nullable=false)
     */
    private $image;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="alias_id", type="integer", nullable=false)
     */
    private $aliasId;

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
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';
    
    /**
     * @var string
     *
     * @ORM\Column(name="mobile_image", type="string", length=255, nullable=false)
     */
    private $mobileImage;

    /**
     * @var integer
     *
     * @ORM\Column(name="show_main", type="integer", nullable=false)
     */
    private $showMain = '0';

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
     * @return CmsThingstodoRegion
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
     * @return CmsThingstodoRegion
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
     * Set image
     *
     * @param string $image
     *
     * @return CmsThingstodoRegion
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

	/**
     * Set aliasId
     *
     * @param integer $aliasId
     *
     * @return CmsThingstodoRegion
     */
    public function setAliasId($aliasId)
    {
        $this->aliasId = $aliasId;

        return $this;
    }

    /**
     * Get aliasId
     *
     * @return integer
     */
    public function getAliasId()
    {
        return $this->aliasId;
    }

    /**
     * Set h3
     *
     * @param string $h3
     *
     * @return CmsThingstodo
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
     * @return CmsThingstodo
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
     * @return CmsThingstodo
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
     * @return CmsThingstodo
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
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsThingstodoRegion
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
     * Set mobileImage
     *
     * @param string $mobileImage
     *
     * @return CmsThingstodoRegion
     */
    public function setMobileImage($mobileImage)
    {
        $this->mobileImage = $mobileImage;

        return $this;
    }

    /**
     * Get mobileImage
     *
     * @return string
     */
    public function getMobileImage()
    {
        return $this->mobileImage;
    }
    /**
    * Set showMain
    *
    * @param integer $showMain
    *
    * @return CmsThingstodoRegion
    */
    public function setShowMain($showMain)
    {
        $this->showMain = $showMain;
        return $this;
    }

    /**
    * Get showMain
    *
    * @return integer
    */
    public function getShowMain()
    {
       return $this->showMain;
    }
}
