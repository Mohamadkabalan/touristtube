<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsUsersCatalogs
 *
 * @ORM\Table(name="cms_users_catalogs", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="location_id", columns={"location_id"}), @ORM\Index(name="cityid", columns={"cityid"}), @ORM\Index(name="cityname", columns={"cityname"}), @ORM\Index(name="country", columns={"country"}), @ORM\Index(name="latitude", columns={"latitude"}), @ORM\Index(name="longitude", columns={"longitude"}), @ORM\Index(name="is_public", columns={"is_public"}), @ORM\Index(name="category", columns={"category"})})
 * @ORM\Entity(repositoryClass="TTBundle\Repository\PhotosVideosRepository")
 */
class CmsUsersCatalogs
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
     * @ORM\Column(name="catalog_name", type="string", length=100, nullable=false)
     */
    private $catalogName;

    /**
     * @var integer
     *
     * @ORM\Column(name="n_media", type="integer", nullable=false)
     */
    private $nMedia;

    /**
     * @var string
     *
     * @ORM\Column(name="placetakenat", type="string", length=255, nullable=false)
     */
    private $placetakenat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="catalog_ts", type="datetime", nullable=false)
     */
    private $catalogTs = 'CURRENT_TIMESTAMP';

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
    private $nbComments;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_views", type="integer", nullable=false)
     */
    private $nbViews;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_ratings", type="integer", nullable=false)
     */
    private $nbRatings;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", precision=10, scale=0, nullable=false)
     */
    private $rating;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_shares", type="integer", nullable=false)
     */
    private $nbShares = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="vpath", type="string", length=16, nullable=false)
     */
    private $vpath;

    /**
     * @var integer
     *
     * @ORM\Column(name="location_id", type="integer", nullable=true)
     */
    private $locationId;

    /**
     * @var integer
     *
     * @ORM\Column(name="cityid", type="integer", nullable=true)
     */
    private $cityid;

    /**
     * @var string
     *
     * @ORM\Column(name="cityname", type="string", length=64, nullable=false)
     */
    private $cityname;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=2, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

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
     * @ORM\Column(name="is_public", type="integer", nullable=false)
     */
    private $isPublic;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="text", length=65535, nullable=false)
     */
    private $keywords;

    /**
     * @var integer
     *
     * @ORM\Column(name="category", type="integer", nullable=true)
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="channelid", type="integer", nullable=false)
     */
    private $channelid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="album_url", type="string", length=255, nullable=false)
     */
    private $albumUrl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="can_comment", type="boolean", nullable=false)
     */
    private $canComment = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="can_share", type="boolean", nullable=false)
     */
    private $canShare = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="can_rate", type="boolean", nullable=false)
     */
    private $canRate = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="can_like", type="boolean", nullable=false)
     */
    private $canLike = '1';

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
     * @return CmsUsersCatalogs
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
     * Set catalogName
     *
     * @param string $catalogName
     *
     * @return CmsUsersCatalogs
     */
    public function setCatalogName($catalogName)
    {
        $this->catalogName = $catalogName;

        return $this;
    }

    /**
     * Get catalogName
     *
     * @return string
     */
    public function getCatalogName()
    {
        return $this->catalogName;
    }

    /**
     * Set nMedia
     *
     * @param integer $nMedia
     *
     * @return CmsUsersCatalogs
     */
    public function setNMedia($nMedia)
    {
        $this->nMedia = $nMedia;

        return $this;
    }

    /**
     * Get nMedia
     *
     * @return integer
     */
    public function getNMedia()
    {
        return $this->nMedia;
    }

    /**
     * Set placetakenat
     *
     * @param string $placetakenat
     *
     * @return CmsUsersCatalogs
     */
    public function setPlacetakenat($placetakenat)
    {
        $this->placetakenat = $placetakenat;

        return $this;
    }

    /**
     * Get placetakenat
     *
     * @return string
     */
    public function getPlacetakenat()
    {
        return $this->placetakenat;
    }

    /**
     * Set catalogTs
     *
     * @param \DateTime $catalogTs
     *
     * @return CmsUsersCatalogs
     */
    public function setCatalogTs($catalogTs)
    {
        $this->catalogTs = $catalogTs;

        return $this;
    }

    /**
     * Get catalogTs
     *
     * @return \DateTime
     */
    public function getCatalogTs()
    {
        return $this->catalogTs;
    }

    /**
     * Set upVotes
     *
     * @param integer $upVotes
     *
     * @return CmsUsersCatalogs
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
     * @return CmsUsersCatalogs
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
     * @return CmsUsersCatalogs
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
     * @return CmsUsersCatalogs
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
     * Set nbViews
     *
     * @param integer $nbViews
     *
     * @return CmsUsersCatalogs
     */
    public function setNbViews($nbViews)
    {
        $this->nbViews = $nbViews;

        return $this;
    }

    /**
     * Get nbViews
     *
     * @return integer
     */
    public function getNbViews()
    {
        return $this->nbViews;
    }

    /**
     * Set nbRatings
     *
     * @param integer $nbRatings
     *
     * @return CmsUsersCatalogs
     */
    public function setNbRatings($nbRatings)
    {
        $this->nbRatings = $nbRatings;

        return $this;
    }

    /**
     * Get nbRatings
     *
     * @return integer
     */
    public function getNbRatings()
    {
        return $this->nbRatings;
    }

    /**
     * Set rating
     *
     * @param float $rating
     *
     * @return CmsUsersCatalogs
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set nbShares
     *
     * @param integer $nbShares
     *
     * @return CmsUsersCatalogs
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
     * Set vpath
     *
     * @param string $vpath
     *
     * @return CmsUsersCatalogs
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
     * Set locationId
     *
     * @param integer $locationId
     *
     * @return CmsUsersCatalogs
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
     * Set cityid
     *
     * @param integer $cityid
     *
     * @return CmsUsersCatalogs
     */
    public function setCityid($cityid)
    {
        $this->cityid = $cityid;

        return $this;
    }

    /**
     * Get cityid
     *
     * @return integer
     */
    public function getCityid()
    {
        return $this->cityid;
    }

    /**
     * Set cityname
     *
     * @param string $cityname
     *
     * @return CmsUsersCatalogs
     */
    public function setCityname($cityname)
    {
        $this->cityname = $cityname;

        return $this;
    }

    /**
     * Get cityname
     *
     * @return string
     */
    public function getCityname()
    {
        return $this->cityname;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return CmsUsersCatalogs
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
     * Set description
     *
     * @param string $description
     *
     * @return CmsUsersCatalogs
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
     * Set latitude
     *
     * @param float $latitude
     *
     * @return CmsUsersCatalogs
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
     * @return CmsUsersCatalogs
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
     * Set isPublic
     *
     * @param integer $isPublic
     *
     * @return CmsUsersCatalogs
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
     * Set keywords
     *
     * @param string $keywords
     *
     * @return CmsUsersCatalogs
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
     * Set category
     *
     * @param integer $category
     *
     * @return CmsUsersCatalogs
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
     * Set channelid
     *
     * @param integer $channelid
     *
     * @return CmsUsersCatalogs
     */
    public function setChannelid($channelid)
    {
        $this->channelid = $channelid;

        return $this;
    }

    /**
     * Get channelid
     *
     * @return integer
     */
    public function getChannelid()
    {
        return $this->channelid;
    }

    /**
     * Set albumUrl
     *
     * @param string $albumUrl
     *
     * @return CmsUsersCatalogs
     */
    public function setAlbumUrl($albumUrl)
    {
        $this->albumUrl = $albumUrl;

        return $this;
    }

    /**
     * Get albumUrl
     *
     * @return string
     */
    public function getAlbumUrl()
    {
        return $this->albumUrl;
    }

    /**
     * Set canComment
     *
     * @param boolean $canComment
     *
     * @return CmsUsersCatalogs
     */
    public function setCanComment($canComment)
    {
        $this->canComment = $canComment;

        return $this;
    }

    /**
     * Get canComment
     *
     * @return boolean
     */
    public function getCanComment()
    {
        return $this->canComment;
    }

    /**
     * Set canShare
     *
     * @param boolean $canShare
     *
     * @return CmsUsersCatalogs
     */
    public function setCanShare($canShare)
    {
        $this->canShare = $canShare;

        return $this;
    }

    /**
     * Get canShare
     *
     * @return boolean
     */
    public function getCanShare()
    {
        return $this->canShare;
    }

    /**
     * Set canRate
     *
     * @param boolean $canRate
     *
     * @return CmsUsersCatalogs
     */
    public function setCanRate($canRate)
    {
        $this->canRate = $canRate;

        return $this;
    }

    /**
     * Get canRate
     *
     * @return boolean
     */
    public function getCanRate()
    {
        return $this->canRate;
    }

    /**
     * Set canLike
     *
     * @param boolean $canLike
     *
     * @return CmsUsersCatalogs
     */
    public function setCanLike($canLike)
    {
        $this->canLike = $canLike;

        return $this;
    }

    /**
     * Get canLike
     *
     * @return boolean
     */
    public function getCanLike()
    {
        return $this->canLike;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsUsersCatalogs
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
     * @return CmsUsersCatalogs
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
}
