<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsVideos
 *
 * @ORM\Table(name="cms_videos", indexes={@ORM\Index(name="cityid", columns={"cityid"}), @ORM\Index(name="video_trip_id", columns={"trip_id"}), @ORM\Index(name="cms_videos_location_index", columns={"location_id"}), @ORM\Index(name="video_url", columns={"video_url"}), @ORM\Index(name="userid", columns={"userid"}), @ORM\Index(name="category", columns={"category"}), @ORM\Index(name="country", columns={"country"}), @ORM\Index(name="image_video", columns={"image_video"}), @ORM\Index(name="channelid", columns={"channelid"}), @ORM\Index(name="nb_views", columns={"nb_views"}), @ORM\Index(name="nb_ratings", columns={"nb_ratings"}), @ORM\Index(name="title", columns={"title"}), @ORM\Index(name="description", columns={"description"}), @ORM\Index(name="both", columns={"title", "description"})})
 * @ORM\Entity(repositoryClass="TTBundle\Repository\PhotosVideosRepository")
 */
class CmsVideos
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
     * @var \DateTime
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=false)
     */
    private $lastModified = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=200, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="fullpath", type="string", length=200, nullable=false)
     */
    private $fullpath;

    /**
     * @var string
     *
     * @ORM\Column(name="relativepath", type="string", length=200, nullable=false)
     */
    private $relativepath;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=200, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=254, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="category", type="integer", nullable=true)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="placetakenat", type="string", length=100, nullable=false)
     */
    private $placetakenat;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="text", length=65535, nullable=false)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=2, nullable=true)
     */
    private $country;

    /**
     * @var integer
     *
     * @ORM\Column(name="location", type="integer", nullable=false)
     */
    private $location;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pdate", type="datetime", nullable=false)
     */
    private $pdate;

    /**
     * @var string
     *
     * @ORM\Column(name="dimension", type="string", length=100, nullable=false)
     */
    private $dimension;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=100, nullable=false)
     */
    private $duration;

    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer", nullable=true)
     */
    private $userid;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_views", type="integer", nullable=false)
     */
    private $nbViews = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_comments", type="integer", nullable=false)
     */
    private $nbComments = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_ratings", type="integer", nullable=false)
     */
    private $nbRatings = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", precision=10, scale=0, nullable=false)
     */
    private $rating = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_shares", type="integer", nullable=false)
     */
    private $nbShares = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="like_value", type="integer", nullable=false)
     */
    private $likeValue = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="up_votes", type="integer", nullable=false)
     */
    private $upVotes = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="down_votes", type="integer", nullable=false)
     */
    private $downVotes = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="lattitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $lattitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="cityid", type="integer", nullable=true)
     */
    private $cityid;

    /**
     * @var string
     *
     * @ORM\Column(name="cityname", type="string", length=255, nullable=false)
     */
    private $cityname;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_public", type="integer", nullable=false)
     */
    private $isPublic = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="trip_id", type="integer", nullable=true)
     */
    private $tripId;

    /**
     * @var string
     *
     * @ORM\Column(name="image_video", type="string", length=1, nullable=true)
     */
    private $imageVideo;

    /**
     * @var integer
     *
     * @ORM\Column(name="location_id", type="integer", nullable=true)
     */
    private $locationId;

    /**
     * @var string
     *
     * @ORM\Column(name="video_url", type="string", length=255, nullable=false)
     */
    private $videoUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="media_servers", type="text", length=65535, nullable=false)
     */
    private $mediaServers;

    /**
     * @var integer
     *
     * @ORM\Column(name="thumb_top", type="integer", nullable=false)
     */
    private $thumbTop = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="thumb_left", type="integer", nullable=false)
     */
    private $thumbLeft = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="channelid", type="integer", nullable=false)
     */
    private $channelid = '0';

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
     * @var \DateTime
     *
     * @ORM\Column(name="link_ts", type="datetime", nullable=false)
     */
    private $linkTs;

    /**
     * @var string
     *
     * @ORM\Column(name="description_linked", type="text", length=65535, nullable=false)
     */
    private $descriptionLinked;

    /**
     * @var string
     *
     * @ORM\Column(name="hash_id", type="string", length=100, nullable=false)
     */
    private $hashId;

    /**
     * @var string
     *
     * @ORM\Column(name="old", type="string", length=200, nullable=false)
     */
    private $old;

    /**
     * @var string
     *
     * @ORM\Column(name="allowed_users", type="text", length=65535, nullable=false)
     */
    private $allowedUsers;

    /**
     * @var integer
     *
     * @ORM\Column(name="featured", type="integer", nullable=false)
     */
    private $featured = '0';



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
     * Set lastModified
     *
     * @param \DateTime $lastModified
     *
     * @return CmsVideos
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
     * Set code
     *
     * @param string $code
     *
     * @return CmsVideos
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return CmsVideos
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
     * Set fullpath
     *
     * @param string $fullpath
     *
     * @return CmsVideos
     */
    public function setFullpath($fullpath)
    {
        $this->fullpath = $fullpath;

        return $this;
    }

    /**
     * Get fullpath
     *
     * @return string
     */
    public function getFullpath()
    {
        return $this->fullpath;
    }

    /**
     * Set relativepath
     *
     * @param string $relativepath
     *
     * @return CmsVideos
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
     * Set type
     *
     * @param string $type
     *
     * @return CmsVideos
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return CmsVideos
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
     * @return CmsVideos
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
     * Set category
     *
     * @param integer $category
     *
     * @return CmsVideos
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
     * Set placetakenat
     *
     * @param string $placetakenat
     *
     * @return CmsVideos
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
     * Set keywords
     *
     * @param string $keywords
     *
     * @return CmsVideos
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
     * @return CmsVideos
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
     * Set location
     *
     * @param integer $location
     *
     * @return CmsVideos
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return integer
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set pdate
     *
     * @param \DateTime $pdate
     *
     * @return CmsVideos
     */
    public function setPdate($pdate)
    {
        $this->pdate = $pdate;

        return $this;
    }

    /**
     * Get pdate
     *
     * @return \DateTime
     */
    public function getPdate()
    {
        return $this->pdate;
    }

    /**
     * Set dimension
     *
     * @param string $dimension
     *
     * @return CmsVideos
     */
    public function setDimension($dimension)
    {
        $this->dimension = $dimension;

        return $this;
    }

    /**
     * Get dimension
     *
     * @return string
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * Set duration
     *
     * @param string $duration
     *
     * @return CmsVideos
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     *
     * @return CmsVideos
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return integer
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsVideos
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
     * Set nbViews
     *
     * @param integer $nbViews
     *
     * @return CmsVideos
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
     * Set nbComments
     *
     * @param integer $nbComments
     *
     * @return CmsVideos
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
     * Set nbRatings
     *
     * @param integer $nbRatings
     *
     * @return CmsVideos
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
     * @return CmsVideos
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
     * @return CmsVideos
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
     * Set likeValue
     *
     * @param integer $likeValue
     *
     * @return CmsVideos
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
     * @return CmsVideos
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
     * @return CmsVideos
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
     * Set lattitude
     *
     * @param float $lattitude
     *
     * @return CmsVideos
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
     * Set longitude
     *
     * @param float $longitude
     *
     * @return CmsVideos
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
     * Set cityid
     *
     * @param integer $cityid
     *
     * @return CmsVideos
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
     * @return CmsVideos
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
     * Set isPublic
     *
     * @param integer $isPublic
     *
     * @return CmsVideos
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
     * Set tripId
     *
     * @param integer $tripId
     *
     * @return CmsVideos
     */
    public function setTripId($tripId)
    {
        $this->tripId = $tripId;

        return $this;
    }

    /**
     * Get tripId
     *
     * @return integer
     */
    public function getTripId()
    {
        return $this->tripId;
    }

    /**
     * Set imageVideo
     *
     * @param string $imageVideo
     *
     * @return CmsVideos
     */
    public function setImageVideo($imageVideo)
    {
        $this->imageVideo = $imageVideo;

        return $this;
    }

    /**
     * Get imageVideo
     *
     * @return string
     */
    public function getImageVideo()
    {
        return $this->imageVideo;
    }

    /**
     * Set locationId
     *
     * @param integer $locationId
     *
     * @return CmsVideos
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
     * Set videoUrl
     *
     * @param string $videoUrl
     *
     * @return CmsVideos
     */
    public function setVideoUrl($videoUrl)
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }

    /**
     * Get videoUrl
     *
     * @return string
     */
    public function getVideoUrl()
    {
        return $this->videoUrl;
    }

    /**
     * Set mediaServers
     *
     * @param string $mediaServers
     *
     * @return CmsVideos
     */
    public function setMediaServers($mediaServers)
    {
        $this->mediaServers = $mediaServers;

        return $this;
    }

    /**
     * Get mediaServers
     *
     * @return string
     */
    public function getMediaServers()
    {
        return $this->mediaServers;
    }

    /**
     * Set thumbTop
     *
     * @param integer $thumbTop
     *
     * @return CmsVideos
     */
    public function setThumbTop($thumbTop)
    {
        $this->thumbTop = $thumbTop;

        return $this;
    }

    /**
     * Get thumbTop
     *
     * @return integer
     */
    public function getThumbTop()
    {
        return $this->thumbTop;
    }

    /**
     * Set thumbLeft
     *
     * @param integer $thumbLeft
     *
     * @return CmsVideos
     */
    public function setThumbLeft($thumbLeft)
    {
        $this->thumbLeft = $thumbLeft;

        return $this;
    }

    /**
     * Get thumbLeft
     *
     * @return integer
     */
    public function getThumbLeft()
    {
        return $this->thumbLeft;
    }

    /**
     * Set channelid
     *
     * @param integer $channelid
     *
     * @return CmsVideos
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
     * Set canComment
     *
     * @param boolean $canComment
     *
     * @return CmsVideos
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
     * @return CmsVideos
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
     * @return CmsVideos
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
     * @return CmsVideos
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
     * Set linkTs
     *
     * @param \DateTime $linkTs
     *
     * @return CmsVideos
     */
    public function setLinkTs($linkTs)
    {
        $this->linkTs = $linkTs;

        return $this;
    }

    /**
     * Get linkTs
     *
     * @return \DateTime
     */
    public function getLinkTs()
    {
        return $this->linkTs;
    }

    /**
     * Set descriptionLinked
     *
     * @param string $descriptionLinked
     *
     * @return CmsVideos
     */
    public function setDescriptionLinked($descriptionLinked)
    {
        $this->descriptionLinked = $descriptionLinked;

        return $this;
    }

    /**
     * Get descriptionLinked
     *
     * @return string
     */
    public function getDescriptionLinked()
    {
        return $this->descriptionLinked;
    }

    /**
     * Set hashId
     *
     * @param string $hashId
     *
     * @return CmsVideos
     */
    public function setHashId($hashId)
    {
        $this->hashId = $hashId;

        return $this;
    }

    /**
     * Get hashId
     *
     * @return string
     */
    public function getHashId()
    {
        return $this->hashId;
    }

    /**
     * Set old
     *
     * @param string $old
     *
     * @return CmsVideos
     */
    public function setOld($old)
    {
        $this->old = $old;

        return $this;
    }

    /**
     * Get old
     *
     * @return string
     */
    public function getOld()
    {
        return $this->old;
    }

    /**
     * Set allowedUsers
     *
     * @param string $allowedUsers
     *
     * @return CmsVideos
     */
    public function setAllowedUsers($allowedUsers)
    {
        $this->allowedUsers = $allowedUsers;

        return $this;
    }

    /**
     * Get allowedUsers
     *
     * @return string
     */
    public function getAllowedUsers()
    {
        return $this->allowedUsers;
    }

    /**
     * Set featured
     *
     * @param integer $featured
     *
     * @return CmsVideos
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * Get featured
     *
     * @return integer
     */
    public function getFeatured()
    {
        return $this->featured;
    }
}
