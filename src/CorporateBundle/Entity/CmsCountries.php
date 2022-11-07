<?php

namespace CorporateBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CmsCountries
 *
 * @ORM\Table(name="cms_countries")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CmsCountriesRepository")
 */
class CmsCountries
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
     * @ORM\Column(name="code", type="string", length=2)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255, nullable=false)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="iso3", type="string", length=3, nullable=false)
     */
    private $iso3;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="smallint", nullable=false)
     */
    private $number;

    /**
     * @var integer
     *
     * @ORM\Column(name="dialing_code", type="integer")
     */
    private $dialingCode;

    /**
     * @var string
     *
     * @ORM\Column(name="continent_code", type="string", length=2, nullable=false)
     */
    private $continentCode;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="ioc_code", type="string", length=255, nullable=true)
     */
    private $iocCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="popularity", type="integer", nullable=false)
     */
    private $popularity = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="flag_icon", type="string", length=20, nullable=true)
     */
    private $flagIcon;

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
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return CmsCountries
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return CmsCountries
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return CmsCountries
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set iso3
     *
     * @param string $iso3
     *
     * @return CmsCountries
     */
    public function setIso3($iso3)
    {
        $this->iso3 = $iso3;

        return $this;
    }

    /**
     * Get iso3
     *
     * @return string
     */
    public function getIso3()
    {
        return $this->iso3;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return CmsCountries
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set dialing code
     *
     * @param integer $dialingCode
     */
    public function setDialingCode($dialingCode)
    {
        $this->dialingCode = $dialingCode;
    }

    /**
     * Get dialing code
     *
     * @return integer
     */
    public function getDialingCode()
    {
        return $this->dialingCode;
    }

    /**
     * Get country name and dialing code 
     *
     * @return string
     */
    public function getDialingCodeToString()
    {
        return $this->getName()." (+".$this->getDialingCode().")";
    }

    /**
     * Set continentCode
     *
     * @param string $continentCode
     *
     * @return CmsCountries
     */
    public function setContinentCode($continentCode)
    {
        $this->continentCode = $continentCode;

        return $this;
    }

    /**
     * Get continentCode
     *
     * @return string
     */
    public function getContinentCode()
    {
        return $this->continentCode;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return CmsCountries
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
     * @return CmsCountries
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
     * Set iocCode
     *
     * @param string $iocCode
     *
     * @return CmsCountries
     */
    public function setIocCode($iocCode)
    {
        $this->iocCode = $iocCode;

        return $this;
    }

    /**
     * Get iocCode
     *
     * @return string
     */
    public function getIocCode()
    {
        return $this->iocCode;
    }

    /**
     * Set popularity
     *
     * @param integer $popularity
     *
     * @return CmsCountries
     */
    public function setPopularity($popularity)
    {
        $this->popularity = $popularity;

        return $this;
    }

    /**
     * Get popularity
     *
     * @return integer
     */
    public function getPopularity()
    {
        return $this->popularity;
    }

    /**
     * Set flag icon
     *
     * @param string $flagIcon
     *
     * @return string
     */
    public function setFlagIcon($flagIcon)
    {
        $this->flagIcon = $flagIcon;

        return $this;
    }

    /**
     * Get flag icon
     *
     * @return string
     */
    public function getFlagIcon()
    {
        return $this->flagIcon;
    }
}