<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsWebcams
 *
 * @ORM\Table(name="cms_webcams", indexes={@ORM\Index(name="url", columns={"url", "location_id", "city_id"}), @ORM\Index(name="location_id", columns={"location_id"}), @ORM\Index(name="city_id", columns={"city_id"})})
 * @ORM\Entity
 */
class CmsWebcams
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
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=128, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="location_id", type="integer", nullable=true)
     */
    private $locationId;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="live_url", type="string", length=255, nullable=false)
     */
    private $liveUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="still_url", type="string", length=255, nullable=false)
     */
    private $stillUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="smallint", nullable=false)
     */
    private $state = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=true)
     */
    private $cityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_views", type="integer", nullable=false)
     */
    private $nbViews = '0';

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
     * @var float
     *
     * @ORM\Column(name="rating", type="float", precision=10, scale=0, nullable=false)
     */
    private $rating = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_ratings", type="integer", nullable=false)
     */
    private $nbRatings = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_shares", type="integer", nullable=false)
     */
    private $nbShares = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_comments", type="integer", nullable=false)
     */
    private $nbComments;

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
     * @return CmsWebcams
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
     * Set url
     *
     * @param string $url
     *
     * @return CmsWebcams
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CmsWebcams
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
     * Set locationId
     *
     * @param integer $locationId
     *
     * @return CmsWebcams
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
     * Set latitude
     *
     * @param float $latitude
     *
     * @return CmsWebcams
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
     * @return CmsWebcams
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
     * Set liveUrl
     *
     * @param string $liveUrl
     *
     * @return CmsWebcams
     */
    public function setLiveUrl($liveUrl)
    {
        $this->liveUrl = $liveUrl;

        return $this;
    }

    /**
     * Get liveUrl
     *
     * @return string
     */
    public function getLiveUrl()
    {
        return $this->liveUrl;
    }

    /**
     * Set stillUrl
     *
     * @param string $stillUrl
     *
     * @return CmsWebcams
     */
    public function setStillUrl($stillUrl)
    {
        $this->stillUrl = $stillUrl;

        return $this;
    }

    /**
     * Get stillUrl
     *
     * @return string
     */
    public function getStillUrl()
    {
        return $this->stillUrl;
    }

    /**
     * Set state
     *
     * @param integer $state
     *
     * @return CmsWebcams
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return CmsWebcams
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
     * Set nbViews
     *
     * @param integer $nbViews
     *
     * @return CmsWebcams
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
     * Set upVotes
     *
     * @param integer $upVotes
     *
     * @return CmsWebcams
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
     * @return CmsWebcams
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
     * @return CmsWebcams
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
     * Set rating
     *
     * @param float $rating
     *
     * @return CmsWebcams
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
     * Set nbRatings
     *
     * @param integer $nbRatings
     *
     * @return CmsWebcams
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
     * Set nbShares
     *
     * @param integer $nbShares
     *
     * @return CmsWebcams
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
     * @return CmsWebcams
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
     * Set canComment
     *
     * @param boolean $canComment
     *
     * @return CmsWebcams
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
     * @return CmsWebcams
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
     * @return CmsWebcams
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
     * @return CmsWebcams
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
}
