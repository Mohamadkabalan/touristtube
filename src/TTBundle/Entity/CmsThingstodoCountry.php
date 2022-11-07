<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsThingstodoCountry
 *
 * @ORM\Table(name="cms_thingstodo_country", indexes={@ORM\Index(name="id", columns={"id"}), @ORM\Index(name="id_2", columns={"id"})})
 * @ORM\Entity
 */
class CmsThingstodoCountry
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
     * @var \DateTime
     *
     * @ORM\Column(name="create_ts", type="datetime", nullable=false)
     */
    private $createTs = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=false)
     */
    private $lastModified = 'CURRENT_TIMESTAMP';

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
     * @ORM\Column(name="alias_id", type="integer", nullable=false)
     */
    private $aliasId;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_display", type="integer", nullable=false)
     */
    private $orderDisplay = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=false)
     */
    private $parentId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=2)
     */
    private $countryCode = '';

    /**
     * @var string
     *
     * @ORM\Column(name="state_code", type="string", length=20)
     */
    private $stateCode = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="bigint", nullable=true)
     */
    private $cityId = '0';

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
     * @return CmsThingstodoCountry
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
     * @return CmsThingstodoCountry
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
     * @return CmsThingstodoCountry
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
     * Set createTs
     *
     * @param \DateTime $createTs
     *
     * @return CmsThingstodoCountry
     */
    public function setCreateTs($createTs)
    {
        $this->createTs = $createTs;

        return $this;
    }

    /**
     * Get createTs
     *
     * @return \DateTime
     */
    public function getCreateTs()
    {
        return $this->createTs;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsThingstodoCountry
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
     * Set lastModified
     *
     * @param \DateTime $lastModified
     *
     * @return CmsThingstodoCountry
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    /**
     * Get lastModified
     *
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * Set h3
     *
     * @param string $h3
     *
     * @return CmsThingstodoCountry
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
     * @return CmsThingstodoCountry
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
     * @return CmsThingstodoCountry
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
     * @return CmsThingstodoCountry
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
     * Set aliasId
     *
     * @param integer $aliasId
     *
     * @return CmsThingstodoCountry
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
     * Set orderDisplay
     *
     * @param integer $orderDisplay
     *
     * @return CmsThingstodoCountry
     */
    public function setOrderDisplay($orderDisplay)
    {
        $this->orderDisplay = $orderDisplay;

        return $this;
    }

    /**
     * Get orderDisplay
     *
     * @return integer
     */
    public function getOrderDisplay()
    {
        return $this->orderDisplay;
    }
    
    /**
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return CmsThingstodoCountry
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
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return CmsThingstodoCountry
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set stateCode
     *
     * @param string $stateCode
     *
     * @return CmsThingstodoCountry
     */
    public function setStateCode($stateCode)
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    /**
     * Get stateCode
     *
     * @return string
     */
    public function getStateCode()
    {
        return $this->stateCode;
    }
    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return CmsThingstodoCountry
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
    * Set showMain
    *
    * @param integer $showMain
    *
    * @return CmsThingstodoCountry
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
