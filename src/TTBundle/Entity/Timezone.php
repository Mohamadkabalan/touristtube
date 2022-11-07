<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Timezone
 *
 * @ORM\Table(name="timezone")
 * @ORM\Entity
 */
class Timezone
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
     * @ORM\Column(name="country_code", type="string", length=2, nullable=true)
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="timezoneid", type="string", length=255, nullable=true)
     */
    private $timezoneid;

    /**
     * @var float
     *
     * @ORM\Column(name="gmtoffset", type="float", precision=10, scale=0, nullable=true)
     */
    private $gmtoffset;

    /**
     * @var float
     *
     * @ORM\Column(name="dstoffest", type="float", precision=10, scale=0, nullable=true)
     */
    private $dstoffest;

    /**
     * @var float
     *
     * @ORM\Column(name="rawoffset", type="float", precision=10, scale=0, nullable=true)
     */
    private $rawoffset;



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
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return Timezone
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set timezoneid
     *
     * @param string $timezoneid
     *
     * @return Timezone
     */
    public function setTimezoneid($timezoneid)
    {
        $this->timezoneid = $timezoneid;

        return $this;
    }

    /**
     * Get timezoneid
     *
     * @return string
     */
    public function getTimezoneid()
    {
        return $this->timezoneid;
    }

    /**
     * Set gmtoffset
     *
     * @param float $gmtoffset
     *
     * @return Timezone
     */
    public function setGmtoffset($gmtoffset)
    {
        $this->gmtoffset = $gmtoffset;

        return $this;
    }

    /**
     * Get gmtoffset
     *
     * @return float
     */
    public function getGmtoffset()
    {
        return $this->gmtoffset;
    }

    /**
     * Set dstoffest
     *
     * @param float $dstoffest
     *
     * @return Timezone
     */
    public function setDstoffest($dstoffest)
    {
        $this->dstoffest = $dstoffest;

        return $this;
    }

    /**
     * Get dstoffest
     *
     * @return float
     */
    public function getDstoffest()
    {
        return $this->dstoffest;
    }

    /**
     * Set rawoffset
     *
     * @param float $rawoffset
     *
     * @return Timezone
     */
    public function setRawoffset($rawoffset)
    {
        $this->rawoffset = $rawoffset;

        return $this;
    }

    /**
     * Get rawoffset
     *
     * @return float
     */
    public function getRawoffset()
    {
        return $this->rawoffset;
    }
}
