<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsChatStatus
 *
 * @ORM\Table(name="cms_chat_status", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class CmsChatStatus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_action", type="datetime", nullable=false)
     */
    private $lastAction = 'CURRENT_TIMESTAMP';



    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsChatStatus
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
     * Set status
     *
     * @param integer $status
     *
     * @return CmsChatStatus
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set lastAction
     *
     * @param \DateTime $lastAction
     *
     * @return CmsChatStatus
     */
    public function setLastAction($lastAction)
    {
        $this->lastAction = $lastAction;

        return $this;
    }

    /**
     * Get lastAction
     *
     * @return \DateTime
     */
    public function getLastAction()
    {
        return $this->lastAction;
    }
}
