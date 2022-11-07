<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsChatLog
 *
 * @ORM\Table(name="cms_chat_log", indexes={@ORM\Index(name="from", columns={"from_user"}), @ORM\Index(name="to", columns={"to_user"}), @ORM\Index(name="msg_ts", columns={"msg_ts"})})
 * @ORM\Entity
 */
class CmsChatLog
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
     * @ORM\Column(name="from_user", type="integer", nullable=false)
     */
    private $fromUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="to_user", type="integer", nullable=false)
     */
    private $toUser;

    /**
     * @var string
     *
     * @ORM\Column(name="msg_txt", type="text", length=65535, nullable=false)
     */
    private $msgTxt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="msg_ts", type="datetime", nullable=false)
     */
    private $msgTs = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delivered_ts", type="datetime", nullable=true)
     */
    private $deliveredTs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="client_ts", type="datetime", nullable=true)
     */
    private $clientTs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="read_ts", type="datetime", nullable=true)
     */
    private $readTs;

    /**
     * @var string
     *
     * @ORM\Column(name="client_uid", type="string", length=64, nullable=false)
     */
    private $clientUid;

    /**
     * @var integer
     *
     * @ORM\Column(name="from_tz", type="integer", nullable=false)
     */
    private $fromTz = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="viewed", type="boolean", nullable=false)
     */
    private $viewed;



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
     * Set fromUser
     *
     * @param integer $fromUser
     *
     * @return CmsChatLog
     */
    public function setFromUser($fromUser)
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    /**
     * Get fromUser
     *
     * @return integer
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Set toUser
     *
     * @param integer $toUser
     *
     * @return CmsChatLog
     */
    public function setToUser($toUser)
    {
        $this->toUser = $toUser;

        return $this;
    }

    /**
     * Get toUser
     *
     * @return integer
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    /**
     * Set msgTxt
     *
     * @param string $msgTxt
     *
     * @return CmsChatLog
     */
    public function setMsgTxt($msgTxt)
    {
        $this->msgTxt = $msgTxt;

        return $this;
    }

    /**
     * Get msgTxt
     *
     * @return string
     */
    public function getMsgTxt()
    {
        return $this->msgTxt;
    }

    /**
     * Set msgTs
     *
     * @param \DateTime $msgTs
     *
     * @return CmsChatLog
     */
    public function setMsgTs($msgTs)
    {
        $this->msgTs = $msgTs;

        return $this;
    }

    /**
     * Get msgTs
     *
     * @return \DateTime
     */
    public function getMsgTs()
    {
        return $this->msgTs;
    }

    /**
     * Set deliveredTs
     *
     * @param \DateTime $deliveredTs
     *
     * @return CmsChatLog
     */
    public function setDeliveredTs($deliveredTs)
    {
        $this->deliveredTs = $deliveredTs;

        return $this;
    }

    /**
     * Get deliveredTs
     *
     * @return \DateTime
     */
    public function getDeliveredTs()
    {
        return $this->deliveredTs;
    }

    /**
     * Set clientTs
     *
     * @param \DateTime $clientTs
     *
     * @return CmsChatLog
     */
    public function setClientTs($clientTs)
    {
        $this->clientTs = $clientTs;

        return $this;
    }

    /**
     * Get clientTs
     *
     * @return \DateTime
     */
    public function getClientTs()
    {
        return $this->clientTs;
    }

    /**
     * Set readTs
     *
     * @param \DateTime $readTs
     *
     * @return CmsChatLog
     */
    public function setReadTs($readTs)
    {
        $this->readTs = $readTs;

        return $this;
    }

    /**
     * Get readTs
     *
     * @return \DateTime
     */
    public function getReadTs()
    {
        return $this->readTs;
    }

    /**
     * Set clientUid
     *
     * @param string $clientUid
     *
     * @return CmsChatLog
     */
    public function setClientUid($clientUid)
    {
        $this->clientUid = $clientUid;

        return $this;
    }

    /**
     * Get clientUid
     *
     * @return string
     */
    public function getClientUid()
    {
        return $this->clientUid;
    }

    /**
     * Set fromTz
     *
     * @param integer $fromTz
     *
     * @return CmsChatLog
     */
    public function setFromTz($fromTz)
    {
        $this->fromTz = $fromTz;

        return $this;
    }

    /**
     * Get fromTz
     *
     * @return integer
     */
    public function getFromTz()
    {
        return $this->fromTz;
    }

    /**
     * Set viewed
     *
     * @param boolean $viewed
     *
     * @return CmsChatLog
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;

        return $this;
    }

    /**
     * Get viewed
     *
     * @return boolean
     */
    public function getViewed()
    {
        return $this->viewed;
    }
}
