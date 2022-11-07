<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsUsersDetail
 *
 * @ORM\Table(name="cms_users_detail")
 * @ORM\Entity
 */
class CmsUsersDetail
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
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="detail_text", type="text", length=65535, nullable=false)
     */
    private $detailText;

    /**
     * @var boolean
     *
     * @ORM\Column(name="detail_type", type="boolean", nullable=false)
     */
    private $detailType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_ts", type="datetime", nullable=false)
     */
    private $createTs = 'CURRENT_TIMESTAMP';

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
     * @var boolean
     *
     * @ORM\Column(name="enable_share_comment", type="boolean", nullable=false)
     */
    private $enableShareComment = '1';

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
     * @return CmsUsersDetail
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
     * Set detailText
     *
     * @param string $detailText
     *
     * @return CmsUsersDetail
     */
    public function setDetailText($detailText)
    {
        $this->detailText = $detailText;

        return $this;
    }

    /**
     * Get detailText
     *
     * @return string
     */
    public function getDetailText()
    {
        return $this->detailText;
    }

    /**
     * Set detailType
     *
     * @param boolean $detailType
     *
     * @return CmsUsersDetail
     */
    public function setDetailType($detailType)
    {
        $this->detailType = $detailType;

        return $this;
    }

    /**
     * Get detailType
     *
     * @return boolean
     */
    public function getDetailType()
    {
        return $this->detailType;
    }

    /**
     * Set createTs
     *
     * @param \DateTime $createTs
     *
     * @return CmsUsersDetail
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
     * Set likeValue
     *
     * @param integer $likeValue
     *
     * @return CmsUsersDetail
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
     * @return CmsUsersDetail
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
     * @return CmsUsersDetail
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
     * @return CmsUsersDetail
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
     * @return CmsUsersDetail
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
     * Set enableShareComment
     *
     * @param boolean $enableShareComment
     *
     * @return CmsUsersDetail
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
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsUsersDetail
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
