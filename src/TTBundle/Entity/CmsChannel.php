<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsChannel
 *
 * @ORM\Table(name="cms_channel", indexes={@ORM\Index(name="owner_id", columns={"owner_id"})})
 * @ORM\Entity(repositoryClass="TTBundle\Repository\ChannelRepository")
 */
class CmsChannel
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
     * @ORM\Column(name="channel_name", type="string", length=128, nullable=false)
     */
    private $channelName;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=128, nullable=false)
     */
    private $logo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_ts", type="datetime", nullable=false)
     */
    private $createTs = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="owner_id", type="integer", nullable=true)
     */
    private $ownerId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="small_description", type="text", length=65535, nullable=false)
     */
    private $smallDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="channel_url", type="string", length=64, nullable=false)
     */
    private $channelUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="header", type="string", length=100, nullable=false)
     */
    private $header;

    /**
     * @var string
     *
     * @ORM\Column(name="bg", type="string", length=100, nullable=false)
     */
    private $bg;

    /**
     * @var string
     *
     * @ORM\Column(name="default_link", type="string", length=100, nullable=false)
     */
    private $defaultLink;

    /**
     * @var string
     *
     * @ORM\Column(name="slogan", type="string", length=110, nullable=false)
     */
    private $slogan;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=2, nullable=false)
     */
    private $country;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     */
    private $cityId;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=10, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=254, nullable=false)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=10, nullable=false)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=false)
     */
    private $phone;

    /**
     * @var integer
     *
     * @ORM\Column(name="category", type="integer", nullable=false)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="text", length=65535, nullable=false)
     */
    private $keywords;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deactivated_ts", type="datetime", nullable=false)
     */
    private $deactivatedTs = '0000-00-00 00:00:00';

    /**
     * @var string
     *
     * @ORM\Column(name="bgcolor", type="string", length=6, nullable=false)
     */
    private $bgcolor;

    /**
     * @var string
     *
     * @ORM\Column(name="coverlink", type="string", length=254, nullable=false)
     */
    private $coverlink;

    /**
     * @var integer
     *
     * @ORM\Column(name="cover_id", type="bigint", nullable=false)
     */
    private $coverId;

    /**
     * @var integer
     *
     * @ORM\Column(name="profile_id", type="bigint", nullable=false)
     */
    private $profileId;

    /**
     * @var integer
     *
     * @ORM\Column(name="slogan_id", type="bigint", nullable=false)
     */
    private $sloganId;

    /**
     * @var integer
     *
     * @ORM\Column(name="info_id", type="bigint", nullable=false)
     */
    private $infoId;

    /**
     * @var integer
     *
     * @ORM\Column(name="hidecreatedon", type="integer", nullable=false)
     */
    private $hidecreatedon = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="hidecreatedby", type="integer", nullable=false)
     */
    private $hidecreatedby = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="hidelocation", type="integer", nullable=false)
     */
    private $hidelocation = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_visible", type="boolean", nullable=false)
     */
    private $channelVisible = '1';

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
    private $nbComments;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_email", type="string", length=100, nullable=false)
     */
    private $notificationEmail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=false)
     */
    private $lastModified = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_type", type="boolean", nullable=false)
     */
    private $channelType = '1';

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
     * Set channelName
     *
     * @param string $channelName
     *
     * @return CmsChannel
     */
    public function setChannelName($channelName)
    {
        $this->channelName = $channelName;

        return $this;
    }

    /**
     * Get channelName
     *
     * @return string
     */
    public function getChannelName()
    {
        return $this->channelName;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return CmsChannel
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set createTs
     *
     * @param \DateTime $createTs
     *
     * @return CmsChannel
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
     * Set ownerId
     *
     * @param integer $ownerId
     *
     * @return CmsChannel
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    /**
     * Get ownerId
     *
     * @return integer
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsChannel
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
     * Set smallDescription
     *
     * @param string $smallDescription
     *
     * @return CmsChannel
     */
    public function setSmallDescription($smallDescription)
    {
        $this->smallDescription = $smallDescription;

        return $this;
    }

    /**
     * Get smallDescription
     *
     * @return string
     */
    public function getSmallDescription()
    {
        return $this->smallDescription;
    }

    /**
     * Set channelUrl
     *
     * @param string $channelUrl
     *
     * @return CmsChannel
     */
    public function setChannelUrl($channelUrl)
    {
        $this->channelUrl = $channelUrl;

        return $this;
    }

    /**
     * Get channelUrl
     *
     * @return string
     */
    public function getChannelUrl()
    {
        return $this->channelUrl;
    }

    /**
     * Set header
     *
     * @param string $header
     *
     * @return CmsChannel
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set bg
     *
     * @param string $bg
     *
     * @return CmsChannel
     */
    public function setBg($bg)
    {
        $this->bg = $bg;

        return $this;
    }

    /**
     * Get bg
     *
     * @return string
     */
    public function getBg()
    {
        return $this->bg;
    }

    /**
     * Set defaultLink
     *
     * @param string $defaultLink
     *
     * @return CmsChannel
     */
    public function setDefaultLink($defaultLink)
    {
        $this->defaultLink = $defaultLink;

        return $this;
    }

    /**
     * Get defaultLink
     *
     * @return string
     */
    public function getDefaultLink()
    {
        return $this->defaultLink;
    }

    /**
     * Set slogan
     *
     * @param string $slogan
     *
     * @return CmsChannel
     */
    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;

        return $this;
    }

    /**
     * Get slogan
     *
     * @return string
     */
    public function getSlogan()
    {
        return $this->slogan;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return CmsChannel
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
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return CmsChannel
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
     * Set city
     *
     * @param string $city
     *
     * @return CmsChannel
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return CmsChannel
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return CmsChannel
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return CmsChannel
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set category
     *
     * @param integer $category
     *
     * @return CmsChannel
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     *
     * @return CmsChannel
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
     * Set deactivatedTs
     *
     * @param \DateTime $deactivatedTs
     *
     * @return CmsChannel
     */
    public function setDeactivatedTs($deactivatedTs)
    {
        $this->deactivatedTs = $deactivatedTs;

        return $this;
    }

    /**
     * Get deactivatedTs
     *
     * @return \DateTime
     */
    public function getDeactivatedTs()
    {
        return $this->deactivatedTs;
    }

    /**
     * Set bgcolor
     *
     * @param string $bgcolor
     *
     * @return CmsChannel
     */
    public function setBgcolor($bgcolor)
    {
        $this->bgcolor = $bgcolor;

        return $this;
    }

    /**
     * Get bgcolor
     *
     * @return string
     */
    public function getBgcolor()
    {
        return $this->bgcolor;
    }

    /**
     * Set coverlink
     *
     * @param string $coverlink
     *
     * @return CmsChannel
     */
    public function setCoverlink($coverlink)
    {
        $this->coverlink = $coverlink;

        return $this;
    }

    /**
     * Get coverlink
     *
     * @return string
     */
    public function getCoverlink()
    {
        return $this->coverlink;
    }

    /**
     * Set coverId
     *
     * @param integer $coverId
     *
     * @return CmsChannel
     */
    public function setCoverId($coverId)
    {
        $this->coverId = $coverId;

        return $this;
    }

    /**
     * Get coverId
     *
     * @return integer
     */
    public function getCoverId()
    {
        return $this->coverId;
    }

    /**
     * Set profileId
     *
     * @param integer $profileId
     *
     * @return CmsChannel
     */
    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;

        return $this;
    }

    /**
     * Get profileId
     *
     * @return integer
     */
    public function getProfileId()
    {
        return $this->profileId;
    }

    /**
     * Set sloganId
     *
     * @param integer $sloganId
     *
     * @return CmsChannel
     */
    public function setSloganId($sloganId)
    {
        $this->sloganId = $sloganId;

        return $this;
    }

    /**
     * Get sloganId
     *
     * @return integer
     */
    public function getSloganId()
    {
        return $this->sloganId;
    }

    /**
     * Set infoId
     *
     * @param integer $infoId
     *
     * @return CmsChannel
     */
    public function setInfoId($infoId)
    {
        $this->infoId = $infoId;

        return $this;
    }

    /**
     * Get infoId
     *
     * @return integer
     */
    public function getInfoId()
    {
        return $this->infoId;
    }

    /**
     * Set hidecreatedon
     *
     * @param integer $hidecreatedon
     *
     * @return CmsChannel
     */
    public function setHidecreatedon($hidecreatedon)
    {
        $this->hidecreatedon = $hidecreatedon;

        return $this;
    }

    /**
     * Get hidecreatedon
     *
     * @return integer
     */
    public function getHidecreatedon()
    {
        return $this->hidecreatedon;
    }

    /**
     * Set hidecreatedby
     *
     * @param integer $hidecreatedby
     *
     * @return CmsChannel
     */
    public function setHidecreatedby($hidecreatedby)
    {
        $this->hidecreatedby = $hidecreatedby;

        return $this;
    }

    /**
     * Get hidecreatedby
     *
     * @return integer
     */
    public function getHidecreatedby()
    {
        return $this->hidecreatedby;
    }

    /**
     * Set hidelocation
     *
     * @param integer $hidelocation
     *
     * @return CmsChannel
     */
    public function setHidelocation($hidelocation)
    {
        $this->hidelocation = $hidelocation;

        return $this;
    }

    /**
     * Get hidelocation
     *
     * @return integer
     */
    public function getHidelocation()
    {
        return $this->hidelocation;
    }

    /**
     * Set channelVisible
     *
     * @param boolean $channelVisible
     *
     * @return CmsChannel
     */
    public function setChannelVisible($channelVisible)
    {
        $this->channelVisible = $channelVisible;

        return $this;
    }

    /**
     * Get channelVisible
     *
     * @return boolean
     */
    public function getChannelVisible()
    {
        return $this->channelVisible;
    }

    /**
     * Set likeValue
     *
     * @param integer $likeValue
     *
     * @return CmsChannel
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
     * @return CmsChannel
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
     * @return CmsChannel
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
     * @return CmsChannel
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
     * @return CmsChannel
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
     * Set notificationEmail
     *
     * @param string $notificationEmail
     *
     * @return CmsChannel
     */
    public function setNotificationEmail($notificationEmail)
    {
        $this->notificationEmail = $notificationEmail;

        return $this;
    }

    /**
     * Get notificationEmail
     *
     * @return string
     */
    public function getNotificationEmail()
    {
        return $this->notificationEmail;
    }

    /**
     * Set lastModified
     *
     * @param \DateTime $lastModified
     *
     * @return CmsChannel
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
     * Set channelType
     *
     * @param boolean $channelType
     *
     * @return CmsChannel
     */
    public function setChannelType($channelType)
    {
        $this->channelType = $channelType;

        return $this;
    }

    /**
     * Get channelType
     *
     * @return boolean
     */
    public function getChannelType()
    {
        return $this->channelType;
    }
}
