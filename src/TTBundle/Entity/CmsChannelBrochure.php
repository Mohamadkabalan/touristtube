<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsChannelBrochure
 *
 * @ORM\Table(name="cms_channel_brochure")
 * @ORM\Entity
 */
class CmsChannelBrochure
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="channelid", type="integer", nullable=false)
     */
    private $channelid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=256, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=256, nullable=false)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="pdf", type="string", length=256, nullable=false)
     */
    private $pdf;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_ts", type="datetime", nullable=false)
     */
    private $createTs = 'CURRENT_TIMESTAMP';

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
     * @ORM\Column(name="is_visible", type="integer", nullable=false)
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
     * @ORM\Column(name="nb_comments", type="integer", nullable=false)
     */
    private $nbComments = '0';



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
     * Set channelid
     *
     * @param integer $channelid
     *
     * @return CmsChannelBrochure
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
     * Set name
     *
     * @param string $name
     *
     * @return CmsChannelBrochure
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
     * @return CmsChannelBrochure
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
     * Set pdf
     *
     * @param string $pdf
     *
     * @return CmsChannelBrochure
     */
    public function setPdf($pdf)
    {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * Get pdf
     *
     * @return string
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * Set createTs
     *
     * @param \DateTime $createTs
     *
     * @return CmsChannelBrochure
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
     * @param integer $published
     *
     * @return CmsChannelBrochure
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
     * @return CmsChannelBrochure
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
     * @return CmsChannelBrochure
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
     * @return CmsChannelBrochure
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
     * @return CmsChannelBrochure
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
     * Set isVisible
     *
     * @param integer $isVisible
     *
     * @return CmsChannelBrochure
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * Get isVisible
     *
     * @return integer
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
     * @return CmsChannelBrochure
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
     * Set nbComments
     *
     * @param integer $nbComments
     *
     * @return CmsChannelBrochure
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
}
