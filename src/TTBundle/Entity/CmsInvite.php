<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsInvite
 *
 * @ORM\Table(name="cms_invite", indexes={@ORM\Index(name="from_id", columns={"from_id"})})
 * @ORM\Entity
 */
class CmsInvite
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
    private $fromId;

    /**
     * @var string
     *
     * @ORM\Column(name="to_name", type="string", length=128, nullable=false)
     */
    private $toName;

    /**
     * @var string
     *
     * @ORM\Column(name="to_email", type="string", length=128, nullable=false)
     */
    private $toEmail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="invite_ts", type="datetime", nullable=false)
     */
    private $inviteTs = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     */
    private $path;



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
     * @return CmsInvite
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
     * Set toName
     *
     * @param string $toName
     *
     * @return CmsInvite
     */
    public function setToName($toName)
    {
        $this->toName = $toName;

        return $this;
    }

    /**
     * Get toName
     *
     * @return string
     */
    public function getToName()
    {
        return $this->toName;
    }

    /**
     * Set toEmail
     *
     * @param string $toEmail
     *
     * @return CmsInvite
     */
    public function setToEmail($toEmail)
    {
        $this->toEmail = $toEmail;

        return $this;
    }

    /**
     * Get toEmail
     *
     * @return string
     */
    public function getToEmail()
    {
        return $this->toEmail;
    }

    /**
     * Set inviteTs
     *
     * @param \DateTime $inviteTs
     *
     * @return CmsInvite
     */
    public function setInviteTs($inviteTs)
    {
        $this->inviteTs = $inviteTs;

        return $this;
    }

    /**
     * Get inviteTs
     *
     * @return \DateTime
     */
    public function getInviteTs()
    {
        return $this->inviteTs;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return CmsInvite
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
