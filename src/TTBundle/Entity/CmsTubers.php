<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsTubers
 *
 * @ORM\Table(name="cms_tubers", uniqueConstraints={@ORM\UniqueConstraint(name="uid", columns={"uid"})}, indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="TTBundle\Repository\UserLoginRepository")
 * @ORM\Entity
 */
class CmsTubers
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
     * @ORM\Column(name="uid", type="string", length=255, nullable=false)
     */
    private $uid;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="log_ts", type="datetime", nullable=false)
     */
    private $logTs = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="client_type", type="boolean", nullable=false)
     */
    private $clientType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiry_date", type="datetime", nullable=false)
     */
    private $expiryDate = '0000-00-00 00:00:00';

    /**
     * @var boolean
     *
     * @ORM\Column(name="keep_me_logged", type="boolean", nullable=false)
     */
    private $keepMeLogged;

    /**
     * @var string
     *
     * @ORM\Column(name="social_token", type="string", length=255, nullable=false)
     */
    private $socialToken;

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
     * Set uid
     *
     * @param string $uid
     *
     * @return CmsTubers
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsTubers
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
     * Set latitude
     *
     * @param float $latitude
     *
     * @return CmsTubers
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return CmsTubers
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set logTs
     *
     * @param \DateTime $logTs
     *
     * @return CmsTubers
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

    /**
     * Set clientType
     *
     * @param boolean $clientType
     *
     * @return CmsTubers
     */
    public function setClientType($clientType)
    {
        $this->clientType = $clientType;

        return $this;
    }

    /**
     * Get clientType
     *
     * @return boolean
     */
    public function getClientType()
    {
        return $this->clientType;
    }

    /**
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     *
     * @return CmsTubers
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Set keepMeLogged
     *
     * @param boolean $keepMeLogged
     *
     * @return CmsTubers
     */
    public function setKeepMeLogged($keepMeLogged)
    {
        $this->keepMeLogged = $keepMeLogged;

        return $this;
    }

    /**
     * Get keepMeLogged
     *
     * @return boolean
     */
    public function getKeepMeLogged()
    {
        return $this->keepMeLogged;
    }

    /**
     * Set socialToken
     *
     * @param string socialToken
     *
     * @return CmsTubers
     */
    public function setSocialToken($socialToken)
    {
        $this->socialToken = $socialToken;

        return $this;
    }

    /**
     * Get socialToken
     *
     * @return string
     */
    public function getSocialToken()
    {
        return $this->socialToken;
    }
}