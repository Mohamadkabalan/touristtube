<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoiFrance
 *
 * @ORM\Table(name="poi_france")
 * @ORM\Entity
 */
class PoiFrance
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
     * @ORM\Column(name="poi_name", type="string", length=200, nullable=false)
     */
    private $poiName;

    /**
     * @var string
     *
     * @ORM\Column(name="poi_description", type="text", length=16777215, nullable=true)
     */
    private $poiDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="poi_about", type="text", length=16777215, nullable=true)
     */
    private $poiAbout;

    /**
     * @var string
     *
     * @ORM\Column(name="poi_website", type="string", length=200, nullable=true)
     */
    private $poiWebsite;

    /**
     * @var string
     *
     * @ORM\Column(name="poi_address", type="text", length=65535, nullable=true)
     */
    private $poiAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=200, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country_name", type="string", length=200, nullable=true)
     */
    private $countryName;

    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=5, nullable=true)
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="poi_contact", type="string", length=100, nullable=true)
     */
    private $poiContact;

    /**
     * @var float
     *
     * @ORM\Column(name="poi_latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $poiLatitude;

    /**
     * @var float
     *
     * @ORM\Column(name="poi_longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $poiLongitude;

    /**
     * @var string
     *
     * @ORM\Column(name="poi_imagelink", type="string", length=255, nullable=true)
     */
    private $poiImagelink;

    /**
     * @var string
     *
     * @ORM\Column(name="poi_link", type="string", length=255, nullable=true)
     */
    private $poiLink;

    /**
     * @var string
     *
     * @ORM\Column(name="opening_hr", type="string", length=300, nullable=true)
     */
    private $openingHr;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status = '0';



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
     * Set poiName
     *
     * @param string $poiName
     *
     * @return PoiFrance
     */
    public function setPoiName($poiName)
    {
        $this->poiName = $poiName;

        return $this;
    }

    /**
     * Get poiName
     *
     * @return string
     */
    public function getPoiName()
    {
        return $this->poiName;
    }

    /**
     * Set poiDescription
     *
     * @param string $poiDescription
     *
     * @return PoiFrance
     */
    public function setPoiDescription($poiDescription)
    {
        $this->poiDescription = $poiDescription;

        return $this;
    }

    /**
     * Get poiDescription
     *
     * @return string
     */
    public function getPoiDescription()
    {
        return $this->poiDescription;
    }

    /**
     * Set poiAbout
     *
     * @param string $poiAbout
     *
     * @return PoiFrance
     */
    public function setPoiAbout($poiAbout)
    {
        $this->poiAbout = $poiAbout;

        return $this;
    }

    /**
     * Get poiAbout
     *
     * @return string
     */
    public function getPoiAbout()
    {
        return $this->poiAbout;
    }

    /**
     * Set poiWebsite
     *
     * @param string $poiWebsite
     *
     * @return PoiFrance
     */
    public function setPoiWebsite($poiWebsite)
    {
        $this->poiWebsite = $poiWebsite;

        return $this;
    }

    /**
     * Get poiWebsite
     *
     * @return string
     */
    public function getPoiWebsite()
    {
        return $this->poiWebsite;
    }

    /**
     * Set poiAddress
     *
     * @param string $poiAddress
     *
     * @return PoiFrance
     */
    public function setPoiAddress($poiAddress)
    {
        $this->poiAddress = $poiAddress;

        return $this;
    }

    /**
     * Get poiAddress
     *
     * @return string
     */
    public function getPoiAddress()
    {
        return $this->poiAddress;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return PoiFrance
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set countryName
     *
     * @param string $countryName
     *
     * @return PoiFrance
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * Get countryName
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return PoiFrance
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
     * Set poiContact
     *
     * @param string $poiContact
     *
     * @return PoiFrance
     */
    public function setPoiContact($poiContact)
    {
        $this->poiContact = $poiContact;

        return $this;
    }

    /**
     * Get poiContact
     *
     * @return string
     */
    public function getPoiContact()
    {
        return $this->poiContact;
    }

    /**
     * Set poiLatitude
     *
     * @param float $poiLatitude
     *
     * @return PoiFrance
     */
    public function setPoiLatitude($poiLatitude)
    {
        $this->poiLatitude = $poiLatitude;

        return $this;
    }

    /**
     * Get poiLatitude
     *
     * @return float
     */
    public function getPoiLatitude()
    {
        return $this->poiLatitude;
    }

    /**
     * Set poiLongitude
     *
     * @param float $poiLongitude
     *
     * @return PoiFrance
     */
    public function setPoiLongitude($poiLongitude)
    {
        $this->poiLongitude = $poiLongitude;

        return $this;
    }

    /**
     * Get poiLongitude
     *
     * @return float
     */
    public function getPoiLongitude()
    {
        return $this->poiLongitude;
    }

    /**
     * Set poiImagelink
     *
     * @param string $poiImagelink
     *
     * @return PoiFrance
     */
    public function setPoiImagelink($poiImagelink)
    {
        $this->poiImagelink = $poiImagelink;

        return $this;
    }

    /**
     * Get poiImagelink
     *
     * @return string
     */
    public function getPoiImagelink()
    {
        return $this->poiImagelink;
    }

    /**
     * Set poiLink
     *
     * @param string $poiLink
     *
     * @return PoiFrance
     */
    public function setPoiLink($poiLink)
    {
        $this->poiLink = $poiLink;

        return $this;
    }

    /**
     * Get poiLink
     *
     * @return string
     */
    public function getPoiLink()
    {
        return $this->poiLink;
    }

    /**
     * Set openingHr
     *
     * @param string $openingHr
     *
     * @return PoiFrance
     */
    public function setOpeningHr($openingHr)
    {
        $this->openingHr = $openingHr;

        return $this;
    }

    /**
     * Get openingHr
     *
     * @return string
     */
    public function getOpeningHr()
    {
        return $this->openingHr;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return PoiFrance
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
}
