<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsMobileToken
 *
 * @ORM\Table(name="cms_mobile_token", indexes={@ORM\Index(name="userid", columns={"tokenid"}), @ORM\Index(name="platform", columns={"platform"})})
 * @ORM\Entity(repositoryClass="TTBundle\Repository\UserRepository")
 * @ORM\Entity
 */
class CmsMobileToken
{
    /**
     * @var string
     *
     * @ORM\Column(name="ssid", type="string", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $ssid;

    /**
     * @var string
     *
     * @ORM\Column(name="tokenid", type="string", length=200)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $tokenid;

    /**
     * @var integer
     *
     * @ORM\Column(name="platform", type="smallint", nullable=false)
     */
    private $platform = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="ap_type", type="string", length=10, nullable=false)
     */
    private $apType;

    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer", nullable=false)
     */
    private $userid;

    /**
     * Set ssid
     *
     * @param string $ssid
     *
     * @return CmsMobileToken
     */
    public function setSsid($ssid)
    {
        $this->ssid = $ssid;

        return $this;
    }

    /**
     * Get ssid
     *
     * @return string
     */
    public function getSsid()
    {
        return $this->ssid;
    }

    /**
     * Set tokenid
     *
     * @param string $tokenid
     *
     * @return CmsMobileToken
     */
    public function setTokenid($tokenid)
    {
        $this->tokenid = $tokenid;

        return $this;
    }

    /**
     * Get tokenid
     *
     * @return string
     */
    public function getTokenid()
    {
        return $this->tokenid;
    }

    /**
     * Set platform
     *
     * @param integer $platform
     *
     * @return CmsMobileToken
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * Get platform
     *
     * @return integer
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Set apType
     *
     * @param string $apType
     *
     * @return CmsMobileToken
     */
    public function setApType($apType)
    {
        $this->apType = $apType;

        return $this;
    }

    /**
     * Get apType
     *
     * @return string
     */
    public function getApType()
    {
        return $this->apType;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     *
     * @return CmsMobileToken
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return integer
     */
    public function getUserid()
    {
        return $this->userid;
    }
}