<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ZomatoRestaurantNew
 *
 * @ORM\Table(name="zomato_restaurant_new")
 * @ORM\Entity
 */
class ZomatoRestaurantNew
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
     * @ORM\Column(name="res_name", type="string", length=200, nullable=false)
     */
    private $resName;

    /**
     * @var string
     *
     * @ORM\Column(name="res_description", type="text", length=16777215, nullable=true)
     */
    private $resDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="res_about", type="text", length=16777215, nullable=true)
     */
    private $resAbout;

    /**
     * @var string
     *
     * @ORM\Column(name="res_website", type="string", length=200, nullable=true)
     */
    private $resWebsite;

    /**
     * @var string
     *
     * @ORM\Column(name="res_address", type="text", length=65535, nullable=true)
     */
    private $resAddress;

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
     * @ORM\Column(name="res_contact", type="string", length=100, nullable=true)
     */
    private $resContact;

    /**
     * @var float
     *
     * @ORM\Column(name="res_latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $resLatitude;

    /**
     * @var float
     *
     * @ORM\Column(name="res_longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $resLongitude;

    /**
     * @var string
     *
     * @ORM\Column(name="res_imagelink", type="string", length=255, nullable=true)
     */
    private $resImagelink;

    /**
     * @var string
     *
     * @ORM\Column(name="res_link", type="string", length=255, nullable=true)
     */
    private $resLink;

    /**
     * @var string
     *
     * @ORM\Column(name="res_cost", type="string", length=255, nullable=true)
     */
    private $resCost;

    /**
     * @var string
     *
     * @ORM\Column(name="res_feature", type="string", length=255, nullable=true)
     */
    private $resFeature;

    /**
     * @var string
     *
     * @ORM\Column(name="opening_hr", type="string", length=500, nullable=true)
     */
    private $openingHr;

    /**
     * @var string
     *
     * @ORM\Column(name="res_fax", type="string", length=100, nullable=true)
     */
    private $resFax;

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
     * Set resName
     *
     * @param string $resName
     *
     * @return ZomatoRestaurantNew
     */
    public function setResName($resName)
    {
        $this->resName = $resName;

        return $this;
    }

    /**
     * Get resName
     *
     * @return string
     */
    public function getResName()
    {
        return $this->resName;
    }

    /**
     * Set resDescription
     *
     * @param string $resDescription
     *
     * @return ZomatoRestaurantNew
     */
    public function setResDescription($resDescription)
    {
        $this->resDescription = $resDescription;

        return $this;
    }

    /**
     * Get resDescription
     *
     * @return string
     */
    public function getResDescription()
    {
        return $this->resDescription;
    }

    /**
     * Set resAbout
     *
     * @param string $resAbout
     *
     * @return ZomatoRestaurantNew
     */
    public function setResAbout($resAbout)
    {
        $this->resAbout = $resAbout;

        return $this;
    }

    /**
     * Get resAbout
     *
     * @return string
     */
    public function getResAbout()
    {
        return $this->resAbout;
    }

    /**
     * Set resWebsite
     *
     * @param string $resWebsite
     *
     * @return ZomatoRestaurantNew
     */
    public function setResWebsite($resWebsite)
    {
        $this->resWebsite = $resWebsite;

        return $this;
    }

    /**
     * Get resWebsite
     *
     * @return string
     */
    public function getResWebsite()
    {
        return $this->resWebsite;
    }

    /**
     * Set resAddress
     *
     * @param string $resAddress
     *
     * @return ZomatoRestaurantNew
     */
    public function setResAddress($resAddress)
    {
        $this->resAddress = $resAddress;

        return $this;
    }

    /**
     * Get resAddress
     *
     * @return string
     */
    public function getResAddress()
    {
        return $this->resAddress;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return ZomatoRestaurantNew
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
     * @return ZomatoRestaurantNew
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
     * @return ZomatoRestaurantNew
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
     * Set resContact
     *
     * @param string $resContact
     *
     * @return ZomatoRestaurantNew
     */
    public function setResContact($resContact)
    {
        $this->resContact = $resContact;

        return $this;
    }

    /**
     * Get resContact
     *
     * @return string
     */
    public function getResContact()
    {
        return $this->resContact;
    }

    /**
     * Set resLatitude
     *
     * @param float $resLatitude
     *
     * @return ZomatoRestaurantNew
     */
    public function setResLatitude($resLatitude)
    {
        $this->resLatitude = $resLatitude;

        return $this;
    }

    /**
     * Get resLatitude
     *
     * @return float
     */
    public function getResLatitude()
    {
        return $this->resLatitude;
    }

    /**
     * Set resLongitude
     *
     * @param float $resLongitude
     *
     * @return ZomatoRestaurantNew
     */
    public function setResLongitude($resLongitude)
    {
        $this->resLongitude = $resLongitude;

        return $this;
    }

    /**
     * Get resLongitude
     *
     * @return float
     */
    public function getResLongitude()
    {
        return $this->resLongitude;
    }

    /**
     * Set resImagelink
     *
     * @param string $resImagelink
     *
     * @return ZomatoRestaurantNew
     */
    public function setResImagelink($resImagelink)
    {
        $this->resImagelink = $resImagelink;

        return $this;
    }

    /**
     * Get resImagelink
     *
     * @return string
     */
    public function getResImagelink()
    {
        return $this->resImagelink;
    }

    /**
     * Set resLink
     *
     * @param string $resLink
     *
     * @return ZomatoRestaurantNew
     */
    public function setResLink($resLink)
    {
        $this->resLink = $resLink;

        return $this;
    }

    /**
     * Get resLink
     *
     * @return string
     */
    public function getResLink()
    {
        return $this->resLink;
    }

    /**
     * Set resCost
     *
     * @param string $resCost
     *
     * @return ZomatoRestaurantNew
     */
    public function setResCost($resCost)
    {
        $this->resCost = $resCost;

        return $this;
    }

    /**
     * Get resCost
     *
     * @return string
     */
    public function getResCost()
    {
        return $this->resCost;
    }

    /**
     * Set resFeature
     *
     * @param string $resFeature
     *
     * @return ZomatoRestaurantNew
     */
    public function setResFeature($resFeature)
    {
        $this->resFeature = $resFeature;

        return $this;
    }

    /**
     * Get resFeature
     *
     * @return string
     */
    public function getResFeature()
    {
        return $this->resFeature;
    }

    /**
     * Set openingHr
     *
     * @param string $openingHr
     *
     * @return ZomatoRestaurantNew
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
     * Set resFax
     *
     * @param string $resFax
     *
     * @return ZomatoRestaurantNew
     */
    public function setResFax($resFax)
    {
        $this->resFax = $resFax;

        return $this;
    }

    /**
     * Get resFax
     *
     * @return string
     */
    public function getResFax()
    {
        return $this->resFax;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return ZomatoRestaurantNew
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