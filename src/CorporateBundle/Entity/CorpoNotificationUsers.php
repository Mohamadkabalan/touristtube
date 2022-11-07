<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoNotificationUsers
 *
 * @ORM\Table(name="corpo_notification_users")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoNotificationUsersRepository")
 */
class CorpoNotificationUsers
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
     * @ORM\Column(name="account_id", type="integer", nullable=false)
     */
    private $accountId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="notification_id", type="integer", nullable=false)
     */
    private $notificationId;

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
     * Get accountId
     *
     * @return integer
     */
    function getAccountId()
    {
        return $this->accountId;
    }
    /**
     * Get userId
     *
     * @return integer
     */
    function getUserId()
    {
        return $this->userId;
    }
    /**
     * Get notificationId
     *
     * @return integer
     */
    function getNotificationId()
    {
        return $this->notificationId;
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
     * Set accountId
     *
     * @param integer $accountId
     *
     * @return accountId
     */
    function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this->accountId;
    }
    
    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return userId
     */
    function setUserId($userId)
    {
        $this->userId = $userId;

        return $this->userId;
    }
    
    /**
     * Set notificationId
     *
     * @param integer $notificationId
     *
     * @return notificationId
     */
    function setNotificationId($notificationId)
    {
        $this->notificationId = $notificationId;

        return $this->notificationId;
    }
    
}