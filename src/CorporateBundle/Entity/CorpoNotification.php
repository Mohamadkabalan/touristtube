<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoNotification
 *
 * @ORM\Table(name="corpo_notification")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoNotificationRepository")
 */
class CorpoNotification
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
     * @ORM\Column(name="type_id", type="integer", nullable=false)
     */
    private $typeId;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=100, nullable=false)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="mssg", type="text", length=65535, nullable=true)
     */
    private $mssg;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="notification_is_sent", type="boolean", nullable=false)
     */
    private $notificationIsSent = '0';
    
    /**
     * @var DateTime
     *
     * @ORM\Column(name="notification_date", type="datetime", nullable=false)
     */
    private $notificationDate;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", nullable=false)
     */
    private $createdBy;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * Get id
     *
     * @return integer
     */
    function getId()
    {
        return $this->id;
    }

    /**
     * Get typeId
     *
     * @return integer
     */
    function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Get subject
     *
     * @return string
     */
    function getSubject()
    {
        return $this->subject;
    }

    /**
     * Get mssg
     *
     * @return string
     */
    function getMssg()
    {
        return $this->mssg;
    }

    /**
     * Get notificationIsSent
     *
     * @return boolean
     */
    function getNotificationIsSent()
    {
        return $this->notificationIsSent;
    }
    
    /**
     * Get notificationDate
     *
     * @return DateTime
     */
    function getNotificationDate()
    {
        return $this->notificationDate;
    }

    /**
     * Get createdBy
     *
     * @return integer
     */
    function getCreatedBy()
    {
        return $this->createdBy;
    }
    
    /**
     * Get createdAt
     *
     * @return DateTime
     */
    function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return id
     */
    function setId($id)
    {
        $this->id = $id;

        return $this->id;
    }

    /**
     * Set typeId
     *
     * @param integer $typeId
     *
     * @return typeId
     */
    function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this->typeId;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return subject
     */
    function setSubject($subject)
    {
        $this->subject = $subject;

        return $this->subject;
    }

    /**
     * Set mssg
     *
     * @param string $mssg
     *
     * @return mssg
     */
    function setMssg($mssg)
    {
        $this->mssg = $mssg;

        return $this->mssg;
    }
    
    /**
     * Set notificationIsSent
     *
     * @param boolean $notificationIsSent
     *
     * @return notificationIsSent
     */
    public function setNotificationIsSent($notificationIsSent)
    {
        $this->notificationIsSent = $notificationIsSent;

        return $this->notificationIsSent;
    }

    /**
     * Set notificationDate
     *
     * @param DateTime $notificationDate
     *
     * @return notificationDate
     */
    function setNotificationDate($notificationDate)
    {
        $this->notificationDate = $notificationDate;

        return $this->notificationDate;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return createdBy
     */
    function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this->createdBy;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return createdAt
     */
    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this->createdAt;
    }
    
}
