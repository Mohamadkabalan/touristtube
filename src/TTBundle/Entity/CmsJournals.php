<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsJournals
 *
 * @ORM\Table(name="cms_journals", indexes={@ORM\Index(name="user_id", columns={"user_id", "journal_link"}), @ORM\Index(name="latitude", columns={"latitude", "longitude", "city_id", "location_id"}), @ORM\Index(name="start_date", columns={"start_date", "end_date"}), @ORM\Index(name="location_id", columns={"location_id"}), @ORM\Index(name="city_id", columns={"city_id"})})
 * @ORM\Entity
 */
class CmsJournals
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
     * @ORM\Column(name="journal_name", type="string", length=128, nullable=false)
     */
    private $journalName;

    /**
     * @var string
     *
     * @ORM\Column(name="journal_desc", type="text", length=65535, nullable=false)
     */
    private $journalDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="journal_link", type="string", length=255, nullable=false)
     */
    private $journalLink;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="journal_ts", type="datetime", nullable=false)
     */
    private $journalTs = 'CURRENT_TIMESTAMP';

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
     * @ORM\Column(name="nb_shares", type="integer", nullable=false)
     */
    private $nbShares = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="is_public", type="integer", nullable=false)
     */
    private $isPublic;

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
     * @var string
     *
     * @ORM\Column(name="keywords", type="text", length=65535, nullable=false)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=2, nullable=false)
     */
    private $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=false)
     */
    private $endDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="built", type="smallint", nullable=false)
     */
    private $built;

    /**
     * @var string
     *
     * @ORM\Column(name="vpath", type="string", length=16, nullable=false)
     */
    private $vpath;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_visible", type="boolean", nullable=false)
     */
    private $isVisible = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="enable_share_comment", type="integer", nullable=false)
     */
    private $enableShareComment = '1';

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
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsJournals
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
     * Set journalName
     *
     * @param string $journalName
     *
     * @return CmsJournals
     */
    public function setJournalName($journalName)
    {
        $this->journalName = $journalName;

        return $this;
    }

    /**
     * Get journalName
     *
     * @return string
     */
    public function getJournalName()
    {
        return $this->journalName;
    }

    /**
     * Set journalDesc
     *
     * @param string $journalDesc
     *
     * @return CmsJournals
     */
    public function setJournalDesc($journalDesc)
    {
        $this->journalDesc = $journalDesc;

        return $this;
    }

    /**
     * Get journalDesc
     *
     * @return string
     */
    public function getJournalDesc()
    {
        return $this->journalDesc;
    }

    /**
     * Set journalLink
     *
     * @param string $journalLink
     *
     * @return CmsJournals
     */
    public function setJournalLink($journalLink)
    {
        $this->journalLink = $journalLink;

        return $this;
    }

    /**
     * Get journalLink
     *
     * @return string
     */
    public function getJournalLink()
    {
        return $this->journalLink;
    }

    /**
     * Set journalTs
     *
     * @param \DateTime $journalTs
     *
     * @return CmsJournals
     */
    public function setJournalTs($journalTs)
    {
        $this->journalTs = $journalTs;

        return $this;
    }

    /**
     * Get journalTs
     *
     * @return \DateTime
     */
    public function getJournalTs()
    {
        return $this->journalTs;
    }

    /**
     * Set upVotes
     *
     * @param integer $upVotes
     *
     * @return CmsJournals
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
     * @return CmsJournals
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
     * @return CmsJournals
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
     * @return CmsJournals
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
     * Set nbShares
     *
     * @param integer $nbShares
     *
     * @return CmsJournals
     */
    public function setNbShares($nbShares)
    {
        $this->nbShares = $nbShares;

        return $this;
    }

    /**
     * Get nbShares
     *
     * @return integer
     */
    public function getNbShares()
    {
        return $this->nbShares;
    }

    /**
     * Set isPublic
     *
     * @param integer $isPublic
     *
     * @return CmsJournals
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return integer
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return CmsJournals
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
     * @return CmsJournals
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
     * @return CmsJournals
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
     * @return CmsJournals
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
     * @return CmsJournals
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
     * Set keywords
     *
     * @param string $keywords
     *
     * @return CmsJournals
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
     * Set country
     *
     * @param string $country
     *
     * @return CmsJournals
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return CmsJournals
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return CmsJournals
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set built
     *
     * @param integer $built
     *
     * @return CmsJournals
     */
    public function setBuilt($built)
    {
        $this->built = $built;

        return $this;
    }

    /**
     * Get built
     *
     * @return integer
     */
    public function getBuilt()
    {
        return $this->built;
    }

    /**
     * Set vpath
     *
     * @param string $vpath
     *
     * @return CmsJournals
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
     * Set isVisible
     *
     * @param boolean $isVisible
     *
     * @return CmsJournals
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * Get isVisible
     *
     * @return boolean
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }

    /**
     * Set enableShareComment
     *
     * @param integer $enableShareComment
     *
     * @return CmsJournals
     */
    public function setEnableShareComment($enableShareComment)
    {
        $this->enableShareComment = $enableShareComment;

        return $this;
    }

    /**
     * Get enableShareComment
     *
     * @return integer
     */
    public function getEnableShareComment()
    {
        return $this->enableShareComment;
    }

    /**
     * Set published
     *
     * @param integer $published
     *
     * @return CmsJournals
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
