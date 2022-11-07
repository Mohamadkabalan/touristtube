<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsJournalsItems
 *
 * @ORM\Table(name="cms_journals_items", indexes={@ORM\Index(name="journal_id", columns={"journal_id"}), @ORM\Index(name="latitude", columns={"latitude", "longitude"}), @ORM\Index(name="city_id", columns={"city_id", "location_id", "location_name"}), @ORM\Index(name="location_id", columns={"location_id"})})
 * @ORM\Entity
 */
class CmsJournalsItems
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
     * @ORM\Column(name="name", type="string", length=200, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="journal_id", type="integer", nullable=false)
     */
    private $journalId;

    /**
     * @var string
     *
     * @ORM\Column(name="pic_vpath", type="string", length=255, nullable=false)
     */
    private $picVpath;

    /**
     * @var string
     *
     * @ORM\Column(name="item_pic", type="string", length=255, nullable=false)
     */
    private $itemPic;

    /**
     * @var string
     *
     * @ORM\Column(name="item_desc", type="string", length=255, nullable=false)
     */
    private $itemDesc;

    /**
     * @var integer
     *
     * @ORM\Column(name="item_order", type="integer", nullable=false)
     */
    private $itemOrder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="item_ts", type="datetime", nullable=false)
     */
    private $itemTs = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="default_pic", type="boolean", nullable=false)
     */
    private $defaultPic;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=true)
     */
    private $cityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="location_id", type="integer", nullable=true)
     */
    private $locationId;

    /**
     * @var string
     *
     * @ORM\Column(name="location_name", type="string", length=255, nullable=false)
     */
    private $locationName;

    /**
     * @var integer
     *
     * @ORM\Column(name="up_votes", type="integer", nullable=false)
     */
    private $upVotes;

    /**
     * @var integer
     *
     * @ORM\Column(name="down_votes", type="integer", nullable=false)
     */
    private $downVotes;

    /**
     * @var integer
     *
     * @ORM\Column(name="like_value", type="integer", nullable=false)
     */
    private $likeValue;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="text", length=65535, nullable=true)
     */
    private $keywords;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';



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
     * Set name
     *
     * @param string $name
     *
     * @return CmsJournalsItems
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Set journalId
     *
     * @param integer $journalId
     *
     * @return CmsJournalsItems
     */
    public function setJournalId($journalId)
    {
        $this->journalId = $journalId;

        return $this;
    }

    /**
     * Get journalId
     *
     * @return integer
     */
    public function getJournalId()
    {
        return $this->journalId;
    }

    /**
     * Set picVpath
     *
     * @param string $picVpath
     *
     * @return CmsJournalsItems
     */
    public function setPicVpath($picVpath)
    {
        $this->picVpath = $picVpath;

        return $this;
    }

    /**
     * Get picVpath
     *
     * @return string
     */
    public function getPicVpath()
    {
        return $this->picVpath;
    }

    /**
     * Set itemPic
     *
     * @param string $itemPic
     *
     * @return CmsJournalsItems
     */
    public function setItemPic($itemPic)
    {
        $this->itemPic = $itemPic;

        return $this;
    }

    /**
     * Get itemPic
     *
     * @return string
     */
    public function getItemPic()
    {
        return $this->itemPic;
    }

    /**
     * Set itemDesc
     *
     * @param string $itemDesc
     *
     * @return CmsJournalsItems
     */
    public function setItemDesc($itemDesc)
    {
        $this->itemDesc = $itemDesc;

        return $this;
    }

    /**
     * Get itemDesc
     *
     * @return string
     */
    public function getItemDesc()
    {
        return $this->itemDesc;
    }

    /**
     * Set itemOrder
     *
     * @param integer $itemOrder
     *
     * @return CmsJournalsItems
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
     * Set itemTs
     *
     * @param \DateTime $itemTs
     *
     * @return CmsJournalsItems
     */
    public function setItemTs($itemTs)
    {
        $this->itemTs = $itemTs;

        return $this;
    }

    /**
     * Get itemTs
     *
     * @return \DateTime
     */
    public function getItemTs()
    {
        return $this->itemTs;
    }

    /**
     * Set defaultPic
     *
     * @param boolean $defaultPic
     *
     * @return CmsJournalsItems
     */
    public function setDefaultPic($defaultPic)
    {
        $this->defaultPic = $defaultPic;

        return $this;
    }

    /**
     * Get defaultPic
     *
     * @return boolean
     */
    public function getDefaultPic()
    {
        return $this->defaultPic;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return CmsJournalsItems
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return CmsJournalsItems
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return CmsJournalsItems
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
     * Set locationId
     *
     * @param integer $locationId
     *
     * @return CmsJournalsItems
     */
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * Get locationId
     *
     * @return integer
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * Set locationName
     *
     * @param string $locationName
     *
     * @return CmsJournalsItems
     */
    public function setLocationName($locationName)
    {
        $this->locationName = $locationName;

        return $this;
    }

    /**
     * Get locationName
     *
     * @return string
     */
    public function getLocationName()
    {
        return $this->locationName;
    }

    /**
     * Set upVotes
     *
     * @param integer $upVotes
     *
     * @return CmsJournalsItems
     */
    public function setUpVotes($upVotes)
    {
        $this->upVotes = $upVotes;

        return $this;
    }

    /**
     * Get upVotes
     *
     * @return integer
     */
    public function getUpVotes()
    {
        return $this->upVotes;
    }

    /**
     * Set downVotes
     *
     * @param integer $downVotes
     *
     * @return CmsJournalsItems
     */
    public function setDownVotes($downVotes)
    {
        $this->downVotes = $downVotes;

        return $this;
    }

    /**
     * Get downVotes
     *
     * @return integer
     */
    public function getDownVotes()
    {
        return $this->downVotes;
    }

    /**
     * Set likeValue
     *
     * @param integer $likeValue
     *
     * @return CmsJournalsItems
     */
    public function setLikeValue($likeValue)
    {
        $this->likeValue = $likeValue;

        return $this;
    }

    /**
     * Get likeValue
     *
     * @return integer
     */
    public function getLikeValue()
    {
        return $this->likeValue;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     *
     * @return CmsJournalsItems
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set published
     *
     * @param integer $published
     *
     * @return CmsJournalsItems
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return integer
     */
    public function getPublished()
    {
        return $this->published;
    }
}
