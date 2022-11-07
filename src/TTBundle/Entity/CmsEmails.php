<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsEmails
 *
 * @ORM\Table(name="cms_emails")
 * @ORM\Entity
 */
class CmsEmails
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
     * @ORM\Column(name="to_email", type="string", length=255, nullable=false)
     */
    private $toEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="msg", type="text", nullable=false)
     */
    private $msg;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=false)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var boolean
     *
     * @ORM\Column(name="priority", type="boolean", nullable=false)
     */
    private $priority = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_ts", type="datetime", nullable=false)
     */
    private $createTs = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="sent", type="boolean", nullable=false)
     */
    private $sent = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="num_try", type="integer", nullable=false)
     */
    private $numTry = '0';



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
     * Set toEmail
     *
     * @param string $toEmail
     *
     * @return CmsEmails
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
     * Set msg
     *
     * @param string $msg
     *
     * @return CmsEmails
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;

        return $this;
    }

    /**
     * Get msg
     *
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return CmsEmails
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return CmsEmails
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
     * Set priority
     *
     * @param boolean $priority
     *
     * @return CmsEmails
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return boolean
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set createTs
     *
     * @param \DateTime $createTs
     *
     * @return CmsEmails
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
     * Set sent
     *
     * @param boolean $sent
     *
     * @return CmsEmails
     */
    public function setSent($sent)
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * Get sent
     *
     * @return boolean
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * Set numTry
     *
     * @param integer $numTry
     *
     * @return CmsEmails
     */
    public function setNumTry($numTry)
    {
        $this->numTry = $numTry;

        return $this;
    }

    /**
     * Get numTry
     *
     * @return integer
     */
    public function getNumTry()
    {
        return $this->numTry;
    }
}
