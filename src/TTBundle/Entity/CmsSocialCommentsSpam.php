<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialCommentsSpam
 *
 * @ORM\Table(name="cms_social_comments_spam", indexes={@ORM\Index(name="commenter_id", columns={"commenter_id"}), @ORM\Index(name="reporter_id", columns={"reporter_id"}), @ORM\Index(name="comment_id", columns={"comment_id"})})
 * @ORM\Entity
 */
class CmsSocialCommentsSpam
{
    /**
     * @var integer
     *
     * @ORM\Column(name="commenter_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $commenterId;

    /**
     * @var integer
     *
     * @ORM\Column(name="comment_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $commentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="reporter_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $reporterId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="report_ts", type="datetime")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $reportTs = 'CURRENT_TIMESTAMP';



    /**
     * Set commenterId
     *
     * @param integer $commenterId
     *
     * @return CmsSocialCommentsSpam
     */
    public function setCommenterId($commenterId)
    {
        $this->commenterId = $commenterId;

        return $this;
    }

    /**
     * Get commenterId
     *
     * @return integer
     */
    public function getCommenterId()
    {
        return $this->commenterId;
    }

    /**
     * Set commentId
     *
     * @param integer $commentId
     *
     * @return CmsSocialCommentsSpam
     */
    public function setCommentId($commentId)
    {
        $this->commentId = $commentId;

        return $this;
    }

    /**
     * Get commentId
     *
     * @return integer
     */
    public function getCommentId()
    {
        return $this->commentId;
    }

    /**
     * Set reporterId
     *
     * @param integer $reporterId
     *
     * @return CmsSocialCommentsSpam
     */
    public function setReporterId($reporterId)
    {
        $this->reporterId = $reporterId;

        return $this;
    }

    /**
     * Get reporterId
     *
     * @return integer
     */
    public function getReporterId()
    {
        return $this->reporterId;
    }

    /**
     * Set reportTs
     *
     * @param \DateTime $reportTs
     *
     * @return CmsSocialCommentsSpam
     */
    public function setReportTs($reportTs)
    {
        $this->reportTs = $reportTs;

        return $this;
    }

    /**
     * Get reportTs
     *
     * @return \DateTime
     */
    public function getReportTs()
    {
        return $this->reportTs;
    }
}
