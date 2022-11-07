<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsChatFile
 *
 * @ORM\Table(name="cms_chat_file")
 * @ORM\Entity
 */
class CmsChatFile
{
    /**
     * @var integer
     *
     * @ORM\Column(name="file_id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $fileId;

    /**
     * @var integer
     *
     * @ORM\Column(name="senderId", type="integer", nullable=false)
     */
    private $senderid;

    /**
     * @var integer
     *
     * @ORM\Column(name="receiverid", type="integer", nullable=false)
     */
    private $receiverid;

    /**
     * @var string
     *
     * @ORM\Column(name="originalName", type="string", length=255, nullable=false)
     */
    private $originalname;

    /**
     * @var string
     *
     * @ORM\Column(name="newName", type="string", length=255, nullable=false)
     */
    private $newname;

    /**
     * @var string
     *
     * @ORM\Column(name="downloadLink", type="string", length=255, nullable=false)
     */
    private $downloadlink;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateTime", type="datetime", nullable=true)
     */
    private $datetime = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;



    /**
     * Get fileId
     *
     * @return integer
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * Set senderid
     *
     * @param integer $senderid
     *
     * @return CmsChatFile
     */
    public function setSenderid($senderid)
    {
        $this->senderid = $senderid;

        return $this;
    }

    /**
     * Get senderid
     *
     * @return integer
     */
    public function getSenderid()
    {
        return $this->senderid;
    }

    /**
     * Set receiverid
     *
     * @param integer $receiverid
     *
     * @return CmsChatFile
     */
    public function setReceiverid($receiverid)
    {
        $this->receiverid = $receiverid;

        return $this;
    }

    /**
     * Get receiverid
     *
     * @return integer
     */
    public function getReceiverid()
    {
        return $this->receiverid;
    }

    /**
     * Set originalname
     *
     * @param string $originalname
     *
     * @return CmsChatFile
     */
    public function setOriginalname($originalname)
    {
        $this->originalname = $originalname;

        return $this;
    }

    /**
     * Get originalname
     *
     * @return string
     */
    public function getOriginalname()
    {
        return $this->originalname;
    }

    /**
     * Set newname
     *
     * @param string $newname
     *
     * @return CmsChatFile
     */
    public function setNewname($newname)
    {
        $this->newname = $newname;

        return $this;
    }

    /**
     * Get newname
     *
     * @return string
     */
    public function getNewname()
    {
        return $this->newname;
    }

    /**
     * Set downloadlink
     *
     * @param string $downloadlink
     *
     * @return CmsChatFile
     */
    public function setDownloadlink($downloadlink)
    {
        $this->downloadlink = $downloadlink;

        return $this;
    }

    /**
     * Get downloadlink
     *
     * @return string
     */
    public function getDownloadlink()
    {
        return $this->downloadlink;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return CmsChatFile
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return CmsChatFile
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
}
