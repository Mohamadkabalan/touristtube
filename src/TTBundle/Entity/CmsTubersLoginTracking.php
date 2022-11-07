<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsTubersLoginTracking
 *
 * @ORM\Table(name="cms_tubers_login_tracking", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class CmsTubersLoginTracking
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
     * @ORM\Column(name="ip_address", type="string", length=100, nullable=true)
     */
    private $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="forwarded_ip_address", type="string", length=100, nullable=true)
     */
    private $forwardedIpAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="string", length=255, nullable=true)
     */
    private $userAgent;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="log_ts", type="datetime", nullable=false)
     */
    private $logTs = 'CURRENT_TIMESTAMP';



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
     * Set ipAddress
     *
     * @param string $ipAddress
     *
     * @return CmsTubersLoginTracking
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set forwardedIpAddress
     *
     * @param string $forwardedIpAddress
     *
     * @return CmsTubersLoginTracking
     */
    public function setForwardedIpAddress($forwardedIpAddress)
    {
        $this->forwardedIpAddress = $forwardedIpAddress;

        return $this;
    }

    /**
     * Get forwardedIpAddress
     *
     * @return string
     */
    public function getForwardedIpAddress()
    {
        return $this->forwardedIpAddress;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     *
     * @return CmsTubersLoginTracking
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsTubersLoginTracking
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
     * Set logTs
     *
     * @param \DateTime $logTs
     *
     * @return CmsTubersLoginTracking
     */
    public function setLogTs($logTs)
    {
        $this->logTs = $logTs;

        return $this;
    }

    /**
     * Get logTs
     *
     * @return \DateTime
     */
    public function getLogTs()
    {
        return $this->logTs;
    }
}
