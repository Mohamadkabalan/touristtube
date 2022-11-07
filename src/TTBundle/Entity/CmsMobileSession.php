<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsMobileSession
 *
 * @ORM\Table(name="cms_mobile_session")
 * @ORM\Entity
 */
class CmsMobileSession
{
    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $userid;

    /**
     * @var string
     *
     * @ORM\Column(name="tokenid", type="string", length=200)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $tokenid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiry_date", type="datetime", nullable=true)
     */
    private $expiryDate;



    /**
     * Set userid
     *
     * @param integer $userid
     *
     * @return CmsMobileSession
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

    /**
     * Set tokenid
     *
     * @param string $tokenid
     *
     * @return CmsMobileSession
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
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     *
     * @return CmsMobileSession
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
}
