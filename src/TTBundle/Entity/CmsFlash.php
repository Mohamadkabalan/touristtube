<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsFlash
 *
 * @ORM\Table(name="cms_flash", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="longitude", columns={"longitude"}), @ORM\Index(name="latitude", columns={"latitude"}), @ORM\Index(name="flash_ts", columns={"flash_ts"}), @ORM\Index(name="reply_to", columns={"reply_to"}), @ORM\Index(name="city_id", columns={"city_id", "location_id"}), @ORM\Index(name="location_id", columns={"location_id"})})
 * @ORM\Entity
 */
class CmsFlash
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
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="flash_text", type="string", length=255, nullable=false)
     */
    private $flashText;

    /**
     * @var string
     *
     * @ORM\Column(name="flash_link", type="string", length=255, nullable=false)
     */
    private $flashLink;

    /**
     * @var string
     *
     * @ORM\Column(name="flash_location", type="string", length=255, nullable=false)
     */
    private $flashLocation;

    /**
     * @var string
     *
     * @ORM\Column(name="pic_file", type="string", length=128, nullable=false)
     */
    private $picFile;

    /**
     * @var string
     *
     * @ORM\Column(name="vpath", type="string", length=128, nullable=false)
     */
    private $vpath;

    /**
     * @var integer
     *
     * @ORM\Column(name="longitude", type="integer", nullable=true)
     */
    private $longitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="latitude", type="integer", nullable=true)
     */
    private $latitude;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="flash_ts", type="datetime", nullable=false)
     */
    private $flashTs = 'CURRENT_TIMESTAMP';

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
     * @var integer
     *
     * @ORM\Column(name="nb_comments", type="integer", nullable=false)
     */
    private $nbComments = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="n_replies", type="integer", nullable=false)
     */
    private $nReplies;

    /**
     * @var integer
     *
     * @ORM\Column(name="n_reflashes", type="integer", nullable=false)
     */
    private $nReflashes;

    /**
     * @var integer
     *
     * @ORM\Column(name="reply_to", type="integer", nullable=true)
     */
    private $replyTo;

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
     * @var boolean
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsFlash
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set flashText
     *
     * @param string $flashText
     *
     * @return CmsFlash
     */
    public function setFlashText($flashText)
    {
        $this->flashText = $flashText;

        return $this;
    }

    /**
     * Get flashText
     *
     * @return string
     */
    public function getFlashText()
    {
        return $this->flashText;
    }

    /**
     * Set flashLink
     *
     * @param string $flashLink
     *
     * @return CmsFlash
     */
    public function setFlashLink($flashLink)
    {
        $this->flashLink = $flashLink;

        return $this;
    }

    /**
     * Get flashLink
     *
     * @return string
     */
    public function getFlashLink()
    {
        return $this->flashLink;
    }

    /**
     * Set flashLocation
     *
     * @param string $flashLocation
     *
     * @return CmsFlash
     */
    public function setFlashLocation($flashLocation)
    {
        $this->flashLocation = $flashLocation;

        return $this;
    }

    /**
     * Get flashLocation
     *
     * @return string
     */
    public function getFlashLocation()
    {
        return $this->flashLocation;
    }

    /**
     * Set picFile
     *
     * @param string $picFile
     *
     * @return CmsFlash
     */
    public function setPicFile($picFile)
    {
        $this->picFile = $picFile;

        return $this;
    }

    /**
     * Get picFile
     *
     * @return string
     */
    public function getPicFile()
    {
        return $this->picFile;
    }

    /**
     * Set vpath
     *
     * @param string $vpath
     *
     * @return CmsFlash
     */
    public function setVpath($vpath)
    {
        $this->vpath = $vpath;

        return $this;
    }

    /**
     * Get vpath
     *
     * @return string
     */
    public function getVpath()
    {
        return $this->vpath;
    }

    /**
     * Set longitude
     *
     * @param integer $longitude
     *
     * @return CmsFlash
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return integer
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param integer $latitude
     *
     * @return CmsFlash
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return integer
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set flashTs
     *
     * @param \DateTime $flashTs
     *
     * @return CmsFlash
     */
    public function setFlashTs($flashTs)
    {
        $this->flashTs = $flashTs;

        return $this;
    }

    /**
     * Get flashTs
     *
     * @return \DateTime
     */
    public function getFlashTs()
    {
        return $this->flashTs;
    }

    /**
     * Set upVotes
     *
     * @param integer $upVotes
     *
     * @return CmsFlash
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
     * @return CmsFlash
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
     * @return CmsFlash
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
     * Set nbComments
     *
     * @param integer $nbComments
     *
     * @return CmsFlash
     */
    public function setNbComments($nbComments)
    {
        $this->nbComments = $nbComments;

        return $this;
    }

    /**
     * Get nbComments
     *
     * @return integer
     */
    public function getNbComments()
    {
        return $this->nbComments;
    }

    /**
     * Set nReplies
     *
     * @param integer $nReplies
     *
     * @return CmsFlash
     */
    public function setNReplies($nReplies)
    {
        $this->nReplies = $nReplies;

        return $this;
    }

    /**
     * Get nReplies
     *
     * @return integer
     */
    public function getNReplies()
    {
        return $this->nReplies;
    }

    /**
     * Set nReflashes
     *
     * @param integer $nReflashes
     *
     * @return CmsFlash
     */
    public function setNReflashes($nReflashes)
    {
        $this->nReflashes = $nReflashes;

        return $this;
    }

    /**
     * Get nReflashes
     *
     * @return integer
     */
    public function getNReflashes()
    {
        return $this->nReflashes;
    }

    /**
     * Set replyTo
     *
     * @param integer $replyTo
     *
     * @return CmsFlash
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * Get replyTo
     *
     * @return integer
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return CmsFlash
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
     * @return CmsFlash
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
     * @return CmsFlash
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
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsFlash
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
}
