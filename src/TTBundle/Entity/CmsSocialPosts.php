<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialPosts
 *
 * @ORM\Table(name="cms_social_posts")
 * @ORM\Entity
 */
class CmsSocialPosts
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
     * @ORM\Column(name="from_id", type="integer", nullable=false)
     */
    private $fromId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="channel_id", type="integer", nullable=true)
     */
    private $channelId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="post_text", type="text", length=65535, nullable=false)
     */
    private $postText;

    /**
     * @var boolean
     *
     * @ORM\Column(name="post_type", type="boolean", nullable=false)
     */
    private $postType;

    /**
     * @var string
     *
     * @ORM\Column(name="post_link", type="string", length=255, nullable=false)
     */
    private $postLink;

    /**
     * @var string
     *
     * @ORM\Column(name="post_location", type="string", length=255, nullable=false)
     */
    private $postLocation;

    /**
     * @var string
     *
     * @ORM\Column(name="media_file", type="string", length=255, nullable=false)
     */
    private $mediaFile;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_video", type="boolean", nullable=false)
     */
    private $isVideo = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_ts", type="datetime", nullable=false)
     */
    private $createTs = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="relativepath", type="string", length=200, nullable=false)
     */
    private $relativepath;

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
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';

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
     * @var integer
     *
     * @ORM\Column(name="like_value", type="integer", nullable=false)
     */
    private $likeValue = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_ratings", type="integer", nullable=false)
     */
    private $nbRatings;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer", nullable=false)
     */
    private $rating;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_views", type="integer", nullable=false)
     */
    private $nbViews;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enable_share_comment", type="boolean", nullable=false)
     */
    private $enableShareComment = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="can_rate", type="boolean", nullable=false)
     */
    private $canRate = '1';



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
     * Set fromId
     *
     * @param integer $fromId
     *
     * @return CmsSocialPosts
     */
    public function setFromId($fromId)
    {
        $this->fromId = $fromId;

        return $this;
    }

    /**
     * Get fromId
     *
     * @return integer
     */
    public function getFromId()
    {
        return $this->fromId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsSocialPosts
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
     * Set channelId
     *
     * @param integer $channelId
     *
     * @return CmsSocialPosts
     */
    public function setChannelId($channelId)
    {
        $this->channelId = $channelId;

        return $this;
    }

    /**
     * Get channelId
     *
     * @return integer
     */
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * Set postText
     *
     * @param string $postText
     *
     * @return CmsSocialPosts
     */
    public function setPostText($postText)
    {
        $this->postText = $postText;

        return $this;
    }

    /**
     * Get postText
     *
     * @return string
     */
    public function getPostText()
    {
        return $this->postText;
    }

    /**
     * Set postType
     *
     * @param boolean $postType
     *
     * @return CmsSocialPosts
     */
    public function setPostType($postType)
    {
        $this->postType = $postType;

        return $this;
    }

    /**
     * Get postType
     *
     * @return boolean
     */
    public function getPostType()
    {
        return $this->postType;
    }

    /**
     * Set postLink
     *
     * @param string $postLink
     *
     * @return CmsSocialPosts
     */
    public function setPostLink($postLink)
    {
        $this->postLink = $postLink;

        return $this;
    }

    /**
     * Get postLink
     *
     * @return string
     */
    public function getPostLink()
    {
        return $this->postLink;
    }

    /**
     * Set postLocation
     *
     * @param string $postLocation
     *
     * @return CmsSocialPosts
     */
    public function setPostLocation($postLocation)
    {
        $this->postLocation = $postLocation;

        return $this;
    }

    /**
     * Get postLocation
     *
     * @return string
     */
    public function getPostLocation()
    {
        return $this->postLocation;
    }

    /**
     * Set mediaFile
     *
     * @param string $mediaFile
     *
     * @return CmsSocialPosts
     */
    public function setMediaFile($mediaFile)
    {
        $this->mediaFile = $mediaFile;

        return $this;
    }

    /**
     * Get mediaFile
     *
     * @return string
     */
    public function getMediaFile()
    {
        return $this->mediaFile;
    }

    /**
     * Set isVideo
     *
     * @param boolean $isVideo
     *
     * @return CmsSocialPosts
     */
    public function setIsVideo($isVideo)
    {
        $this->isVideo = $isVideo;

        return $this;
    }

    /**
     * Get isVideo
     *
     * @return boolean
     */
    public function getIsVideo()
    {
        return $this->isVideo;
    }

    /**
     * Set createTs
     *
     * @param \DateTime $createTs
     *
     * @return CmsSocialPosts
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
     * Set relativepath
     *
     * @param string $relativepath
     *
     * @return CmsSocialPosts
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
     * Set longitude
     *
     * @param float $longitude
     *
     * @return CmsSocialPosts
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
     * @return CmsSocialPosts
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
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsSocialPosts
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
     * Set nbComments
     *
     * @param integer $nbComments
     *
     * @return CmsSocialPosts
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
     * @return CmsSocialPosts
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
     * Set upVotes
     *
     * @param integer $upVotes
     *
     * @return CmsSocialPosts
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
     * @return CmsSocialPosts
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
     * @return CmsSocialPosts
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
     * Set nbRatings
     *
     * @param integer $nbRatings
     *
     * @return CmsSocialPosts
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
     * @param integer $rating
     *
     * @return CmsSocialPosts
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set nbViews
     *
     * @param integer $nbViews
     *
     * @return CmsSocialPosts
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
     * Set enableShareComment
     *
     * @param boolean $enableShareComment
     *
     * @return CmsSocialPosts
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
     * Set canRate
     *
     * @param boolean $canRate
     *
     * @return CmsSocialPosts
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
}
