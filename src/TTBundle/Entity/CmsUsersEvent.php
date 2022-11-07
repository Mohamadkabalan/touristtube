<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsUsersEvent
 *
 * @ORM\Table(name="cms_users_event")
 * @ORM\Entity
 */
class CmsUsersEvent
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
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=128, nullable=false)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=128, nullable=false)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=2, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="location_detailed", type="string", length=255, nullable=false)
     */
    private $locationDetailed;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="lattitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $lattitude;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fromdate", type="datetime", nullable=false)
     */
    private $fromdate = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fromtime", type="time", nullable=false)
     */
    private $fromtime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="todate", type="datetime", nullable=false)
     */
    private $todate = '0000-00-00 00:00:00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="totime", type="time", nullable=false)
     */
    private $totime;

    /**
     * @var integer
     *
     * @ORM\Column(name="theme_id", type="integer", nullable=false)
     */
    private $themeId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="limitnumber", type="integer", nullable=false)
     */
    private $limitnumber = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="caninvite", type="integer", nullable=false)
     */
    private $caninvite = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="showguests", type="integer", nullable=false)
     */
    private $showguests = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="enable_share_comment", type="boolean", nullable=false)
     */
    private $enableShareComment = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="relativepath", type="string", length=200, nullable=false)
     */
    private $relativepath;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="like_value", type="integer", nullable=false)
     */
    private $likeValue;

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
     * @ORM\Column(name="nb_shares", type="integer", nullable=false)
     */
    private $nbShares;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_comments", type="integer", nullable=false)
     */
    private $nbComments = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_joining", type="integer", nullable=false)
     */
    private $nbJoining = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_visible", type="boolean", nullable=false)
     */
    private $isVisible = '1';



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
     * @return CmsUsersEvent
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
     * Set name
     *
     * @param string $name
     *
     * @return CmsUsersEvent
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
     * Set photo
     *
     * @param string $photo
     *
     * @return CmsUsersEvent
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CmsUsersEvent
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
     * Set location
     *
     * @param string $location
     *
     * @return CmsUsersEvent
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return CmsUsersEvent
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
     * Set locationDetailed
     *
     * @param string $locationDetailed
     *
     * @return CmsUsersEvent
     */
    public function setLocationDetailed($locationDetailed)
    {
        $this->locationDetailed = $locationDetailed;

        return $this;
    }

    /**
     * Get locationDetailed
     *
     * @return string
     */
    public function getLocationDetailed()
    {
        return $this->locationDetailed;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return CmsUsersEvent
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
     * Set lattitude
     *
     * @param float $lattitude
     *
     * @return CmsUsersEvent
     */
    public function setLattitude($lattitude)
    {
        $this->lattitude = $lattitude;

        return $this;
    }

    /**
     * Get lattitude
     *
     * @return float
     */
    public function getLattitude()
    {
        return $this->lattitude;
    }

    /**
     * Set fromdate
     *
     * @param \DateTime $fromdate
     *
     * @return CmsUsersEvent
     */
    public function setFromdate($fromdate)
    {
        $this->fromdate = $fromdate;

        return $this;
    }

    /**
     * Get fromdate
     *
     * @return \DateTime
     */
    public function getFromdate()
    {
        return $this->fromdate;
    }

    /**
     * Set fromtime
     *
     * @param \DateTime $fromtime
     *
     * @return CmsUsersEvent
     */
    public function setFromtime($fromtime)
    {
        $this->fromtime = $fromtime;

        return $this;
    }

    /**
     * Get fromtime
     *
     * @return \DateTime
     */
    public function getFromtime()
    {
        return $this->fromtime;
    }

    /**
     * Set todate
     *
     * @param \DateTime $todate
     *
     * @return CmsUsersEvent
     */
    public function setTodate($todate)
    {
        $this->todate = $todate;

        return $this;
    }

    /**
     * Get todate
     *
     * @return \DateTime
     */
    public function getTodate()
    {
        return $this->todate;
    }

    /**
     * Set totime
     *
     * @param \DateTime $totime
     *
     * @return CmsUsersEvent
     */
    public function setTotime($totime)
    {
        $this->totime = $totime;

        return $this;
    }

    /**
     * Get totime
     *
     * @return \DateTime
     */
    public function getTotime()
    {
        return $this->totime;
    }

    /**
     * Set themeId
     *
     * @param integer $themeId
     *
     * @return CmsUsersEvent
     */
    public function setThemeId($themeId)
    {
        $this->themeId = $themeId;

        return $this;
    }

    /**
     * Get themeId
     *
     * @return integer
     */
    public function getThemeId()
    {
        return $this->themeId;
    }

    /**
     * Set limitnumber
     *
     * @param integer $limitnumber
     *
     * @return CmsUsersEvent
     */
    public function setLimitnumber($limitnumber)
    {
        $this->limitnumber = $limitnumber;

        return $this;
    }

    /**
     * Get limitnumber
     *
     * @return integer
     */
    public function getLimitnumber()
    {
        return $this->limitnumber;
    }

    /**
     * Set caninvite
     *
     * @param integer $caninvite
     *
     * @return CmsUsersEvent
     */
    public function setCaninvite($caninvite)
    {
        $this->caninvite = $caninvite;

        return $this;
    }

    /**
     * Get caninvite
     *
     * @return integer
     */
    public function getCaninvite()
    {
        return $this->caninvite;
    }

    /**
     * Set showguests
     *
     * @param integer $showguests
     *
     * @return CmsUsersEvent
     */
    public function setShowguests($showguests)
    {
        $this->showguests = $showguests;

        return $this;
    }

    /**
     * Get showguests
     *
     * @return integer
     */
    public function getShowguests()
    {
        return $this->showguests;
    }

    /**
     * Set enableShareComment
     *
     * @param boolean $enableShareComment
     *
     * @return CmsUsersEvent
     */
    public function setEnableShareComment($enableShareComment)
    {
        $this->enableShareComment = $enableShareComment;

        return $this;
    }

    /**
     * Get enableShareComment
     *
     * @return boolean
     */
    public function getEnableShareComment()
    {
        return $this->enableShareComment;
    }

    /**
     * Set relativepath
     *
     * @param string $relativepath
     *
     * @return CmsUsersEvent
     */
    public function setRelativepath($relativepath)
    {
        $this->relativepath = $relativepath;

        return $this;
    }

    /**
     * Get relativepath
     *
     * @return string
     */
    public function getRelativepath()
    {
        return $this->relativepath;
    }

    /**
     * Set published
     *
     * @param integer $published
     *
     * @return CmsUsersEvent
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

    /**
     * Set likeValue
     *
     * @param integer $likeValue
     *
     * @return CmsUsersEvent
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
     * Set upVotes
     *
     * @param integer $upVotes
     *
     * @return CmsUsersEvent
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
     * @return CmsUsersEvent
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
     * Set nbShares
     *
     * @param integer $nbShares
     *
     * @return CmsUsersEvent
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
     * Set nbComments
     *
     * @param integer $nbComments
     *
     * @return CmsUsersEvent
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
     * Set nbJoining
     *
     * @param integer $nbJoining
     *
     * @return CmsUsersEvent
     */
    public function setNbJoining($nbJoining)
    {
        $this->nbJoining = $nbJoining;

        return $this;
    }

    /**
     * Get nbJoining
     *
     * @return integer
     */
    public function getNbJoining()
    {
        return $this->nbJoining;
    }

    /**
     * Set isVisible
     *
     * @param boolean $isVisible
     *
     * @return CmsUsersEvent
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
}
