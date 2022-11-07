<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsPushNotification
 *
 * @ORM\Table(name="cms_push_notification")
 * @ORM\Entity
 */
class CmsPushNotification
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
     * @ORM\Column(name="msg", type="string", length=255, nullable=true)
     */
    private $msg;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="action_user_id", type="integer", nullable=false)
     */
    private $actionUserId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="action_type", type="boolean", nullable=true)
     */
    private $actionType;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=true)
     */
    private $entityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_type", type="integer", nullable=false)
     */
    private $entityType;



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
     * Set msg
     *
     * @param string $msg
     *
     * @return CmsPushNotification
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsPushNotification
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
     * Set actionUserId
     *
     * @param integer $actionUserId
     *
     * @return CmsPushNotification
     */
    public function setActionUserId($actionUserId)
    {
        $this->actionUserId = $actionUserId;

        return $this;
    }

    /**
     * Get actionUserId
     *
     * @return integer
     */
    public function getActionUserId()
    {
        return $this->actionUserId;
    }

    /**
     * Set actionType
     *
     * @param boolean $actionType
     *
     * @return CmsPushNotification
     */
    public function setActionType($actionType)
    {
        $this->actionType = $actionType;

        return $this;
    }

    /**
     * Get actionType
     *
     * @return boolean
     */
    public function getActionType()
    {
        return $this->actionType;
    }

    /**
     * Set entityId
     *
     * @param integer $entityId
     *
     * @return CmsPushNotification
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return integer
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set entityType
     *
     * @param integer $entityType
     *
     * @return CmsPushNotification
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * Get entityType
     *
     * @return boolean
     */
    public function getEntityType()
    {
        return $this->entityType;
    }
}
